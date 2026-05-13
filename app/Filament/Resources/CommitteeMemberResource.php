<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommitteeMemberResource\Pages;
use App\Models\Chapter;
use App\Models\CommitteeMember;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommitteeMemberResource extends AuthorizedResource
{
    protected static ?string $model = CommitteeMember::class;

    protected static ?string $permissionModule = 'committee-members';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Content Management';

    public static function getNavigationLabel(): string
    {
        return 'Committee Members';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('designation')->required()->maxLength(255),
            Select::make('committee_type')
                ->required()
                ->options([
                    'governing_body' => 'Governing Body',
                    'executive_committee' => 'Executive Committee',
                    'office_bearer' => 'Office Bearer',
                ])
                ->default('executive_committee'),
            Select::make('chapter_id')
                ->label('Chapter')
                ->options(fn (): array => Chapter::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload(),
            DatePicker::make('tenure_start'),
            DatePicker::make('tenure_end'),
            TextInput::make('email')->email()->maxLength(255),
            TextInput::make('phone')->maxLength(255),
            FileUpload::make('photo_path')
                ->directory('committee')
                ->disk('public')
                ->image()
                ->imageEditor(),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
            Textarea::make('bio')->rows(4)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('photo_path')->disk('public')->circular()->label('Photo'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('designation')->searchable(),
                TextColumn::make('committee_type')->badge(),
                TextColumn::make('chapter.name')->label('Chapter'),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->filters([
                SelectFilter::make('committee_type')
                    ->options([
                        'governing_body' => 'Governing Body',
                        'executive_committee' => 'Executive Committee',
                        'office_bearer' => 'Office Bearer',
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
            'index' => Pages\ListCommitteeMembers::route('/'),
            'create' => Pages\CreateCommitteeMember::route('/create'),
            'edit' => Pages\EditCommitteeMember::route('/{record}/edit'),
        ];
    }
}
