<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use App\Models\MainUser;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Tapp\FilamentAuthenticationLog\RelationManagers\AuthenticationLogsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

 
    
    protected static ?string $navigationLabel = "admin";
    protected static ?string $modelLabel = "  admin   ";
    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

   

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('')
                ->schema([
                    // Hidden::make('role')->default('super-admin')->dehydrated(true),
                    TextInput::make('name')->label('name')->required(),
                    TextInput::make('email')->label('email')->email()->required(),
                    TextInput::make('phone')->label('phone')->required(),
                    // TextInput::make('mac_address')->label('mac_address')->required(),
                    TextInput::make('password')->minLength(6)
                        ->password()->required()
                        ->label('password')->helperText('Password must be at least 6 characters 
                '),
                Toggle::make('status')->label('status'),

                    // Select::make('roles')
                    //     ->relationship('roles', 'name')
                    //     ->multiple()
                    //     ->preload()
                    //     ->searchable(),

                ])->collapsible()->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('name')->searchable()->label('name'),
                TextColumn::make('email')->searchable()->label('email'),
                TextColumn::make('phone')->searchable()->label('phone'),
                TextColumn::make('data_entries_count')->counts('data_entries')->badge()->label('Data Entries'),
                // TextColumn::make('mac_address')->label('mac_address'),
                // TextColumn::make('mainuser_works_count')->counts('mainuser_works')->badge()->label('Works'),
                TextColumn::make('agent_code')->searchable()->label('Agent Code')->badge()->color('danger'),
                IconColumn::make('status')
                ->boolean(),

                // TextColumn::make('created_at')->label('created_at')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Action::make('new_user')
                //     ->label('New User')
                //     ->icon('heroicon-o-plus-circle')
                //     ->color('primary')
                //     ->url(fn () => 'https://beauty.besmani.com/' . auth()->user()->id, shouldOpenInNewTab: true),
            ])
            ->actions([   
                Tables\Actions\EditAction::make(), 
                // Action::make('besmani')
                //     ->label('Register New')
                //     ->icon('heroicon-o-arrow-top-right-on-square')
                //     ->color('success')
                //     ->url('https://besmani.com', shouldOpenInNewTab: true),
                // Action::make('report')
                //     ->label('Report')
                //     ->icon('heroicon-o-chart-bar')
                //     ->color('info')
                //     ->modalHeading('User Reports & Analytics')
                //     ->modalWidth(MaxWidth::SevenExtraLarge)
                //     ->modalContent(function (User $record) {
                //         // Find MainUsers using phone number in Mobile_moaref column
                //         $mainUsers = MainUser::where('mobile_moaref', $record->phone)->get();
                        
                //         if ($mainUsers->isEmpty()) {
                //             return view('filament.pages.no-main-user-found-en');
                //         }
                        
                //         // Get all user IDs from found MainUsers
                //         $userIds = $mainUsers->pluck('id')->toArray();
                        
                //         // Initialize collections for aggregated data
                //         $allClinicServices = collect();
                //         $allWomenSalonServices = collect(); 
                //         $allMenSalonServices = collect();
                //         $allWomenAcademyCourses = collect();
                //         $allMenAcademyCourses = collect();
                //         $allProducts = collect();
                //         $allPortfolios = collect();
                //         $allActivities = collect();
                        
                //         // Collect data from all user IDs
                //         foreach ($userIds as $userId) {
                //             $allClinicServices = $allClinicServices->merge(
                //                 \App\Models\ClinicService::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //             $allWomenSalonServices = $allWomenSalonServices->merge(
                //                 \App\Models\WomenServiceSalon::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //             $allMenSalonServices = $allMenSalonServices->merge(
                //                 \App\Models\MenSalonService::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //             $allWomenAcademyCourses = $allWomenAcademyCourses->merge(
                //                 \App\Models\WomenAcademyCourse::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //             $allMenAcademyCourses = $allMenAcademyCourses->merge(
                //                 \App\Models\MenAcademyCourse::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //             $allProducts = $allProducts->merge(
                //                 \App\Models\Product::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //             $allPortfolios = $allPortfolios->merge(
                //                 \App\Models\WsPortfolio::where('user_id', $userId)->orderBy('created_at', 'desc')->get()
                //             );
                //             $allActivities = $allActivities->merge(
                //                 \App\Models\InfoActivity::where('user_id', $userId)->orderBy('create_at', 'desc')->get()
                //             );
                //         }
                        
                //         // Use the first MainUser for primary display
                //         $mainUser = $mainUsers->first();
                        
                //         // Collect all data using MainUser IDs found by Mobile_moaref
                //         $userData = [
                //             'user_info' => [
                //                 'Total Users Found' => $mainUsers->count(),
                //                 'Search Phone' => $record->phone,
                //                 'Primary ID' => $mainUser->id,
                //                 'Primary Name' => trim($mainUser->fl_name . ' ' . $mainUser->last_name),
                //                 'Primary Email' => $mainUser->email,
                //                 'Primary Mobile' => $mainUser->mobile,
                //                 'Confirm Code' => $mainUser->confirm_code,
                //                 'Reference' => $mainUser->fl_moaref,
                //                 'Approved' => $mainUser->approved ? 'Yes' : 'No',
                //                 'All User IDs' => implode(', ', $userIds),
                //             ],
                //             'clinic_services' => $allClinicServices,
                //             'women_salon_services' => $allWomenSalonServices,
                //             'men_salon_services' => $allMenSalonServices,
                //             'women_academy_courses' => $allWomenAcademyCourses,
                //             'men_academy_courses' => $allMenAcademyCourses,
                //             'products' => $allProducts,
                //             'portfolios' => $allPortfolios,
                //             'activities' => $allActivities,
                //             'counts' => [
                //                 'activities' => $allActivities->count(),
                //                 'products' => $allProducts->count(),
                //                 'portfolios' => $allPortfolios->count(),
                //                 'clinic_services' => $allClinicServices->count(),
                //                 'women_salon_services' => $allWomenSalonServices->count(),
                //                 'men_salon_services' => $allMenSalonServices->count(),
                //                 'women_academy_courses' => $allWomenAcademyCourses->count(),
                //                 'men_academy_courses' => $allMenAcademyCourses->count(),
                //             ]
                //         ];
                        
                //         return view('filament.pages.user-reports-en', compact('mainUser', 'userData', 'mainUsers'));
                //     })
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Close'),
             ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ 
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuthenticationLogsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('id', 'desc');
            // ->where('role', 0)
    }
}
