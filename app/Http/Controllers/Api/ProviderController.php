<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProviderController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // User fields
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',

            // Provider fields
            'address'     => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city'        => 'required|string|max:100',
            'phone'       => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $validator->validated();

        // Créer l'utilisateur
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Créer le provider lié
        $user->provider()->create([
            'address'     => $data['address'],
            'postal_code' => $data['postal_code'],
            'city'        => $data['city'],
            'phone'       => $data['phone'] ?? null,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'Votre demande a bien été envoyée. En attente de validation.',
            'user'    => $user,
            'token'   => $user->createToken('api_token')->plainTextToken,
            'status'  => 'pending',
        ], 201);
    }
}
