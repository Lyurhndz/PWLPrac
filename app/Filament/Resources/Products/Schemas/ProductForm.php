<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Actions\Action;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Wizard::make([
                    Step::make('Product Info')
                        ->icon('heroicon-o-shopping-bag') // Icon for product info
                        ->description('Input Product Information')
                        ->schema([
                            Group::make([
                                TextInput::make('name')->required(),
                                TextInput::make('sku')->required(),
                            ])->columns(2),
                            MarkdownEditor::make('description')
                        ]),
                
                    Step::make('Pricing & Stock')
                        ->icon('heroicon-o-currency-dollar') // Icon for pricing
                        ->description('Input Price and Stock')
                        ->schema([
                            Group::make([
                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1) // Validasi minimal harga > 0
                                    ->validationMessages([
                                        'min' => 'Price must be greater than 0',
                                    ]),
                                TextInput::make('stock')
                                    ->numeric()
                                    ->required(),                                
                            ])->columns(2),
                            MarkdownEditor::make('description')
                        ]),
                    
                    Step::make('Media & Status')
                        ->icon('heroicon-o-photo') // Icon for media
                        ->description('Upload gambar dan atur status')
                        ->schema([
                            FileUpload::make('image')
                                ->disk('public')
                                ->directory('products'),
                            Checkbox::make('is_active'),
                            Checkbox::make('is_featured'),
                        ]),
                ])
                ->columnSpanFull()
                ->submitAction(
                    Action::make('save')
                        ->label('Save Product')
                        ->button()
                        ->color('primary')
                        ->submit('save')
                )
            ]);
    }
}