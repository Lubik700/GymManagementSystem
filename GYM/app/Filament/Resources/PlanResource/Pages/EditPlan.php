<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPlan extends EditRecord
{
    protected static string $resource = PlanResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Plan updated successfully!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}