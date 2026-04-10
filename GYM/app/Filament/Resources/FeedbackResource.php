<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-chat-bubble-left-right';
    }

    public static function getNavigationLabel(): string
    {
        return 'Feedback';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) Feedback::count() ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Feedback Details')
                ->schema([
                    Forms\Components\TextInput::make('client.name')
                        ->label('Client')
                        ->disabled(),
                    Forms\Components\TextInput::make('title')
                        ->disabled(),
                    Forms\Components\TextInput::make('category')
                        ->disabled(),
                    Forms\Components\TextInput::make('rating')
                        ->disabled(),
                    Forms\Components\Textarea::make('message')
                        ->disabled()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('suggestion')
                        ->disabled()
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'gym_facility' => 'Gym Facility',
                        'equipment'    => 'Equipment',
                        'staff'        => 'Staff',
                        'workout_area' => 'Workout Area',
                        'classes'      => 'Classes',
                        default        => 'Other',
                    }),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state)),
                Tables\Columns\TextColumn::make('message')
                    ->limit(40)
                    ->label('Feedback'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'gym_facility' => 'Gym Facility',
                        'equipment'    => 'Equipment',
                        'staff'        => 'Staff',
                        'workout_area' => 'Workout Area',
                        'classes'      => 'Classes',
                        'other'        => 'Other',
                    ]),
            ])
            ->actions([
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
            'index' => Pages\ListFeedbacks::route('/'),
            'view'  => Pages\ViewFeedback::route('/{record}'),
        ];
    }
}