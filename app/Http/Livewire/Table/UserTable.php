<?php

namespace App\http\Livewire\Table;

use App\Models\Thrubus\Pool;
use App\Models\Thrubus\Mitra;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name")
                ->searchable()
                ->sortable(),
            Column::make("Email", "email")
                ->searchable()
                ->sortable(),
            Column::make("No. Handphone", "phone")
                ->searchable()
                ->sortable(),
            Column::make("Alamat", "location")
                ->searchable()
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Mitra", "mitra_id")
                // ->searchable()
                // ->sortable()
                ->collapseOnMobile()
                ->format([$this, 'generateMitraHtml']),
            Column::make("Pool", "pool_id")
                // ->searchable()
                // ->sortable()
                ->collapseOnMobile()
                ->format([$this, 'generatePoolHtml']),
            // Column::make("Created at", "created_at")
            //     ->sortable(),
            // Column::make("Updated at", "updated_at")
            //     ->sortable(),
            Column::make("Action", 'id')
                ->format([$this, 'generateActionHtml']),
        ];
    }

    protected $listeners = ['deleteUser'];

    public function deleteUser($userId)
    {

        try {
            DB::beginTransaction();
            User::destroy($userId);
            DB::commit();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $this->emit('refreshDataTable');
    }

    public function generateMitraHtml($value, $column, $row)
    {
        $mitra = Mitra::where('idmitra', $column->mitra_id)->first();
        return $mitra->nama_mitra ?? '-';
    }

    public function generatePoolHtml($value, $column, $row)
    {
        $pool = Pool::where('idpool', $column->pool_id)->first();
        return $pool->nama ?? '-';
    }

    public function generateActionHtml($value, $column, $row)
    {
        $html = '';
        $html .= '<a class="mx-3" href="' . route('user.edit', ['id' => $column->id]) . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        if ($column->id != 1) {
            $html .= '<a href="_blank" class="mx-0 delete-user" data-user-id="' . $column->id . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
        }
        return Str::of($html)->toHtmlString();
    }
}
