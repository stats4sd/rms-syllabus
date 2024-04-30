<?php

namespace App\Filament\App\Infolists\Actions;

use Closure;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Auth;

class LoginPromptWithFormAction extends Action
{
    protected Closure|string|null $cancelRedirectsTo = null;


    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->visible(Auth::guest())
            ->modalHeading('Keep Track of Your Journey')
            ->modalIcon('heroicon-o-bookmark')
            ->modalIconColor('darkblue')
            ->modalDescription('Save your progress with a free account.')
            ->modalAlignment(Alignment::Start)
            ->modalFooterActions([
                Action::make('cancel')
                    ->url(fn() => $this->getCancelRedirectsTo() ?? '#')
                    ->close()
                    ->label('Continue as Guest')
            ])
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalContent(fn() => view('filament.app.infolists.components.login-prompt-with-form-action'))
            ->modalWidth('md')
            ->mountUsing(function () {
                // set the intended url to redirect the user after login.
                session()->put('intended_url', $this->getCancelRedirectsTo());
        });

    }


    public function action(Closure|string|null $action): static
    {

        if ($action) {
            $this->action = $action;

        } else {
            $this->action = fn(Module $record) => redirect($this->getCancelRedirectsTo());
        }


        return $this;
    }

    public function cancelRedirectsTo(Closure|string|null $redirect): static
    {
        $this->cancelRedirectsTo = $redirect;

        return $this;
    }

    public function getCancelRedirectsTo(): ?string
    {
        return $this->evaluate($this->cancelRedirectsTo);
    }

}
