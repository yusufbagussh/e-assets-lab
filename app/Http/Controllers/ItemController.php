<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
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

        $data = Item::query();

        $data->with('location');

        if ($search) {
            $data->whereHas('location', function ($value) use ($search) {
                $value->where('item_nama', 'LIKE', "%" . $search . "%")
                    ->orWhere('lokasi_nama', 'LIKE', "%" . $search . "%")
                    ->orWhere('item_kondisi', 'LIKE', "%" . $search . "%");
            });
        }

        if ($sort && in_array($sort, ['item_nama', 'item_lokasi', 'item_kondisi', 'lokasi_nama'])) {
            $sortBy = $sort;
        } else {
            $sortBy = 'item_id';
        }

        if ($type && in_array($type, ['ASC', 'DESC'])) {
            $sortOrder = $type;
        } else {
            $sortOrder = 'DESC';
        }

        if ($sort == 'lokasi_nama') {
            $data->orderBy(Location::select('lokasi_nama')->whereColumn('lokasi_id', 'items.item_lokasi'), $sortOrder);
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

        // if ($paginate) {
        //     return ApiFormatter::createApi(200, 'Success',  $data->paginate($paginate));
        // } else {
        //     return ApiFormatter::createApi(200, 'Success', $data->paginate(10));
        // }
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
        //message validation
        $message = [
            'item_nama.required' => 'nama barang wajib diisi',
            'item_lokasi.required' => 'lokasi item wajib diisi',
            'item_jumlah.required' => 'jumlah item wajib diisi',
            'item_kondisi.required' => 'kondisi item wajib diisi',
            'item_spesifikasi.required' => 'spesifikasi item wajib diisi',
            'item_status.required' => 'lokasi item wajib diisi',
            'item_gambar.file' => 'yang anda upload bukan file',
            'item_gambar.image' => 'format file bukan gambar',
            'item_gambar.max' => 'ukuran gambar tidak boleh lebih dari 1024kilobytes'
        ];

        if ($request->file('item_gambar')) {
            //set validation
            $validator = Validator::make($request->all(), [
                'item_lokasi' => 'required',
                'item_nama' => 'required',
                'item_jumlah' => 'required',
                'item_kondisi' => 'required',
                'item_spesifikasi' => 'required',
                'item_status' => 'required',
                'item_gambar' => 'file|image|max:1024',
            ], $message);
        } else {
            //set validation
            $validator = Validator::make($request->all(), [
                'item_lokasi' => 'required',
                'item_nama' => 'required',
                'item_jumlah' => 'required',
                'item_kondisi' => 'required',
                'item_spesifikasi' => 'required',
                'item_status' => 'required',
            ], $message);
        }

        //response error validation
        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        //store item image
        if ($request->file('item_gambar')) {
            $item_gambar = $request->file('item_gambar')->store('item-image');
        } else {
            $item_gambar = 'item-image/default.png';
        }

        //save to database
        $item = Item::create([
            'item_lokasi' => $request->item_lokasi,
            'item_nama' => $request->item_nama,
            'item_jumlah' => $request->item_jumlah,
            'item_kondisi' => $request->item_kondisi,
            'item_spesifikasi' => $request->item_spesifikasi,
            'item_status' => $request->item_status,
            'item_gambar' => $item_gambar,
        ]);

        //success save to database
        if ($item) {
            return ApiFormatter::createApi(201, 'Item Created', $item);
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
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'item_lokasi' => 'required',
    //             'item_nama' => 'required',
    //             'item_lokasi' => 'required',
    //             'item_jumlah' => 'required',
    //             'item_gambar' => 'required',
    //             'item_spesifikasi' => 'required',
    //             'item_kondisi' => 'required',
    //             'item_status' => 'required',
    //         ]);

    //         $item = Item::findOrFail($id);

    //         $item->update([
    //             'item_lokasi' => $request->item_lokasi,
    //             'item_nama' => $request->item_nama,
    //             'item_lokasi' => $request->item_lokasi,
    //             'item_jumlah' => $request->item_jumlah,
    //             'item_gambar' => $request->item_gambar,
    //             'item_spesifikasi' => $request->item_spesifikasi,
    //             'item_kondisi' => $request->item_kondisi,
    //             'item_status' => $request->item_status,
    //         ]);

    //         $data = Item::where('item_id', '=', $item->item_id)->get();

    //         if ($data) {
    //             return ApiFormatter::createApi(200, 'Success', $data);
    //         } else {
    //             return ApiFormatter::createApi(400, 'Failed');
    //         }
    //     } catch (Exception $error) {
    //         return ApiFormatter::createApi(400, 'Failed');
    //     }
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItem(Request $request, $id)
    {
        //set message validation
        $message = [
            'item_nama.required' => 'nama barang wajib diisi',
            'item_lokasi.required' => 'lokasi item wajib diisi',
            'item_jumlah.required' => 'jumlah item wajib diisi',
            'item_kondisi.required' => 'kondisi item wajib diisi',
            'item_spesifikasi.required' => 'spesifikasi item wajib diisi',
            'item_status.required' => 'lokasi item wajib diisi',
            'item_gambar.file' => 'yang anda upload bukan file',
            'item_gambar.image' => 'format file bukan gambar',
            'item_gambar.max' => 'ukuran gambar tidak boleh lebih dari 1024kilobytes'
        ];

        if ($request->file('item_gambar')) {
            //set validation
            $validator = Validator::make($request->all(), [
                'item_lokasi' => 'required',
                'item_nama' => 'required',
                'item_jumlah' => 'required',
                'item_kondisi' => 'required',
                'item_spesifikasi' => 'required',
                'item_status' => 'required',
                'item_gambar' => 'file|image|max:1024',
            ], $message);
        } else {
            //set validation
            $validator = Validator::make($request->all(), [
                'item_lokasi' => 'required',
                'item_nama' => 'required',
                'item_jumlah' => 'required',
                'item_kondisi' => 'required',
                'item_spesifikasi' => 'required',
                'item_status' => 'required',
            ], $message);
        }

        //response error validation
        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $item = Item::findOrFail($id);

        if ($request->file('item_gambar')) {
            $item_gambar = $request->file('item_gambar')->store('item-image');

            if ($item->item_gambar != 'item-image/default.png') {
                Storage::delete($item->item_gambar);
            }
        } else {
            $item_gambar = $item->item_gambar;
        }

        $item->update([
            'item_lokasi' => $request->item_lokasi,
            'item_nama' => $request->item_nama,
            'item_lokasi' => $request->item_lokasi,
            'item_jumlah' => $request->item_jumlah,
            'item_gambar' => $item_gambar,
            'item_spesifikasi' => $request->item_spesifikasi,
            'item_kondisi' => $request->item_kondisi,
            'item_status' => $request->item_status,
        ]);

        $data = Item::where('item_id', '=', $item->item_id)->get();

        if ($data) {
            return ApiFormatter::createApi(201, 'Item Created', $data);
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
        $item = Item::findOrFail($id);

        $data = $item->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
