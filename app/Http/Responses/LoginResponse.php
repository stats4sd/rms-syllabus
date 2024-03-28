<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (Filament::getCurrentPanel()->getId() === 'app') {
            return redirect()->to(url('/home'));
        }

        if (Filament::getCurrentPanel()->getId() === 'admin') {
            return redirect()->to(url('/admin'));
        }
 
        return parent::toResponse($request);
    }
}