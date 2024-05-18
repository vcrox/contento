<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(): View
    {
        return View("roles.index");
    }
}
