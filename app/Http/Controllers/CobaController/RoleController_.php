<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table roles
        $roles = Role::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Role',
            'data'    => $roles
        ], 200);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //find role by ID
        $role = Role::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Role',
            'data'    => $role
        ], 200);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'role_nama' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $role = Role::create([
            'role_nama' => $request->role_nama,
        ]);

        //success save to database
        if ($role) {

            return response()->json([
                'success' => true,
                'message' => 'Role Created',
                'data'    => $role
            ], 201);
        }

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Role Failed to Save',
        ], 409);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $role
     * @return void
     */
    public function update(Request $request, Role $role)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'role_nama'   => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find role by ID
        $role = Role::findOrFail($role->role_id);

        if ($role) {

            //update role
            $role->update([
                'role_nama'     => $request->role_nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data'    => $role
            ], 200);
        }

        //data role not found
        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find role by ID
        $role = Role::findOrfail($id);

        if ($role) {

            //delete role
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role Deleted',
            ], 200);
        }

        //data role not found
        return response()->json([
            'success' => false,
            'message' => 'Role Not Found',
        ], 404);
    }
}
