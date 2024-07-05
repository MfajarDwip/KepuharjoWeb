<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterAkunModel;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Models\MobilePengajuanSuratModel;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;

class ApiPengajuanRtController extends Controller
{
    //rekap surat rt sesuai status
    public function status_surat_rt(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;
        $status = $request->input('status');

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {
                    $suratData = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
                    })
                    ->with(['masyarakat.akun', 'surat'])
                    ->when($status, function ($query, $status) {
                        return $query->where('status', $status);
                    })
                    ->get();

                    return response()->json($suratData);
                }

            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

    }

    //rekap semua surat rt
    public function rekap_rt(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {

                    $suratData = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
                    })
                    ->with(['masyarakat', 'surat'])
                    ->whereNotIn('status', ['Dibatalkan'])
                    ->orderByDesc('id_pengajuan')
                    ->paginate(10);

                    return response()->json($suratData);
                }

            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

    }

    public function update_status_setuju_rt(Request $request, $id)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {

                    $surat = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
                    })->findOrFail($id);

                    $no_pengantar = $request->input('no_pengantar');

                    $surat->status = "Disetujui RT";
                    $surat->no_pengantar = $no_pengantar;

                    $surat->save();

                    return response()->json([
                        'message' => 'Status surat updated successfully',
                        'surat' => $surat
                    ]);
                }

            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }
    }
    public function update_status_tolak_rt(Request $request, $id)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {

                    $surat = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
                    })->findOrFail($id);

                    $keterangan_tolak = $request->input('keterangan_ditolak');

                    $surat->status = "Ditolak RT";
                    $surat->info = "non_active";
                    $surat->keterangan_ditolak = $keterangan_tolak;
                    $surat->save();

                    return response()->json([
                        'message' => 'Status surat updated successfully',
                        'surat' => $surat
                    ]);
                }

            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }
    }

    public function sendNotificationsRw(Request $request)
    {
        $akun = $request->user()->id_masyarakat;

        $userMasyarakat =  MobileMasterMasyarakatModel::whwere('id_masyarakat', $akun)->first();

        if ($userMasyarakat) {
            $userWithSameRw = MobileMasterAkunModel::whereHas('masyarakat', function ($query) use ($userMasyarakat) {
                $query->where('id_kk', $userMasyarakat->id_kk);
            })->where('role', 3)->first();
            if ($userWithSameRw) {
                $token = $userWithSameRw->fcm_token;

                try {
                    $message = CloudMessage::new()
                        ->withNotification(Notification::create("Surat Masuk", "Terdapat surat masuk"))
                        ->withChangedTarget('token', $token);

                    FirebaseMessaging::send($message);
                    return response()->json(['message' => 'Notification sent successfully.']);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Failed to send notification.'], 500);
                }
            } else {
                return response()->json(['message' => 'User with same rt and role 3 not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'User Masyarakat not found.'], 404);
        }
    }

}
