<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
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

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getNavigationLabel(): string
    {
        return 'Gym Plans';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Plan Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Plan Name')
                        ->required()
                        ->placeholder('e.g. Monthly, Quarterly, Annual'),
                    Forms\Components\TextInput::make('price')
                        ->label('Price (NPR)')
                        ->numeric()
                        ->required()
                        ->placeholder('e.g. 2000'),
                    Forms\Components\Select::make('duration')
                        ->label('Duration')
                        ->options([
                            '1 Month'  => '1 Month',
                            '2 Months' => '2 Months',
                            '3 Months' => '3 Months',
                            '6 Months' => '6 Months',
                            '1 Year'   => '1 Year',
                            '2 Years'  => '2 Years',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->placeholder('What is included in this plan...')
                        ->nullable(),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price (NPR)')
                    ->money('NPR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index'  => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'view'   => Pages\ViewPlan::route('/{record}'),
            'edit'   => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}