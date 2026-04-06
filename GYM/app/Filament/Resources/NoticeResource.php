<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoticeResource\Pages;
use App\Models\Notice;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;

class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-megaphone';
    }

    public static function getNavigationLabel(): string
    {
        return 'Notices';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Notice::where('is_active', true)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Notice Details')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Notice Title')
                        ->required()
                        ->placeholder('e.g. Gym Maintenance Notice'),
                    Forms\Components\Select::make('type')
                        ->label('Type')
                        ->options([
                            'important' => 'Important',
                            'event'     => 'Event',
                            'general'   => 'General',
                        ])
                        ->required()
                        ->default('general'),
                    Forms\Components\Textarea::make('content')
                        ->label('Content')
                        ->required()
                        ->rows(4)
                        ->placeholder('Write the notice content here...')
                        ->columnSpanFull(),
                    Forms\Components\DateTimePicker::make('posted_at')
                        ->label('Posted At')
                        ->default(now())
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'important' => 'danger',
                        'event'     => 'success',
                        'general'   => 'info',
                        default     => 'gray',
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('posted_at')
                    ->label('Posted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('posted_at', 'desc')
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                TableDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNotices::route('/'),
            'create' => Pages\CreateNotice::route('/create'),
            'view'   => Pages\ViewNotice::route('/{record}'),
            'edit'   => Pages\EditNotice::route('/{record}/edit'),
        ];
    }
}