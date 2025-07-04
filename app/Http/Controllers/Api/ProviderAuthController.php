<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProviderAuthController extends Controller
{
    // 1. Inscription
    public function register(Request $request)
    {
        $data = $request->validate([
            'email'                 => 'required|email|unique:provider_accounts',
            'password'              => 'required|min:6|confirmed',
            'name'                  => 'required|string|max:100',
            'address'               => 'required|string',
            'postal_code'           => 'required|string',
            'city'                  => 'required|string',
            'phone'                 => 'nullable|string',
        ]);

        $account = ProviderAccount::create([
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $provider = Provider::create([
            'provider_account_id' => $account->id,
            'name'        => $data['name'],
            'address'     => $data['address'],
            'postal_code' => $data['postal_code'],
            'city'        => $data['city'],
            'phone'       => $data['phone'] ?? null,
        ]);

        return response()->json([
            'account' => $account,
            'provider' => $provider,
        ]);
    }

    // 2. Connexion
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $account = ProviderAccount::where('email', $data['email'])->first();

        if (! $account || ! Hash::check($data['password'], $account->password)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        return response()->json([
            'token' => $account->createToken('provider_api_token')->plainTextToken,
        ]);
    }

    // 3. Récupérer les infos du prestataire connecté
    public function me(Request $request)
    {
        $account = $request->user(); // via Sanctum
        return response()->json([
            'account'  => $account,
            'provider' => $account->provider, // relation hasOne
        ]);
    }

    // 4. Déconnexion
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnecté avec succès']);
    }
}
