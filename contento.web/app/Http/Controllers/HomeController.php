<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = User::find(Auth::user()->id);
        $rol = $user->getRoleNames()->first();
        // switch ($rol) {
        //     case 'Administrador':
        //         return View('admindashboard');
        //         break;
        //     case 'Contador':
        //         return View('dashboard_contador');
        //         break;
        //     default:
        //         break;
        // }
        return View('dashboard');
    }
    public function paises(Request $request)
    {
        $data = Pais::select("descripcion")
            ->where('descripcion', 'LIKE', '%' . $request->get('query') . '%')
            ->pluck('descripcion');
        return response()->json($data);
    }
    public function municipios(Request $request)
    {
        $data = Municipio::select("descripcion")
            ->where('descripcion', 'LIKE', '%' . $request->get('query') . '%')
            ->pluck('descripcion');
        return response()->json($data);
    }
    public function estados(Request $request)
    {
        $data = Estado::select("descripcion")
            ->where('descripcion', 'LIKE', '%' . $request->get('query') . '%')
            ->pluck('descripcion');
        return response()->json($data);
    }
}
