<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug'),
                Textarea::make('excerpt')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(PostStatus::class)
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->timezone(config('app.timezone')),
                FileUpload::make('cover_image_path')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('post-covers')
                    ->panelAspectRatio('16:9')
                    ->panelLayout('integrated')
                    ->imagePreviewHeight('128')
                    ->fetchFileInformation(false)
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        $ext = $file->getClientOriginalExtension() ?: 'jpg';
                        return (string) str()->uuid() . '.' . $ext;
                    }),
                TextInput::make('video_url')
                    ->label('YouTube URL')
                    ->maxLength(255)
                    ->helperText('Paste a full YouTube URL (youtu.be or youtube.com).')
                    ->columnSpanFull(),
                Select::make('author_id')
                    ->relationship('author', 'name'),
                TextInput::make('meta_title'),
                TextInput::make('meta_description'),
            ]);
    }
}
