<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function index(): View
    {
        return View("permissions.index");
    }
}
