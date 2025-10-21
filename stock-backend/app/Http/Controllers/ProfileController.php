<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
        ]);


        // Empêcher la modification du mot de passe et du rôle ici
        $user->update($data);

        // Charger la relation correcte (role + permissions)
        $user->load('role.permissions');

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    public function show(Request $request)
    {
        $user = $request->user()->load('role.permissions');

        return response()->json([
            'user' => $user
        ]);
    }
}
