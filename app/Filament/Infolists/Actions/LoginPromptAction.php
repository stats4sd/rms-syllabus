<?php

namespace App\Filament\Infolists\Actions;

use Closure;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Auth;

class LoginPromptAction extends Action
{
    protected Closure|string|null $redirectTo = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation()
            ->modalHeading('Keep Track of Your Journey')
            ->modalIcon('heroicon-o-bookmark')
            ->modalIconColor('darkblue')
            ->modalDescription('Save your progress with a free account.')
            ->modalAlignment(Alignment::Start)
            ->modalSubmitActionLabel('Login or Signup')
            ->visible(Auth::guest())
            ->modalCancelAction(fn() =>
            Action::make('cancel')
                ->url($this->getRedirectTo() ?? '#')
                ->close()
                ->label('Continue as Guest')
            );
    }


    public function action(Closure|string|null $action): static
    {

        if ($action) {
            $this->action = $action;

        } else {
            $this->action = fn(Module $record) => redirect($this->getRedirectTo());

        }


        return $this;
    }

    public function redirectTo(Closure|string|null $redirect): static
    {
        $this->redirectTo = $redirect;

        return $this;
    }

    public function getRedirectTo(): ?string
    {
        return $this->evaluate($this->redirectTo);
    }

}
