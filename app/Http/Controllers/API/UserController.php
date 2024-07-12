<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CrudService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $crudService;

    public function __construct()
    {
        $this->crudService = new CrudService(new User());
    }

    public function index()
    {
        $users = $this->crudService->index();
        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = $this->crudService->show($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = $this->crudService->show($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $updatedUser = $this->crudService->update($request, $id);
        return response()->json($updatedUser, 200);
    }

    public function destroy($id)
    {
        $user = $this->crudService->show($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->crudService->destroy($id);
        return response()->json(['message' => 'User deleted'], 200);
    }
}
