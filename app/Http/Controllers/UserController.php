<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $paginate = $request->paginate;
        $sort = $request->sortBy;
        $type = $request->sortOrder;

        $data = User::query();

        $data->with('role');

        if ($search) {
            $data->whereHas('role', function ($value) use ($search) {
                $value->where('user_nama', 'LIKE', "%" . $search . "%")
                    ->orWhere('user_email', 'LIKE', "%" . $search . "%")
                    ->orWhere('role_nama', 'LIKE', "%" . $search . "%");
            });
        }

        if ($sort && in_array($sort, ['user_nama', 'user_email', 'role_nama'])) {
            $sortBy = $sort;
        } else {
            $sortBy = 'id';
        }

        if ($type && in_array($type, ['ASC', 'DESC'])) {
            $sortOrder = $type;
        } else {
            $sortOrder = 'DESC';
        }

        if ($sort == 'role_nama') {
            $data->orderBy(Role::select('role_nama')->whereColumn('role_id', 'users.user_role'), $sortOrder);
        } else {
            $data->orderBy($sortBy, $sortOrder);
        }

        if ($paginate == 'all') {
            $jml = count($data->get()->toArray());
            return ApiFormatter::createApi(200, 'Success',  $data->paginate($jml));
        } else if ($paginate) {
            return ApiFormatter::createApi(200, 'Success',  $data->paginate($paginate));
        } else {
            return ApiFormatter::createApi(200, 'Success', $data->paginate(10));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = [
            'user_nama.required' => 'nama user wajib diisi',
            'user_nama.between' => 'panjang karakter harus 8 - 100 karakter',
            'user_email.required' => 'email user wajib diisi',
            'user_email.email' => 'email tidak valid',
            'user_email.max' => 'email tidak lebih dari 100 karakter',
            'user_email.unique' => 'email sudah terdaftar',
            'password.required' => 'password user wajib diisi',
            'password.min' => 'password minimal 8 karakter',
            'password_confirmation.required' => 'konfirmasi password wajib diisi',
            'password_confirmation.same' => 'konfirmasi password tidak sesuai',
            'user_role.required' => 'role user wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'user_nama' => 'required|string|between:2,100',
            'user_email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            // 'password_confirmation' => 'required|same:password',
            'user_role' => 'required',
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $user = User::create([
            'user_nama' => $request->user_nama,
            'user_email' => $request->user_email,
            'password' => bcrypt($request->password),
            'user_role' => $request->user_role,
        ]);

        // $user = User::create(array_merge(
        //     $validator->validated(),
        //     ['password' => bcrypt($request->password)]
        // ));

        $data = User::where('id', '=', $user->id)->get();

        if ($data) {
            return ApiFormatter::createApi(201, 'User Created', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::where('id', '=', $id)->get();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->user_email === $user->user_email) {
            $rules = 'required|string|email|max:100';
        } else {
            $rules = 'required|string|email|unique:users|max:100';
        }

        $message = [
            'user_nama.required' => 'nama user wajib diisi',
            'user_nama.between' => 'panjang karakter harus 8 - 100 karakter',
            'user_email.required' => 'email user wajib diisi',
            'user_email.email' => 'email tidak valid',
            'user_email.max' => 'email tidak lebih dari 100 karakter',
            'user_email.unique' => 'email sudah terdaftar',
            'password.required' => 'password user wajib diisi',
            'password.min' => 'password minimal 8 karakter',
            'password_confirmation.required' => 'konfirmasi password wajib diisi',
            'password_confirmation.same' => 'konfirmasi password tidak sesuai',
            'user_role.required' => 'role user wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'user_nama' => 'required|string|between:2,100',
            'user_email' => $rules,
            'password' => 'required|string|min:8',
            // 'password_confirmation' => 'required|same:password',
            'user_role' => 'required',
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        // $user = User::create(array_merge(
        //     $validator->validated(),
        //     ['password' => bcrypt($request->password)]
        // ));


        if ($request->password != $user->password) {
            $password = bcrypt($request->password);
        } else {
            $password = $user->password;
        }

        $user->update([
            'user_nama' => $request->user_nama,
            'user_email' => $request->user_email,
            'password' => $password,
            'user_role' => $request->user_role,
        ]);

        $data = User::where('id', '=', $user->id)->get();

        if ($data) {
            return ApiFormatter::createApi(201, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $data = $user->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
