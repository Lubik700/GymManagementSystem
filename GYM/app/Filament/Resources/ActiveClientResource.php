<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActiveClientResource\Pages;
use App\Models\Client;
use App\Models\Subscription;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action as TableAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Panel;

class ActiveClientResource extends Resource
{
    protected static ?string $model = Client::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-user-circle';
    }

    public static function getNavigationLabel(): string
    {
        return 'Active Clients';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Members';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getSlug(?Panel $panel = null): string
{
    return 'active-clients';
}

    public static function getNavigationBadge(): ?string
    {
        return (string) Client::where('status', 'active')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Personal Information')
                ->schema([
                    Forms\Components\TextInput::make('name')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('contact')->disabled(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'client'   => 'Client',
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ])->disabled(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Client::query()->where('status', 'active'))
            ->columns([
                Tables\Columns\TextColumn::make('profile_picture')
                    ->label('Photo')
                    ->formatStateUsing(fn ($state) => $state ? '✓ Uploaded' : 'No photo'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        default  => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Member Since')
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                TableAction::make('deactivate')
                    ->label('Mark Expired')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Client $record): void {
                        $record->update(['status' => 'client']);

                        Notification::make()
                            ->title('Client marked as expired!')
                            ->warning()
                            ->send();
                    }),

                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActiveClients::route('/'),
            'view'  => Pages\ViewActiveClient::route('/{record}'),
        ];
    }
}