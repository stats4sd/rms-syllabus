<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;

class LogoutResponse extends \Filament\Http\Responses\Auth\LogoutResponse
{
    public function toResponse($request): RedirectResponse
    {

        Notification::make('logged out')
            ->success()
            ->title('You have been logged out')
            ->duration(5000)
            ->send();

        return redirect()->back();
    }
}
