<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function show(): JsonResponse
    {
        $settings = DB::table('store_settings')->pluck('value', 'key')->map(fn ($value) => json_decode($value, true));
        return response()->json(['settings' => $settings]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'maintenance' => ['required', 'boolean'],
            'disableCheckout' => ['required', 'boolean'],
            'contactEmail' => ['required', 'email'],
            'contactPhone' => ['required', 'string', 'max:30'],
            'whatsappLink' => ['required', 'url'],
        ]);
        foreach ($data as $key => $value) {
            DB::table('store_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => json_encode($value), 'updated_at' => now(), 'created_at' => now()]
            );
        }
        return response()->json(['settings' => $data]);
    }
}
