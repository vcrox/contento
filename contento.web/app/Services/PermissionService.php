<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function List($q, $sortBy, $sortAsc, $pageNumber): LengthAwarePaginator
    {
        $permissions = Permission::when($q, function ($query) use ($q) {
            return $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            });
        })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC');
        return $permissions->paginate($pageNumber);
    }

    public function ExportList($q, $sortBy, $sortAsc): Collection
    {
        $permissions = Permission::when($q, function ($query) use ($q) {
            return $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            });
        })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')->get();
        return $permissions;
    }
}
