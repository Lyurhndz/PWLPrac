<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Schemas\Components\Image;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Checkbox;
use Symfony\Component\Finder\Iterator\SortableIterator;
use Filament\Actions\Action;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
            TextColumn::make('id')
                ->label('ID')
                ->Sortable()
                ->toggleable(isToggledHiddenByDefault:true),
            TextColumn::make('title')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('slug')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('category.name')
                ->sortable()
                ->searchable()
                ->toggleable(),            
            ColorColumn::make('color'),
            ImageColumn::make('image')
                ->disk('public'),
            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->sortable(),
            TextColumn::make('tags.name')
                ->label('tags')
                ->toggleable(isToggledHiddenByDefault:false),
            IconColumn::make('published')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),
        
            ])->defaultSort('created_at', 'asc')
            ->filters([
                Filter::make('created_at')
                    ->label('Creation Date')
                        ->schema([
                            DatePicker::make('created_at')
                                ->label('Select Date: '),
                    ]),
                SelectFilter::make('category_id')
                    ->label('Select Category')
                    ->relationship('category', 'name')
                    ->preload(),
                    // ->query(function ($query, $data) {
                    //     return $query
                    //     ->when(
                    //         $data['created_at'],
                    //         fn ($query, $date) => $query->whereDate('created_at', $date)
                    //         );
                    //     })
            ])
            ->recordActions([
                ReplicateAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('status')
                ->label('Status Change')
                ->icon('heroicon-o-check-circle')
                ->schema([
                    Checkbox::make('published')
                    ->default(fn($record): bool => $record->published)
                ])
                ->action(function($record, $data){
                    $record->update(['published' => $data['published']]);
                })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
