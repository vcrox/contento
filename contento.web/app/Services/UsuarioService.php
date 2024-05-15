<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsuarioService
{
    public function List($q, $sortBy, $sortAsc, $pageNumber): LengthAwarePaginator
    {
        $usuarios = User::when($q, function ($query) use ($q) {
                return $query->where(function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        // ->orWhereHas('cliente', function (Builder $query) use ($q) {
                        //     $query->where('razon_social', 'like', '%' . $q . '%');
                        // })
                        ->orWhereHas('roles', function (Builder $query) use ($q) {
                            $query->where('name', 'like', '%' . $q . '%');
                        });
                });
            })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC');
        return $usuarios->paginate($pageNumber);
    }
    public function ExportList($q, $sortBy, $sortAsc): Collection
    {
        $usuarios = User::when($q, function ($query) use ($q) {
                return $query->where(function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        // ->orWhereHas('cliente', function (Builder $query) use ($q) {
                        //     $query->where('razon_social', 'like', '%' . $q . '%');
                        // })
                        ->orWhereHas('roles', function (Builder $query) use ($q) {
                            $query->where('name', 'like', '%' . $q . '%');
                        });
                });
            })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')->get();
        return $usuarios;
    }

    public function Store($id, array $data,$rol): User
    {
        $user = null;
        try {
            DB::beginTransaction();
            $user = User::updateOrCreate(['id' => $id], $data);
            $user->syncRoles([$rol]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return $user;
    }
}
