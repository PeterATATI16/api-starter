<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CrudService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model::all();
    }

    public function show($id)
    {
        return $this->model::find($id);
    }

    public function update(Request $request, $id)
    {
        $instance = $this->model::find($id);
        if (!$instance) {
            return null;
        }

        $data = $request->all();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $instance->update($data);
        return $instance;
    }

    public function destroy($id)
    {
        $instance = $this->model::find($id);
        if (!$instance) {
            return null;
        }

        $instance->delete();
        return $instance;
    }
}
