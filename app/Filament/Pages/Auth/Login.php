<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Login extends BaseLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailOrPhoneFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailOrPhoneFormComponent(): Component
    {
        return TextInput::make('emailOrPhone')
            ->label('Email address or Phone number')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    } 

    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        $data = $this->form->getState();

        $emailOrPhone = $data['emailOrPhone'] ?? '';
        $password = $data['password'] ?? '';
        $remember = $data['remember'] ?? false;

        // Determine if input is email or phone
        $isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);
        
        // Find user by email or phone
        if ($isEmail) {
            $user = User::where('email', $emailOrPhone)->first();
        } else {
            // Clean phone number (remove formatting)
            $cleanPhone = preg_replace('/[^0-9]/', '', $emailOrPhone);
            $user = User::where('phone', $cleanPhone)->first();
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'data.emailOrPhone' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'data.emailOrPhone' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        // Log the user in
        // Admin panel uses default 'web' guard
        Auth::guard('web')->login($user, $remember);
        session()->regenerate();

        return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
    }
}

