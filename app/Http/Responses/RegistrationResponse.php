<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class RegistrationResponse extends \Filament\Http\Responses\Auth\RegistrationResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (Filament::getCurrentPanel()->getId() === 'app') {
            $intended = session()->pull('intended_url', '/home');

            return redirect()->intended($intended);
        }

        if (Filament::getCurrentPanel()->getId() === 'admin') {
            return redirect()->to(url('/admin'));
        }

        return parent::toResponse($request);
    }
}
