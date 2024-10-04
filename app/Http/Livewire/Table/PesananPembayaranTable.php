<?php

namespace App\Http\Livewire\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PesananPembayaran;

class PesananPembayaranTable extends DataTableComponent
{
    protected $model = PesananPembayaran::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Header id", "header_id")
                ->sortable(),
            Column::make("Iditem status pembayaran", "iditem_status_pembayaran")
                ->sortable(),
            Column::make("Iditem methode pembayaran", "iditem_methode_pembayaran")
                ->sortable(),
            Column::make("Nominal", "nominal")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            Column::make("Created by", "created_by")
                ->sortable(),
            Column::make("Keterangan", "keterangan")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
