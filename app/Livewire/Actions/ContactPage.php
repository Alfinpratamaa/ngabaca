<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class ContactPage extends Component
{
    public $name, $email, $phone, $message;

    public function submitForm()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|min:10',
        ]);

        // Contoh pengiriman email bisa diganti sesuai kebutuhan
        Mail::raw($this->message, function ($msg) {
            $msg->to('support@e-commerce.com')
                ->subject('New Contact Message from ' . $this->name);
        });

        session()->flash('success', 'Your message has been sent successfully.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.contact-page');
    }
}
