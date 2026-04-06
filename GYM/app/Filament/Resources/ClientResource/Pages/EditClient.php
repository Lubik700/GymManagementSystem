<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    // ✅ Remove ->body() to avoid Dom\HTMLDocument error
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Client updated successfully!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}