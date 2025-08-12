<?php

namespace App\Filament\Resources\MainUserResource\Pages;

use App\Filament\Resources\MainUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;

class ViewUser extends ViewRecord
{
    protected static string $resource = MainUserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('fl_name')
                            ->label('First Name')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->columnSpan(2),
                        TextInput::make('mobile')
                            ->label('Phone Number')
                            ->disabled()
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('Verification & Status')
                    ->schema([
                        TextInput::make('confirm_code')
                            ->label('Confirmation Code')
                            ->disabled()
                            ->columnSpan(1),
                        Toggle::make('approved')
                            ->label('Approved Status')
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Reference Information')
                    ->schema([
                        TextInput::make('fl_moaref')
                            ->label('Reference Name')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('mobile_moaref')
                            ->label('Reference Phone Number')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('code_moaref')
                            ->label('Reference Code')
                            ->disabled()
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('System Information') 
                    ->schema([
                        TextInput::make('id')
                            ->label('User ID')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('created_at')
                            ->label('Created At')
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('updated_at')
                            ->label('Last Updated')
                            ->disabled()
                            ->columnSpan(2),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Back')
                ->action(fn () => redirect('/admin/main-users'))
                ->color('danger')
        ];
    }
}
