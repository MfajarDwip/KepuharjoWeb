<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratRequest;
use App\Models\MobileMasterSuratModel;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $data = MobileMasterSuratModel::all();

        return view('master_surat', compact('data'));
    }

    public function store(SuratRequest $suratrequest)
    {
        $valid = $suratrequest->id_surat;
        $check = MobileMasterSuratModel::all();
        foreach ($check as  $value) {
            if ($value->id_surat == $valid) {
                return redirect()->back()->with('exist','')->withInput();
            }
        }
        $imageName = time().'.'.$suratrequest->image->getClientOriginalExtension();
        $suratrequest->image->move(public_path('images'), $imageName);
        $validated['image'] = $imageName;
        $validated = $suratrequest->validated();
        $surat = MobileMasterSuratModel::create([
            'id_surat' => $validated['id_surat'],
            'nama_surat' => $validated['nama_surat'],
            'image' => $imageName,
        ]);

        return Redirect('mastersurat')->with('success', '');
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = MobileMasterSuratModel::where('id_surat', $id);
            $data->delete();

            return Redirect('mastersurat')->with('successhapus', '');
        } catch (\Throwable $th) {
            // return response()->json([
            //     'message' => 'Data Tidak Bisa Dihapus, Sudah Pernah Mengajukan',
            // ], 200);
            return redirect()->back()->with('relation','');
        }

    }

    public function update(SuratRequest $suratrequest, $id)
    {
        $data = MobileMasterSuratModel::where('id_surat', $id)->first();
        $validated = $suratrequest->validated();
        $data->update([
            'id_surat' => $validated['id_surat'],
            'nama_surat' => $validated['nama_surat'],
        ]);

        return Redirect('mastersurat')->with('successedit', '');
    }

    public function updateimage(Request $suratrequest, $id){

        request()->validate([
            'image' => 'required|image|mimes: jpeg,png,jpg,gif,svg|max:2048'
        ],
        [
            'image.required' => 'Gambar Surat Harus diisi',
            'image.mimes' => 'Format Ikon Harus jpeg,png,jpg,gif,svg',
            'image.max'=> 'Ukuran Ikon Maksimal 2 Mb'
        ]);

        $imageName = time().'.'.$suratrequest->image->getClientOriginalExtension();
        $suratrequest->image->move(public_path('images'), $imageName);
        $data = MobileMasterSuratModel::where('id_surat', $id);
        $data->update([
            'image' => $imageName,
        ]);

        return Redirect('mastersurat')->with('successedit', '');
    }
}
