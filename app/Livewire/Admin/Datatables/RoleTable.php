<?php
 
namespace App\Livewire\Admin\Datatables;
 
use Rappasoft\LaravelLivewireTables\DataTableComponent;

use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Models\Role;

use Carbon\Carbon;
 
class RoleTable extends DataTableComponent

{

    protected $model = Role::class;
 
    public function configure(): void

    {

        $this->setPrimaryKey('id');

    }
 
    public function columns(): array

    {

        return [

            Column::make('Id', 'id')

                ->sortable(),
 
            Column::make('Nombre', 'name')

                ->sortable(),
 
            Column::make('Fecha', 'created_at') // <- nombre correcto

                ->sortable()

                ->format(function ($value, $row) {

                    if (!$row->created_at) return '-';
 
                    // Si ya es Carbon, formatea; si es string, parsea y formatea

                    $dt = $row->created_at instanceof \Carbon\Carbon

                        ? $row->created_at

                        : Carbon::parse($row->created_at);
 
                    return $dt->format('d/m/Y');

                }),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.roles.actions', 
                    ['role' => $row]);

                }),
        ];
    }

}

 