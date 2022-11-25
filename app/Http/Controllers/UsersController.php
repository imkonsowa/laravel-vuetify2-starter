<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\AppDatatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    function index(): Response
    {
        return Inertia::render('Admin/Users');
    }

    function datatable(): LengthAwarePaginator
    {
        return AppDatatable::of(User::query());
    }
}
