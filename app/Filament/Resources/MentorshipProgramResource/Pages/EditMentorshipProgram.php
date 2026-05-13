<?php

namespace App\Filament\Resources\MentorshipProgramResource\Pages;

use App\Filament\Resources\MentorshipProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMentorshipProgram extends EditRecord
{
    protected static string $resource = MentorshipProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
