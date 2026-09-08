<?php

namespace App\Http\Controllers;

use App\Models\HakSuara;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckTokenController extends Controller
{
    public function check(Request $request): JsonResponse
    {
        $tokenInput = trim((string) $request->input('token'));

        if (empty($tokenInput)) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak boleh kosong',
            ]);
        }

        // Cek apakah token berasal dari DPT personal (Kartu Pemilih)
        $hakSuara = HakSuara::where('token', $tokenInput)->first();

        if ($hakSuara) {
            if ($hakSuara->token_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token ini sudah hangus (kedua pemilihan sudah diikuti).',
                ]);
            }

            $sisa = $hakSuara->remainingElections();

            if (empty($sisa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemilih ini sudah menyelesaikan kedua pemilihan.',
                ]);
            }

            $labelSisa = array_map(
                fn ($t) => $t === 'mpk' ? 'MPK' : 'OSIS',
                $sisa
            );

            return response()->json([
                'success' => true,
                'message' => 'Token personal valid.',
                'voter_name' => $hakSuara->nisn,
                'type' => 'personal',
                'sisa_pemilihan' => $sisa,
                'sisa_label' => implode(' & ', $labelSisa),
            ]);
        }

        // Cek apakah token berasal dari master Display Token
        $token = Token::where('token', $tokenInput)
            ->where('active', true)
            ->first();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah tidak aktif.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Display token valid',
            'type' => 'display',
        ]);
    }
}
