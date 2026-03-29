<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Grid;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Left column (2/3 width)
                Group::make()
                    ->schema([
                        Section::make("Post Details")
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->minLength(5) // Title minimal 5 karakter
                                            ->maxLength(255)
                                            ->validationMessages([
                                                'required' => 'The post title is required.',
                                                'min' => 'The title must be at least 5 characters.', // Custom message
                                                'max' => 'The title cannot exceed 255 characters.',
                                            ])
                                            ->columnSpan(1),
                                        
                                        TextInput::make('slug')
                                            ->required()
                                            ->minLength(3) // Slug minimal 3 karakter
                                            ->unique('posts', 'slug', ignoreRecord: true)
                                            ->validationMessages([
                                                'required' => 'The slug is required.',
                                                'min' => 'The slug must be at least 3 characters.',
                                                'unique' => 'This slug has already been taken. Please use a different slug.', // Custom message
                                            ])
                                            ->columnSpan(1),
                                        
                                        Select::make("category_id")
                                            ->relationship('category', 'name')
                                            ->required() // Category wajib dipilih
                                            ->validationMessages([
                                                'required' => 'Please select a category for this post.',
                                            ])
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(1),
                                        
                                        ColorPicker::make("color")
                                            ->label("Post Color")
                                            ->columnSpan(1),
                                    ]),
                                
                                MarkdownEditor::make("content")
                                    ->label("Post Content")
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'link',
                                        'heading',
                                        'bulletList',
                                        'orderedList',
                                        'codeBlock',
                                        'blockquote',
                                    ])
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(2),
                
                // Right column (1/3 width)
                Group::make()
                    ->schema([
                        Section::make("Image Upload")
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make("image")
                                    ->required() // Image wajib diupload
                                    ->disk("public")
                                    ->directory("posts")
                                    ->image()
                                    ->imagePreviewHeight(200)
                                    ->maxSize(2048)
                                    ->validationMessages([
                                        'required' => 'Please upload a post image.',
                                        'image' => 'The file must be an image.',
                                        'max' => 'The image size cannot exceed 2MB.',
                                    ]),
                            ])
                            ->collapsible(),
                        
                        Section::make("Meta Information")
                            ->icon('heroicon-o-tag')
                            ->schema([
                                TagsInput::make("tags")
                                    ->placeholder("Add tags")
                                    ->suggestions([
                                        'Laravel',
                                        'PHP',
                                        'JavaScript',
                                        'Tailwind',
                                        'Livewire',
                                        'Filament',
                                    ]),
                                
                                Checkbox::make("published")
                                    ->label("Published")
                                    ->default(false),
                                
                                DateTimePicker::make("published_at")
                                    ->label("Publish Date")
                                    ->seconds(false),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}