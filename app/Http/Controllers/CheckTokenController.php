<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class CheckTokenController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = Token::where('token', $request->token)
            ->where('active', true)
            ->first();

        if (! $token) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid atau sudah tidak aktif.']);
        }

        return response()->json(['success' => true]);
    }
}
