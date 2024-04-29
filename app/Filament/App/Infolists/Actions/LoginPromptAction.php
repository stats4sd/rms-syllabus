<?php

namespace App\Filament\App\Infolists\Actions;

use App\Providers\Filament\AppPanelProvider;
use Closure;
use Filament\Infolists\Components\Actions\Action;
use Filament\Pages\Auth\Login;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Auth;

class LoginPromptAction extends Action
{
    protected Closure|string|null $cancelRedirectsTo = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation()
            ->visible(Auth::guest())
            ->modalHeading('Keep Track of Your Journey')
            ->modalIcon('heroicon-o-bookmark')
            ->modalIconColor('darkblue')
            ->modalDescription('Save your progress with a free account.')
            ->modalAlignment(Alignment::Start)
            ->modalSubmitAction(fn() => Action::make('Login or Signup')
                ->url(filament()->getLoginUrl()))
            ->modalCancelAction(fn() =>
            Action::make('cancel')
                ->url($this->getCancelRedirectsTo() ?? '#')
                ->close()
                ->label('Continue as Guest')
            );
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
