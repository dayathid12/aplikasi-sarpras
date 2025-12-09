<div class="flex items-center gap-2">
    <span>{{ $getRecord()->nama_pengemudi }}</span>

    <x-filament-tables::dropdown
        placement="bottom-end"
        class="flex-shrink-0"
    >
        <x-slot name="trigger">
            <x-filament::icon-button
                icon="heroicon-s-ellipsis-vertical"
                label="Actions"
                tooltip="Actions"
            />
        </x-slot>

        <x-filament::dropdown.list>
            {{ \Filament\Tables\Actions\DeleteAction::make('delete')
                ->record($getRecord())
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->extraAttributes([
                    'class' => 'filament-tables-actions-action-group-item',
                ])
                ->button()
            }}
        </x-filament::dropdown.list>
    </x-filament-tables::dropdown>
</div>
