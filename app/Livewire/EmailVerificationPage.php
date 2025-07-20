<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Exception;

class PhoneVerificationPage extends Component
{
    public $phoneNumber = '';
    public $otp = ['', '', '', '', '', ''];
    public $isOtpSent = false;
    public $errorMessage = '';

    protected $rules = [
        'phoneNumber' => 'required|numeric|digits_between:9,14',
        'otp.*' => 'required|numeric|digits:1',
    ];

    protected $messages = [
        'phoneNumber.required' => 'Nomor telepon wajib diisi.',
        'phoneNumber.numeric' => 'Nomor telepon harus berupa angka.',
        'phoneNumber.digits_between' => 'Nomor telepon harus antara 9 sampai 14 digit.',
        'otp.*.required' => 'Semua kolom OTP wajib diisi.',
    ];

    public function sendOtp()
    {
        $this->validate(['phoneNumber' => $this->rules['phoneNumber']]);
        $this->errorMessage = '';

        // Format nomor ke E.164 (+62xxxx)
        $formattedPhoneNumber = '+62' . $this->phoneNumber;

        try {
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));

            $verification = $twilio->verify->v2->services(config('services.twilio.verify_sid'))
                ->verifications
                ->create($formattedPhoneNumber, "sms");

            $this->isOtpSent = true;
            Log::info('OTP sent to ' . $formattedPhoneNumber);
            Log::info("Status : " . $verification->status);
            session()->flash('success', 'Kode OTP telah dikirim ke ' . $formattedPhoneNumber);
        } catch (Exception $e) {
            Log::error('Twilio Send OTP Error: ' . $e->getMessage());
            $this->errorMessage = 'Gagal mengirim OTP. Pastikan nomor telepon valid dan coba lagi.';
        }
    }

    public function verifyOtp()
    {
        $this->validate(['otp.*' => $this->rules['otp.*']]);
        $this->errorMessage = '';

        $otpCode = implode('', $this->otp);
        $formattedPhoneNumber = '+62' . $this->phoneNumber;

        try {
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));

            $verification_check = $twilio->verify->v2->services(config('services.twilio.verify_sid'))
                ->verificationChecks
                ->create(['to' => $formattedPhoneNumber, 'code' => $otpCode]);

            if ($verification_check->status == 'approved') {
                $user = Auth::user();
                $user->phone_number = $formattedPhoneNumber;
                $user->is_phone_verified = true;
                $user->save();

                // Redirect ke dashboard atau halaman selanjutnya
                return redirect()->route('dashboard')->with('success', 'Nomor telepon berhasil diverifikasi!');
            } else {
                $this->errorMessage = 'Kode OTP yang Anda masukkan salah.';
            }
        } catch (Exception $e) {
            Log::error('Twilio Verify OTP Error: ' . $e->getMessage());
            $this->errorMessage = 'Gagal memverifikasi OTP. Silakan coba lagi.';
        }
    }

    public function render()
    {
        return view('livewire.phone-verification-page');
    }
}
