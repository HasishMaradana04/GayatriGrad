<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostingResource\Pages;
use App\Models\JobPosting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class JobPostingResource extends AuthorizedResource
{
    protected static ?string $model = JobPosting::class;

    protected static ?string $permissionModule = 'jobs';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Alumni & Career';

    public static function getNavigationLabel(): string
    {
        return 'Jobs';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('company')->required()->maxLength(255),
            TextInput::make('location')->maxLength(255),
            Select::make('employment_type')
                ->required()
                ->options([
                    'full_time' => 'Full Time',
                    'part_time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                ])
                ->default('full_time'),
            DatePicker::make('expires_at'),
            TextInput::make('apply_url')->url()->maxLength(255),
            Toggle::make('is_active')->default(true),
            RichEditor::make('description')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('company')->searchable()->sortable(),
                TextColumn::make('location')->searchable(),
                TextColumn::make('employment_type')->badge(),
                TextColumn::make('expires_at')->date(),
            ])
            ->filters([
                SelectFilter::make('employment_type')->options([
                    'full_time' => 'Full Time',
                    'part_time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                ]),
                TernaryFilter::make('is_active'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
