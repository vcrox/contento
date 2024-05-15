<?php

namespace App\Livewire;

use App\Exports\UsuariosExport;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class ListaUsuarios extends Component
{
    use WithPagination;

    public $nombre, $email, $usuario_id, $password, $repetir_password, $telefono;
    public $isModalOpen = false;
    public $isEdit = false;
    public $confirmDelete = false;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $pageNumber = 10;
    public $pageNumbers = ['10', '25', '50', '100'];
    public $clientes = [];
    public $roles = [];
    public $rol;
    public $cliente = null;
    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];
    public function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class . ",email," . $this->usuario_id],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
            'repetir_password' => 'nullable|same:password',
            'cliente' => 'nullable',
            'rol' => 'required',
            'telefono' => ['required', 'numeric']
        ];
    }
    public function mount()
    {
        // $this->clientes=Cliente::where('tipo_cliente','CLIENTE')->get();
        $this->roles = Role::all()->pluck("name");
    }
    public function render(UsuarioService $usuarioService)
    {

        $usuarios = $usuarioService->List($this->q, $this->sortBy, $this->sortAsc, $this->pageNumber);

        return view('livewire.lista-usuarios', compact(['usuarios']));
    }
    public function updatingQ()
    {
        $this->resetPage();
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
        $this->usuario_id = $id;
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
        $this->nombre = "";
        $this->email = "";
        $this->usuario_id = null;
        $this->cliente = null;
        $this->password = "";
        $this->repetir_password = "";
        $this->telefono = "";
        $this->rol = "";
    }
    public function create()
    {
        $this->resetForm();
        $this->openModalPopover();
        $this->isEdit = false;
    }
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $this->usuario_id = $id;
        $this->nombre = $usuario->name;
        $this->email = $usuario->email;
        $this->cliente = $usuario->cliente_id;
        $this->telefono = $usuario->telefono;
        $this->rol = $usuario->getRoleNames()->first();

        $this->openModalPopover();
        $this->isEdit = true;
    }
    public function delete()
    {
        User::find($this->usuario_id)->delete();
        $this->closeModalDelete();
        session()->flash('message', "Registro eliminado");
    }

    public function store(UsuarioService $usuarioService)
    {
        try {
            if ($this->usuario_id != null && $this->usuario_id != "") {
                $editRules = $this->rules();
                $editRules['password'] = ['nullable', Password::min(8)->letters()->mixedCase()->numbers()];
                $this->validate($editRules);
                $user = $usuarioService->Store($this->usuario_id, [
                    'name' => $this->nombre,
                    'email' => $this->email,
                    'telefono' => $this->telefono,
                    'cliente_id' => $this->cliente == "" ? null : $this->cliente
                ], $this->rol);
            } else {
                $this->validate($this->rules());
                $user = $usuarioService->Store($this->usuario_id, [
                    'name' => $this->nombre,
                    'email' => $this->email,
                    'telefono' => $this->telefono,
                    'password' => Hash::make($this->password),
                    'cliente_id' => $this->cliente == "" ? null : $this->cliente
                ], $this->rol);
            }
            session()->flash('message', $this->usuario_id != null ? 'Registro actualizado.' : 'Registro creado.');
            $this->closeModalPopover();
            $this->resetForm();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . $th->getTraceAsString());
            throw $th;
        }
    }
    public function export(UsuarioService $usuarioService)
    {
        return Excel::download(new UsuariosExport($this->q, $this->sortBy, $this->sortAsc, $usuarioService), 'usuarios.xlsx');
    }
}
