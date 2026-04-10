<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
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
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-wrench-screwdriver';
    }

    public static function getNavigationLabel(): string
    {
        return 'Equipment';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

 public static function getNavigationBadge(): ?string
{
    try {
        return (string) Equipment::count() ?: null;
    } catch (\Exception $e) {
        return null;
    }
}

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Equipment Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Equipment Name')
                        ->required()
                        ->placeholder('e.g. Treadmill'),
                    Forms\Components\Select::make('category')
                        ->label('Category')
                        ->options([
                            'Cardio'      => 'Cardio',
                            'Strength'    => 'Strength',
                            'Flexibility' => 'Flexibility',
                            'Free Weights'=> 'Free Weights',
                            'Machines'    => 'Machines',
                            'Other'       => 'Other',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('brand')
                        ->label('Brand')
                        ->placeholder('e.g. Life Fitness'),
                    Forms\Components\TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->default(1)
                        ->required(),
                    Forms\Components\Select::make('condition')
                        ->label('Condition')
                        ->options([
                            'excellent'   => 'Excellent',
                            'good'        => 'Good',
                            'fair'        => 'Fair',
                            'maintenance' => 'Under Maintenance',
                        ])
                        ->required()
                        ->default('good'),
                    Forms\Components\Toggle::make('is_available')
                        ->label('Available')
                        ->default(true),
                    Forms\Components\Textarea::make('description')
                        ->label('Description / Specifications')
                        ->placeholder('e.g. Max speed 20km/h, incline 0-15%, heart rate monitor...')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('image')
                        ->label('Equipment Image')
                        ->image()
                        ->disk('public')
                        ->directory('equipment-images')
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('image')
    ->label('Photo')
    ->formatStateUsing(fn ($state) => $state ? '✓ Uploaded' : 'No photo'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Brand'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'excellent'   => 'success',
                        'good'        => 'info',
                        'fair'        => 'warning',
                        'maintenance' => 'danger',
                        default       => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Cardio'       => 'Cardio',
                        'Strength'     => 'Strength',
                        'Flexibility'  => 'Flexibility',
                        'Free Weights' => 'Free Weights',
                        'Machines'     => 'Machines',
                        'Other'        => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('condition')
                    ->options([
                        'excellent'   => 'Excellent',
                        'good'        => 'Good',
                        'fair'        => 'Fair',
                        'maintenance' => 'Under Maintenance',
                    ]),
            ])
            // ✅ Correct — already imported at top
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
            'index'  => Pages\ListEquipments::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'view'   => Pages\ViewEquipment::route('/{record}'),
            'edit'   => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}