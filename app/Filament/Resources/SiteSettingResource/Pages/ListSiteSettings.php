<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Resources\Pages\ListRecords;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    public function mount(): void
    {
        $record = SiteSetting::query()->first();

        if (! $record) {
            $record = SiteSetting::query()->create();
        }

        $this->redirect(
            SiteSettingResource::getUrl('edit', ['record' => $record])
        );
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
