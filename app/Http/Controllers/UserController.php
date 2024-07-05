<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Models\MobileMasterAkunModel;

class UserController extends Controller
{
    public function masteruser()
    {
        $data = MobileMasterAkunModel::with('user')
            ->where('role', '=', '4')->get();

        return view('master_user', compact('data'));
    }

    public function update(PasswordRequest $passwordrequest, $id)
    {
        $data = MobileMasterAkunModel::where('id_masyarakat', $id)->first();
        $validated = $passwordrequest->validated();
        $data->update($validated);

        return redirect('masteruser')->with('successedit', '');
    }
}
