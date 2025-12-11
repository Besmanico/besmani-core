<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MainUserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Widgets\WomenSalonServiceStatsWidget;
use App\Filament\Widgets\MainUserStatsWidget;
use App\Filament\Widgets\MainUserRegistrationChart;
use App\Filament\Widgets\MainUserRegistrationLineChart;
use App\Filament\Resources\MainUserResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\WsPortfolioRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\ClinicServiceRelationManager;
use App\Filament\Resources\MainUsertResource\RelationManagers\InfoActivityRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\MenSalonServiceRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\MenAcademyCourseRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\WomenServiceSalonRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\WomenAcademyCourseRelationManager;

class MainUserResource extends Resource
{
    protected static ?string $model = MainUser::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationGroup = 'Public';
    protected static ?string $navigationLabel = "  Main User  ";
    protected static ?string $modelLabel = "   Main User  ";
    protected static ?string $pluralModelLabel = "  Main User    ";
    protected static ?int $navigationSort = 2;
    protected static ?string $globalSearchResultTitleAttribute = 'fl_name';
    
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return trim($record->fl_name . ' ' . $record->last_name) ?: $record->email;
    }
 

    public static function getGloballySearchableAttributes(): array
    {
        return ['fl_name', 'last_name', 'email', 'mobile'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email,
            'Phone' => $record->mobile,
            'Reference' => $record->fl_moaref,
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->url(static::getUrl('view', ['record' => $record]))
                ->icon('heroicon-o-eye'),
        ];
    }

    



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('fl_name')->label('First Name')->required(),
                        TextInput::make('last_name')->label('Last Name')->required(),
                        TextInput::make('email')->label('Email')->required(),
                        TextInput::make('mobile')->label('Phone No.')->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\ImageColumn::make('avatar') 
                ->label('Avatar')
                ->circular()
                ->getStateUsing(function ($record) {
                    if (!$record || !$record->avatar) {
                        // Return default avatar or placeholder
                        return 'https://ui-avatars.com/api/?name=' . urlencode(($record->fl_name ?? '') . ' ' . ($record->last_name ?? '')) . '&color=7F9CF5&background=EBF4FF&size=128';
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                    return $record->avatar ? $beautyUrl . '/public/assets/images/user/' . $record->avatar : null;
                 }) 
                ->url(function ($record) {
                    if (!$record || !$record->avatar) {
                        return null;
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                    return $record->avatar ? $beautyUrl . '/public/assets/images/user/' . $record->avatar : null;
                })
                ->openUrlInNewTab(),
                TextColumn::make('fl_name')->searchable()->label('First Name')->searchable(),
                TextColumn::make('last_name')->searchable()->label('Last Name')->searchable(),
                TextColumn::make('email')->searchable()->label('Email')->searchable(),
                TextColumn::make('mobile')->searchable()->label('Phone No.')->searchable()->badge()->color('success'),
                TextColumn::make('confirm_code')->searchable()->label('Confirm Code')->badge()->color('danger')->toggleable(false),
                TextColumn::make('fl_moaref')->searchable()->label('Reference Name')->toggleable(false),
                TextColumn::make('mobile_moaref')->searchable()->label('Reference Phone No.')->toggleable(),
                TextColumn::make('child')->searchable()->label('Site')->toggleable()->badge()->color('success'),
                TextColumn::make('code_moaref')->searchable()->label('Code')->badge()->toggleable(false),
                // ToggleColumn::make('approved')->label('Approved')->toggleable(false), 

               
            ])
            ->filters([
                //
            ])
            ->actions([
                //  Tables\Actions\DeleteAction::make(),  
                 Tables\Actions\EditAction::make(),  
                 Tables\Actions\ViewAction::make(),  
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
            InfoActivityRelationManager::class,
            ClinicServiceRelationManager::class,
            WomenServiceSalonRelationManager::class,
            MenSalonServiceRelationManager::class,
            WomenAcademyCourseRelationManager::class,
            MenAcademyCourseRelationManager::class,
            ProductsRelationManager::class,   
            WsPortfolioRelationManager::class,

        ];
    }

    public static function getWidgets(): array
    {
        return [
            MainUserStatsWidget::class,
            MainUserRegistrationChart::class,
            MainUserRegistrationLineChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMainUsers::route('/'),
            'create' => Pages\CreateMainUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            //  'edit' => Pages\EditMainUser::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
           ->orderBy('id', 'desc');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

}
