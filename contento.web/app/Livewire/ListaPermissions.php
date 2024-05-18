<?php

namespace App\Livewire;

use App\Exports\PermissionExport;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListaPermissions extends Component
{
    use WithPagination;
    public $name, $permission_id;
    public $isModalOpen = false;
    public $isEdit = false;
    public $confirmDelete = false;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $pageNumber = 10;
    public $pageNumbers = ['10', '25', '50', '100'];
    public $roles;
    public $rolesSelected = [];
    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,id'],
            'roles' => ['array'],
        ];
    }
    public function mount()
    {
    }
    public function render(PermissionService $permissionService)
    {
        $this->roles = Role::pluck('name', 'id');
        $permissions = $permissionService->List($this->q, $this->sortBy, $this->sortAsc, $this->pageNumber);
        return view('livewire.lista-permissions', compact(['permissions']));
    }
    public function updatingQ()
    {
        $this->resetPage();
    }
    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }
    public function sortBy($field)
    {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }
    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }
    public function openModalDelete($id)
    {
        $this->confirmDelete = true;
        $this->permission_id = $id;
    }
    public function closeModalDelete()
    {
        $this->confirmDelete = false;
    }
    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }
    private function resetForm()
    {
        $this->name = "";
        $this->permission_id = null;
    }
    public function create()
    {
        $this->resetForm();
        $this->openModalPopover();
        $this->isEdit = false;
    }
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->rolesSelected = $permission->roles->pluck('id')->toArray();
        // $permission->syncRoles($this->rolesSelected);
        $this->permission_id = $id;
        $this->name = $permission->name;
        $this->openModalPopover();
        $this->isEdit = true;
    }
    public function delete()
    {
        Permission::find($this->permission_id)->delete();
        $this->closeModalDelete();
        session()->flash('message', "Registro eliminado");
    }

    public function store()
    {
        $this->validate($this->rules());
        try {
            $registro = Permission::updateOrCreate(['id' => $this->permission_id], [
                'name' => Str::upper($this->name),
            ]);
            $registro->syncRoles($this->rolesSelected);
            session()->flash('message', $this->permission_id != null ? 'Registro actualizado.' : 'Registro creado.');
            $this->closeModalPopover();
            $this->resetForm();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . $th->getTraceAsString());
            throw $th;
        }
    }
    public function export(PermissionService $permissionService)
    {
        return Excel::download(new PermissionExport($this->q, $this->sortBy, $this->sortAsc, $permissionService), 'permissions.xlsx');
    }
}
