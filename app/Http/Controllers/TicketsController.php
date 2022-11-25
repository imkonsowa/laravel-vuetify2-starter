<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Support\AppDatatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class TicketsController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Users/Tickets');
    }

    public function adminIndex(): \Inertia\Response
    {
        return Inertia::render('Admin/Tickets');
    }

    public function datatable(): LengthAwarePaginator
    {
        return AppDatatable::of(Ticket::query()->where('created_by', Auth::id()));
    }

    public function adminDatatable(): LengthAwarePaginator
    {
        return AppDatatable::of(Ticket::query());
    }

    public function create(Request $request): JsonResponse
    {
        Ticket::query()
            ->create(
                array_merge($request->only(['title', 'description']), [
                    'created_by' => Auth::id()
                ])
            );

        return Response::json([
            'success' => true,
            'message' => 'Ticket created successfully'
        ]);
    }
}
