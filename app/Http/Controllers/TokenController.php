<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = Token::orderBy('id', 'desc')->get();
        $totalTokens = Token::count();
        $activeTokens = Token::where('active', true)->count();

        return view('tokens.index', compact('tokens', 'totalTokens', 'activeTokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|max:10|unique:tokens,token',
        ]);

        Token::create([
            'token' => $request->token,
            'active' => true,
        ]);

        return redirect()->route('tokens.index')->with('success', 'Token berhasil ditambahkan!');
    }

    public function update(Request $request, Token $token)
    {
        $request->validate([
            'active' => 'boolean',
        ]);

        $token->update(['active' => $request->boolean('active')]);

        return redirect()->route('tokens.index')->with('success', 'Status token berhasil diupdate!');
    }

    public function destroy(Token $token)
    {
        $token->delete();

        return redirect()->route('tokens.index')->with('success', 'Token berhasil dihapus!');
    }
}
