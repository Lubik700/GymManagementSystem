<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActiveClientResource\Pages;
use App\Models\Client;
use App\Models\Subscription;
use BackedEnum;
use Filament\Forms;
use Filament\Panel;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action as TableAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Carbon\Carbon;

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

    // ✅ Auto-expire clients more than 5 days past end date
    public static function boot()
    {
        parent::boot();
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
        // ✅ Auto-expire clients who are 5+ days past end date
        self::autoExpireClients();

        return $table
            ->query(
                Client::query()
                    ->where('clients.status', 'active')
                    ->whereHas('subscriptions', function ($q) {
                        $q->where('status', 'active');
                    })
                    // ✅ Sort by remaining days (soonest expiry first)
                    ->leftJoin('subscriptions', function ($join) {
                        $join->on('subscriptions.client_id', '=', 'clients.id')
                             ->where('subscriptions.status', 'active');
                    })
                    ->orderBy('subscriptions.end_date', 'asc')
                    ->select('clients.*')
            )
            ->columns([
                Tables\Columns\TextColumn::make('profile_picture')
                    ->label('Photo')
                    ->formatStateUsing(fn ($state) => $state ? '✓' : '-'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact'),

                // ✅ Show subscription end date
                Tables\Columns\TextColumn::make('subscriptions.end_date')
                    ->label('Expires On')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return Carbon::parse($state)->format('d M Y');
                    }),

                // ✅ Remaining days with color coding
                Tables\Columns\TextColumn::make('remaining_days')
                    ->label('Days Left')
                    ->getStateUsing(function (Client $record) {
                        $sub = $record->subscriptions()
                            ->where('status', 'active')
                            ->latest()
                            ->first();
                        if (!$sub) return null;
                        $days = (int) Carbon::today()->diffInDays(
                            Carbon::parse($sub->end_date), false
                        );
                        return $days;
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state === null) return 'N/A';
                        if ($state < 0) return abs($state) . ' days overdue';
                        if ($state === 0) return 'Expires today!';
                        return $state . ' days left';
                    })
                    ->color(function ($state) {
                        if ($state === null) return 'gray';
                        if ($state <= 0)  return 'danger';  // overdue
                        if ($state <= 5)  return 'danger';  // red — less than 5
                        if ($state <= 10) return 'warning'; // yellow — less than 10
                        return 'success';
                    })
                    ->badge()
                    ->sortable(false),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color('success'),

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

                        // Mark subscription as expired
                        $record->subscriptions()
                            ->where('status', 'active')
                            ->update(['status' => 'expired']);

                        Notification::make()
                            ->title('Client marked as expired.')
                            ->warning()
                            ->send();
                    }),

                ViewAction::make(),
            ]);
    }

    // ✅ Auto-expire clients 5+ days past subscription end date
    protected static function autoExpireClients(): void
    {
        $gracePeriod = Carbon::today()->subDays(5); // 5 days grace period

        // Find active clients whose subscription ended more than 5 days ago
        $expiredClients = Client::where('clients.status', 'active')
            ->whereHas('subscriptions', function ($q) use ($gracePeriod) {
                $q->where('status', 'active')
                  ->where('end_date', '<', $gracePeriod);
            })
            ->get();

        foreach ($expiredClients as $client) {
            // Change client status to 'client'
            $client->update(['status' => 'client']);

            // Mark their subscriptions as expired
            $client->subscriptions()
                ->where('status', 'active')
                ->update(['status' => 'expired']);
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActiveClients::route('/'),
            'view'  => Pages\ViewActiveClient::route('/{record}'),
        ];
    }
}