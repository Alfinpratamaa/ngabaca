<?php

namespace App\Livewire;

use App\Mail\SendOtpMail;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmailOtpVerificationPage extends Component
{
    public $email = '';
    public $otp = '';
    public $errorMessage = '';
    public $successMessage = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'Email Anda sudah diverifikasi.');
        }
        $this->email = $user->email;

        // --- Perbaikan Utama ---
        // Cek jika OTP awal belum pernah dikirim dalam session ini
        if (!session()->has('otp_initial_sent')) {
            $this->sendOtp(); // Kirim OTP hanya jika flag session tidak ada

            // Set flag di session untuk menandakan OTP awal sudah dikirim
            session(['otp_initial_sent' => true]);
        }
    }

    public function sendOtp()
    {
        $this->reset(['errorMessage', 'successMessage', 'otp']); // Reset pesan dan input OTP
        $user = Auth::user();

        try {
            $otpCode = random_int(100000, 999999);

            $user->email_otp = $otpCode;
            $user->email_otp_expires_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new SendOtpMail((string)$otpCode));

            $this->successMessage = 'Kode OTP baru telah dikirim ke ' . $this->email;
        } catch (Exception $e) {
            Log::error('Email Send OTP Error: ' . $e->getMessage());
            $this->errorMessage = 'Gagal mengirim OTP. Silakan coba lagi nanti.';
        }
    }

    public function verifyOtp()
    {
        // Pastikan Anda mengubah aturan validasi untuk mencocokkan tipe data 'otp' yang berupa string
        $this->validate([
            'otp' => 'required|string|digits:6'
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus 6 digit.',
        ]);

        $this->reset(['errorMessage', 'successMessage']);
        $user = Auth::user();

        if ($user->email_otp !== $this->otp) {
            $this->addError('otp', 'Kode OTP yang Anda masukkan salah.');
            return;
        }

        if (now()->gt($user->email_otp_expires_at)) {
            $this->addError('otp', 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang.');
            return;
        }

        $user->email_verified_at = now();
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->save();

        // Hapus session flag setelah verifikasi berhasil
        session()->forget('otp_initial_sent');

        return redirect()->route('home')->with('success', 'Email berhasil diverifikasi!');
    }

    public function render()
    {
        return view('livewire.email-otp-verification-page');
    }
}
