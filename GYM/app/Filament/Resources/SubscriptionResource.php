<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use App\Models\Client;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;


class SubscriptionResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
{
    return false; // ✅ Hides from sidebar
}
    public static function calculateEndDate(string $startDate, string $duration): string
{
    $date = \Carbon\Carbon::parse($startDate);

    return match ($duration) {
        '1 Month'  => $date->addMonth()->toDateString(),
        '2 Months' => $date->addMonths(2)->toDateString(),
        '3 Months' => $date->addMonths(3)->toDateString(),
        '6 Months' => $date->addMonths(6)->toDateString(),
        '1 Year'   => $date->addYear()->toDateString(),
        '2 Years'  => $date->addYears(2)->toDateString(),
        default    => $date->addMonth()->toDateString(),
    };
}
    protected static ?string $model = Subscription::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-credit-card';
    }

    public static function getNavigationLabel(): string
    {
        return 'Subscriptions';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Members';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Subscription::count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscription Details')
    ->schema([
        Forms\Components\Select::make('client_id')
            ->label('Client')
            ->options(Client::whereIn('status', ['active', 'client'])
                ->pluck('name', 'id'))
            ->searchable()
            ->required(),
        Forms\Components\TextInput::make('plan_name')
            ->label('Plan Name')
            ->required()
            ->placeholder('e.g. Monthly, Quarterly, Annual'),
        Forms\Components\Select::make('duration')
            ->label('Duration')
            ->options([
                '1 Month'   => '1 Month',
                '2 Months'  => '2 Months',
                '3 Months'  => '3 Months',
                '6 Months'  => '6 Months',
                '1 Year'    => '1 Year',
                '2 Years'   => '2 Years',
            ])
            ->required()
            ->reactive() // ✅ triggers recalculation
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                $startDate = $get('start_date');
                if ($startDate && $state) {
                    $end = self::calculateEndDate($startDate, $state);
                    $set('end_date', $end);
                }
            }),
        Forms\Components\TextInput::make('amount')
            ->label('Amount (NPR)')
            ->numeric()
            ->required(),
        Forms\Components\DatePicker::make('start_date')
            ->required()
            ->default(now())
            ->reactive() // ✅ triggers recalculation
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                $duration = $get('duration');
                if ($state && $duration) {
                    $end = self::calculateEndDate($state, $duration);
                    $set('end_date', $end);
                }
            }),
        Forms\Components\DatePicker::make('end_date')
            ->label('End Date (Auto-calculated)')
            ->required()
            ->disabled(false), // ✅ shows calculated date but still editable
        Forms\Components\Select::make('status')
            ->options([
                'active'    => 'Active',
                'expired'   => 'Expired',
                'cancelled' => 'Cancelled',
            ])
            ->required()
            ->default('active'),
        Forms\Components\Textarea::make('notes')
            ->label('Notes')
            ->nullable(),
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
                Tables\Columns\TextColumn::make('plan_name')
                    ->label('Plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (NPR)')
                    ->money('NPR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'    => 'success',
                        'expired'   => 'warning',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active'    => 'Active',
                        'expired'   => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
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
            'index'  => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'view'   => Pages\ViewSubscription::route('/{record}'),
            'edit'   => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}