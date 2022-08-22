<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Location::all();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
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
        try {
            $request->validate([
                'lokasi_nama' => 'required',
                // 'lokasi_gambar' => 'required|image|file'
            ]);

            // $lokasi['lokasi_gambar'] = $request->file('lokasi_gambar')->store('location-image');

            $location = Location::create([
                'lokasi_nama' => $request->lokasi_nama,
                // 'lokasi_gambar' => $lokasi['lokasi_gambar'],
            ]);

            $data = Location::where('lokasi_id', '=', $location->lokasi_id)->get();

            if ($data) {
                return ApiFormatter::createApi(200, 'Success', $data);
            } else {
                return ApiFormatter::createApi(400, 'Failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'Failed', $error);
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
        $data = Location::where('lokasi_id', '=', $id)->get();

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
        try {
            $request->validate([
                'lokasi_nama' => 'required',
                // 'lokasi_gambar' => 'required',
            ]);

            // if ($request->file('lokasi_gambar')) {
            //     if ($request->gambarLama) {
            //         Storage::delete($request->gambarLama);
            //     }
            //     $validatedData['lokasi_gambar'] = $request->file('lokasi_gambar')->store('location-image');
            // }
            
            $input = $request->all();
            
            $location = Location::findOrFail($id);

            // $imageName = NULL;
            // if ($image = $request->file('lokasi_gambar')) {
            //     $destinationPath = 'location-image/';
            //     $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            //     $image->move($destinationPath, $imageName);
            //     $input['image'] = $imageName;
            //     unlink('location-image/' . $location->image);
            // }


            $location->update($input);

            $data = Location::where('lokasi_id', '=', $location->lokasi_id)->get();

            if ($data) {
                return ApiFormatter::createApi(200, 'Success', $data);
            } else {
                return ApiFormatter::createApi(400, 'Failed');
            }
        } catch (Exception $error) {
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
        $location = Location::findOrFail($id);

        $data = $location->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
