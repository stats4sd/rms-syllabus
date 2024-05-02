<?php

namespace App\Filament\App\Infolists\Actions;

use Closure;
use Egulias\EmailValidator\Parser\CommentStrategy\LocalComment;
use Filament\Facades\Filament;
use Filament\Infolists\Components\Actions\Action;
use Filament\Pages\Auth\Login;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
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
            ->modalWidth('md');

        // TODO: find the blade and add <x-filament-socialite::buttons :show-divider="true"
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
