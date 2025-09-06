<?php

namespace App\Filament\Resources\MainUserResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\MainUserResource;
use Filament\Tables\Table;
use Filament\Forms\Components\Placeholder;

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
                    // Section::make('Products Information') 
                    // ->schema([
                    //     Placeholder::make('products_info')
                    //         ->label('Products')
                    //         ->content(function ($record) {
                    //             if (!$record || !$record->products || $record->products->isEmpty()) {
                    //                 return '<div class="text-gray-500 italic">No products found for this user.</div>';
                    //             }
                                
                    //             $html = '<div class="overflow-x-auto">';
                    //             $html .= '<table class="min-w-full divide-y divide-gray-200 bg-white border border-gray-200 rounded-lg">';
                                
                    //             // Table Header
                    //             $html .= '<thead class="bg-gray-50">';
                    //             $html .= '<tr>';
                    //             $html .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Title</th>';
                    //             $html .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Category</th>';
                    //             $html .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Description</th>';
                    //             $html .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Status</th>';
                    //             $html .= '</tr>';
                    //             $html .= '</thead>';
                                
                    //             // Table Body
                    //             $html .= '<tbody class="bg-white divide-y divide-gray-200">';
                    //             foreach ($record->products as $product) {
                    //                 $html .= '<tr class="hover:bg-gray-50">';
                    //                 $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . htmlspecialchars($product->title) . '</td>';
                    //                 $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . ($product->category ? htmlspecialchars($product->category->title) : '-') . '</td>';
                    //                 $html .= '<td class="px-6 py-4 text-sm text-gray-500">' . htmlspecialchars(substr($product->description ?? '', 0, 50)) . (strlen($product->description ?? '') > 50 ? '...' : '') . '</td>';
                    //                 $html .= '<td class="px-6 py-4 whitespace-nowrap">';
                    //                 if (isset($product->status)) {
                    //                     $statusClass = $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    //                     $statusText = $product->status ? 'Active' : 'Inactive';
                    //                     $html .= '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $statusClass . '">' . $statusText . '</span>';
                    //                 } else {
                    //                     $html .= '<span class="text-gray-400">-</span>';
                    //                 }
                    //                 $html .= '</td>';
                    //                 $html .= '</tr>';
                    //             }
                    //             $html .= '</tbody>';
                    //             $html .= '</table>';
                    //             $html .= '</div>';
                                
                    //             return $html;
                    //         })
                    //         ->columnSpan(2),
                    // ])
                    // ->columns(2)
                    // ->collapsible(),
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
