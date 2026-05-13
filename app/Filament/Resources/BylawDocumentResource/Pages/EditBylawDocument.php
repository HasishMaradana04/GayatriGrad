<?php

namespace App\Filament\Resources\BylawDocumentResource\Pages;

use App\Filament\Resources\BylawDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBylawDocument extends EditRecord
{
    protected static string $resource = BylawDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
