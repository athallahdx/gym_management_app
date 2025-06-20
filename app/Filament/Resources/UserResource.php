<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\GymVisitRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\MembershipHistoriesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\TransactionRelationManager;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Services\FileNaming;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context) => $context === 'create')
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->label('Password'),

                TextInput::make('phone')->tel(),

                Select::make('role')
                    ->required()
                    ->options([
                        'member' => 'Member',
                        'trainer' => 'Trainer',
                        'admin' => 'Admin',
                    ])
                    ->default('member'),

                Select::make('membership_registered')
                    ->options([
                        'unregistered' => 'Unregistered',
                        'registered' => 'Registered',
                    ])
                    ->default('unregistered'),

                Select::make('membership_status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('inactive'),

                DateTimePicker::make('membership_end_date'),

                Textarea::make('profile_bio'),

                FileUpload::make('profile_image')
                    ->label('Foto Profil')
                    ->image()
                    ->imageEditor()
                    ->previewable(true)
                    ->avatar()
                    ->directory('user_profile')
                    ->imagePreviewHeight('150')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $component) {
                        $extension = $file->getClientOriginalExtension();

                        $record = $component->getLivewire()->getRecord();
                        $id = $record?->id ?? -1;

                        return FileNaming::generateUserProfileName($id, $extension);
                    })
                ->default('default-user_profile.png')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_image')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(40)
                    ->width(40),

                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')->searchable()->sortable(),

                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'member' => 'Member',
                        'trainer' => 'Trainer',
                        default => ucfirst($state),
                    })
                ,

                TextColumn::make('membership_registered')
                    ->label('Status Pendaftaran')
                    ->badge()
                    ->colors([
                        'success' => 'registered',
                        'danger' => 'unregistered',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'registered' => 'Terdaftar',
                        'unregistered' => 'Tidak Terdaftar',
                        default => ucfirst($state),
                    }),

                TextColumn::make('membership_status')
                    ->label('Status Membership')
                    ->badge()
                    ->colors([
                            'success' => 'active',
                            'danger' => 'inactive',
                        ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                        default => ucfirst($state),
                    }),

                TextColumn::make('membership_end_date')
                    ->label('Tanggal Berakhir Membership')
                    ->dateTime(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->alignCenter()
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MembershipHistoriesRelationManager::class,
            GymVisitRelationManager::class,
            TransactionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
