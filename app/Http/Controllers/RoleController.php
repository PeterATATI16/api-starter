<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CrudService;
use App\Models\Role;

class RoleController extends Controller
{
    protected $crudService;

    public function __construct()
    {
        $this->crudService = new CrudService(new Role);
    }

    public function index()
    {
        $roles = $this->crudService->index();
        return response()->json($roles, 200);
    }
    public function store(Request $request)
    {
        $role = $this->crudService->store($request);
        return response()->json($role, 201);
    }
    public function show(string $id)
    {
        $role = $this->crudService->show($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }
    public function update(Request $request, string $id)
    {
        $role = $this->crudService->update($request, $id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    public function destroy(string $id)
    {
        $role = $this->crudService->destroy($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function toggleActive(string $id)
    {
        $role = $this->crudService->toggleAttribute($id, 'is_active');
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $statusMessage = $role->is_active ? 'Role activated' : 'Role desactivated';

        return response()->json([
            'role' => $role,
            'message' => $statusMessage
        ]);
    }

}
