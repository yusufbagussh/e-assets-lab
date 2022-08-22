<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Item::with('location')->get();

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
        $validasi = $request->validate([
            'item_lokasi' => 'required',
            'item_nama' => 'required',
            'item_jumlah' => 'required',
            'item_kondisi' => 'required',
            'item_spesifikasi' => 'required',
            'item_status' => 'required',
            // 'item_gambar' => 'image|file|max:1024',
            'item_gambar' => 'required'

        ]);
        try {

            // $validasi['item_gambar'] = $request->file('item_gambar')->store('item-image');

            $item = Item::create($validasi);

            $data = Item::where('item_id', '=', $item->item_id)->get();

            if ($data) {
                return ApiFormatter::createApi(200, 'Success', $data);
            } else {
                return ApiFormatter::createApi(400, 'Failed');
            }
        } catch (Exception $e) {
            return ApiFormatter::createApi(400, 'Failed', $e->getMessage());
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
        $data = Item::where('item_id', '=', $id)->get();

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
                'item_lokasi' => 'required',
                'item_nama' => 'required',
                'item_lokasi' => 'required',
                'item_jumlah' => 'required',
                'item_gambar' => 'required',
                'item_spesifikasi' => 'required',
                'item_kondisi' => 'required',
                'item_status' => 'required',
            ]);
            if ($request->file('item_gambar')) {
                if ($request->gambarLama) {
                    Storage::delete($request->gambarLama);
                }
                $validatedData['item_gambar'] = $request->file('item_gambar')->store('item-image');
            }

            $item = Item::findOrFail($id);

            $item->update([
                'item_lokasi' => $request->item_lokasi,
                'item_nama' => $request->item_nama,
                'item_lokasi' => $request->item_lokasi,
                'item_jumlah' => $request->item_jumlah,
                'item_gambar' => $request->item_gambar,
                'item_spesifikasi' => $request->item_spesifikasi,
                'item_kondisi' => $request->item_kondisi,
                'item_status' => $request->item_status,
            ]);

            $data = Item::where('item_id', '=', $item->item_id)->get();

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
        $item = Item::findOrFail($id);

        $data = $item->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
