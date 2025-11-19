<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\License;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\LicenseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LicenseResource\RelationManagers;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Public';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('info')
                    ->schema([
                        TextInput::make('qty')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(1)
                            ->label('Quantity')
                            ->helperText('Number of license codes to generate'),

                        Hidden::make('user_id')->default(Auth::user()->id)->dehydrated(true),
                        Placeholder::make('note')
                            ->content('License codes will be automatically generated based on quantity')
                            ->columnSpanFull(),

                        // Hidden::make('user_id')->default(Auth::user()->id),

                    ])->collapsed(false)->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([100, 150, 200,  'all'])
            ->columns([
                TextColumn::make('code')->searchable()
                    ->label('Code')
                    ->copyable()
                    ->copyMessage('License code copied!')
                    ->copyMessageDuration(1500),
                // TextColumn::make('license_checkssite')->getStateUsing(function ($record) {
                //     if ($record->license_checks && $record->license_checks->count() > 0) {
                //         $licenseCheck = $record->license_checks->first();
                //         return $licenseCheck ? $licenseCheck->site : 'N/A';
                //     }
                //     return 'N/A';
                // })->label('Site'),
                TextColumn::make('licensechecks.site')->label('Site'),
                // TextColumn::make('licensechecks.user_id')->label('User'),
                TextColumn::make('licensechecks.user_id')->getStateUsing(function ($record) {
                    if ($record->license_checks && $record->license_checks->count() > 0) {
                        $licenseCheck = $record->license_checks->first();
                        if ($licenseCheck && $licenseCheck->user_id) {
                            $user = MainUser::find($licenseCheck->user_id);
                            return $user ? $user->fl_name : 'N/A';
                        }
                    }
                    return 'N/A';
                })->label('User'),

                IconColumn::make('status')->label('Status')->boolean(),
                TextColumn::make('created_at')->label('Created At')->badge(),
                // TextColumn::make('updated_at')->label('Updated At')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['licensechecks' => function ($query) {
                $query->orderBy('id', 'desc');
            }])
            ->orderBy('id', 'desc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
