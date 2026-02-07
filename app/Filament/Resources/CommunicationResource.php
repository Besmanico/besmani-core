<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Communication;
use App\Mail\ProviderSendMail;
use Filament\Resources\Resource;
use App\Models\ProviderEmailReport;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CommunicationResource\Pages;
use App\Filament\Resources\CommunicationResource\RelationManagers;

class CommunicationResource extends Resource
{
    protected static ?string $model = MainUser::class;

    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = "  Providers  ";
    protected static ?string $modelLabel = "   Providers  ";
    protected static ?string $pluralModelLabel = "  Providers    ";
    protected static ?int $navigationSort = 2;
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
        
            ])
            ->filters([
                //
            ]) 
            
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('send_email')
                        ->label('Send Email')
                        ->icon('heroicon-o-envelope')
                        ->color('primary')
                        ->form([
                            TextInput::make('sender_name')
                                ->label('Sender Name'),
                                
                                
                            Textarea::make('description')
                                ->label('Description')
                                ->required()
                                ->rows(5)
                                ->columnSpanFull(),
                        ]) 
                        ->action(function (Collection $records, array $data): void {
                            $senderName = $data['sender_name'] ?? '';
                            $description = $data['description'] ?? '';

                            $sent = 0;
                            $failed = [];

                            foreach ($records as $record) {
                                $email = $record->email ?? null;
                                
                                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $failed[] = ($record->fl_name ?? '') . ' ' . ($record->last_name ?? '') . ' (no valid email)';
                                    continue;
                                }

                                try {
                                    // Mail::to($email)->send(new ProviderSendMail($senderName, $description));
                                    // $sent++;
                                    Mail::raw($description, function ($message) use ($email, $senderName, $description) {
                                        $message->to($email)
                                        ->replyTo(env('MAIL_REPLY_TO'), env('MAIL_FROM_NAME'))
                                                ->subject('BESMANI'); 
                                    });
                                    // save the report to the database
                                    ProviderEmailReport::create([
                                        'provider_id' => $record->id,
                                        'user_id' => auth()->user()->id,
                                        'user_name' => $senderName,
                                        'body' => $description,
                                    ]);
                                } catch (\Throwable $e) {
                                    $failed[] = ($record->fl_name ?? '') . ' ' . ($record->last_name ?? '') . ': ' . $e->getMessage();
                                }  
                            }

                            if ($failed) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Emails partially sent')
                                    ->body("Sent: {$sent}. Failed: " . implode('; ', array_slice($failed, 0, 3)) . (count($failed) > 3 ? '...' : ''))
                                    ->warning()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Emails sent')
                                    ->body('Email sent to ' . $sent . ' provider(s).')
                                    ->success()
                                    ->send();
                            }
                        }), 
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommunications::route('/'),
            // 'create' => Pages\CreateCommunication::route('/create'),
            // 'edit' => Pages\EditCommunication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
           ->where('service_pr', 1)->orderBy('id', 'desc');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('service_pr', 1)->count();
    }

}
