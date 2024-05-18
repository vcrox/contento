<?php

namespace App\Livewire;

use App\Exports\RoleExport;
use App\Services\RoleService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ListaRoles extends Component
{
    use WithPagination;
    public $name, $role_id;
    public $isModalOpen = false;
    public $isEdit = false;
    public $confirmDelete = false;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $pageNumber = 10;
    public $pageNumbers = ['10', '25', '50', '100'];
    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,id'],
        ];
    }
    public function mount()
    {
    }
    public function render(RoleService $roleService)
    {
        $roles = $roleService->List($this->q, $this->sortBy, $this->sortAsc, $this->pageNumber);
        return view('livewire.lista-roles', compact(['roles']));
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
        $this->role_id = $id;
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
        $this->role_id = null;
    }
    public function create()
    {
        $this->resetForm();
        $this->openModalPopover();
        $this->isEdit = false;
    }
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $id;
        $this->name = $role->name;
        $this->openModalPopover();
        $this->isEdit = true;
    }
    public function delete()
    {
        Role::find($this->role_id)->delete();
        $this->closeModalDelete();
        session()->flash('message', "Registro eliminado");
    }

    public function store()
    {
        $this->validate($this->rules());
        try {
            $registro = Role::updateOrCreate(['id' => $this->role_id], [
                'name' => Str::upper($this->name),
            ]);
            session()->flash('message', $this->role_id != null ? 'Registro actualizado.' : 'Registro creado.');
            $this->closeModalPopover();
            $this->resetForm();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . $th->getTraceAsString());
            throw $th;
        }
    }
    public function export(RoleService $roleService)
    {
        return Excel::download(new RoleExport($this->q, $this->sortBy, $this->sortAsc, $roleService), 'roles.xlsx');
    }
}
