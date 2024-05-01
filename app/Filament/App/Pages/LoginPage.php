<?php

namespace App\Filament\App\Pages;

use Filament\Facades\Filament;

class LoginPage extends \Filament\Pages\Auth\Login
{

    // override the parent mount function to store the previous url, so we can redirect the user back to the previous page after login
    public function mount(): void
    {
        session()->put('intended_url', url()->previous());

        parent::mount();
    }

}
