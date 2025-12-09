<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Resources\PermissionResource;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index()
    {
        $permissions = Permission::all();
        return PermissionResource::collection($permissions);
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        $permission = Permission::create(['name' => $request->name]);
        if ($request->has('description')) {
            $permission->description = $request->description;
            $permission->save();
        }

        return new PermissionResource($permission);
    }

    /**
     * Display the specified permission.
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return new PermissionResource($permission);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return new PermissionResource($permission);
    }

    /**
     * Remove the specified permission.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
