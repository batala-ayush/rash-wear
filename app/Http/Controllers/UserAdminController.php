<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserAdminController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'users' => DB::table('users')->orderBy('created_at', 'desc')->get(['id', 'name', 'email', 'phone', 'role', 'status', 'created_at']),
        ]);
    }
}
