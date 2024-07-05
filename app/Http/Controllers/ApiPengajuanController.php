<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterAkunModel;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Models\MobilePengajuanSuratModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;

class ApiPengajuanController extends Controller
{
    public function pengajuan(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'keterangan' => 'required',
            'id_surat' => 'required',
            'image_bukti' => 'required|image',
            'image_kk' => 'required|image',
            'image_ktp' => 'required|image',
            'image_suratnikah' => 'sometimes|nullable|image',
            'image_aktacerai' => 'sometimes|nullable|image',
            'image_suratkehilangan' => 'sometimes|nullable|image',
            'image_bidan' => 'sometimes|nullable|image',
            'image_suratlahir' => 'sometimes|nullable|image',
            'image_suratkematian' => 'sometimes|nullable|image',
            'image_aktekelahiran' => 'sometimes|nullable|image'
        ]);
        $masyarakat = MobileMasterMasyarakatModel::where('nik', $request->nik)->first();

        $imagebukti = $request->file('image_bukti');
        $imagenamebukti = "img_" . Str::random(10) . time() . '.' . $imagebukti->getClientOriginalExtension();
        $imagebukti->move(public_path('images/'), $imagenamebukti);

        $imagekk = $request->file('image_kk');
        $imagenamekk = "img_" . Str::random(10) . time() . '.' . $imagekk->getClientOriginalExtension();
        $imagekk->move(public_path('images/'), $imagenamekk);

        $imagektp = $request->file('image_ktp');
        $imagenamektp = "img_" . Str::random(10) . time() . '.' . $imagektp->getClientOriginalExtension();
        $imagektp->move(public_path('images/'), $imagenamektp);

        $imagenamesuratnikah = null;
        $imagesuratnikah = $request->file('image_suratnikah');

        if ($imagesuratnikah) {
            $imagenamesuratnikah = "img_" . Str::random(10) . time() . '.' . $imagesuratnikah->getClientOriginalExtension();
            $imagesuratnikah->move(public_path('images/'), $imagenamesuratnikah);
        }

        $imagenameaktacerai = null;
        $imageaktacerai = $request->file('image_aktacerai');

        if ($imageaktacerai) {
            $imagenameaktacerai = "img_" . Str::random(10) . time() . '.' . $imageaktacerai->getClientOriginalExtension();
            $imageaktacerai->move(public_path('images/'), $imagenameaktacerai);
        }

        $imagenamesuratkehilangan = null;
        $imagesuratkehilangan = $request->file('image_suratkehilangan');

        if ($imagesuratkehilangan) {
            $imagenamesuratkehilangan = "img_" . Str::random(10) . time() . '.' . $imagesuratkehilangan->getClientOriginalExtension();
            $imagesuratkehilangan->move(public_path('images/'), $imagenamesuratkehilangan);
        }

        $imagenamebidan = null;
        $imagebidan = $request->file('image_bidan');

        if ($imagebidan) {
            $imagenamebidan = "img_" . Str::random(10) . time() . '.' . $imagebidan->getClientOriginalExtension();
            $imagebidan->move(public_path('images/'), $imagenamebidan);
        }

        $imagenamesuratlahir = null;
        $imagesuratlahir = $request->file('image_suratlahir');

        if ($imagesuratlahir) {
            $imagenamesuratlahir = "img_" . Str::random(10) . time() . '.' . $imagesuratlahir->getClientOriginalExtension();
            $imagesuratlahir->move(public_path('images/'), $imagenamesuratlahir);
        }

        $imagenamesuratkematian = null;
        $imagesuratkematian = $request->file('image_suratkematian');

        if ($imagesuratkematian) {
            $imagenamesuratkematian = "img_" . Str::random(10) . time() . '.' . $imagesuratkematian->getClientOriginalExtension();
            $imagesuratkematian->move(public_path('images/'), $imagenamesuratkematian);
        }

        $imagenameaktekelahiran = null;
        $imageaktekelahiran = $request->file('image_aktekelahiran');

        if ($imageaktekelahiran) {
            $imagenameaktekelahiran = "img_" . Str::random(10) . time() . '.' . $imageaktekelahiran->getClientOriginalExtension();
            $imageaktekelahiran->move(public_path('images/'), $imagenameaktekelahiran);
        }

        $cek = MobilePengajuanSuratModel::where('id_surat', $request->id_surat)
            ->where('id_masyarakat', $masyarakat->id_masyarakat)
            ->where('info', 'active')
            ->exists();

        if ($cek) {
            return response()->json([
                'message' => 'Surat sebelumnya belum selesai',
            ], 200);
        } else {
            $data = MobilePengajuanSuratModel::create([
                'keterangan' => $request->keterangan,
                'status' => 'Diajukan',
                'info' => 'active',
                'uuid' => Str::uuid(),
                'id_surat' => $request->id_surat,
                'image_bukti' => $imagenamebukti,
                'image_kk' => $imagenamekk,
                'image_ktp' => $imagenamektp,
                'image_suratnikah' => $imagenamesuratnikah,
                'image_aktacerai' => $imagenameaktacerai,
                'image_suratkehilangan' => $imagenamesuratkehilangan,
                'image_bidan' => $imagenamebidan,
                'image_suratlahir' => $imagenamesuratlahir,
                'image_suratkematian' => $imagenamesuratkematian,
                'image_aktekelahiran' => $imagenameaktekelahiran,
                'id_masyarakat' => $masyarakat->id_masyarakat,
            ]);

            return response()->json([
                'message' => 'Berhasil mengajukan surat',
                'data' => $data
            ], 200);
        }
    }

    public function status_proses(Request $request)
    {
        $user = $request->user();
        $id_masyarakat = $user->id_masyarakat;

        $no_kk = MobileMasterKksModel::whereHas('masyarakat', function ($query) use ($id_masyarakat) {
            $query->where('id_masyarakat', $id_masyarakat);
        })->value('no_kk');

        $pengajuan_surats = MobilePengajuanSuratModel::with('surat', 'masyarakat.kks')
            ->where(function ($query) use ($id_masyarakat, $no_kk) {
                $query->where('id_masyarakat', $id_masyarakat)
                    ->orWhereHas('masyarakat.kks', function ($query) use ($no_kk) {
                        $query->where('no_kk', $no_kk);
                    });
            })
            ->whereNotIn('status', ['Selesai', 'Diajukan', 'Dibatalkan', 'Ditolak RT'])
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $pengajuan_surats,
        ], 200);
    }


    public function status_surat(Request $request)
    {
        $user = $request->user();
        $id_masyarakat = $user->id_masyarakat;
        $status = $request->status;

        $no_kk = MobileMasterKksModel::whereHas('masyarakat', function ($query) use ($id_masyarakat) {
            $query->where('id_masyarakat', $id_masyarakat);
        })->value('no_kk');

        $pengajuan_surats = MobilePengajuanSuratModel::with('surat', 'masyarakat.kks')
            ->where(function ($query) use ($id_masyarakat, $no_kk) {
                $query->where('id_masyarakat', $id_masyarakat)
                    ->orWhereHas('masyarakat.kks', function ($query) use ($no_kk) {
                        $query->where('no_kk', $no_kk);
                    });
            })
            ->where('status', $status)
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $pengajuan_surats,
        ], 200);
    }

    public function pembatalan(Request $request, $id)
    {
        $userId = $request->user()->id_masyarakat;
        $pengajuanSurat = MobilePengajuanSuratModel::where('id_pengajuan', $id)
            ->where('id_masyarakat', $userId)
            ->where('status', 'Diajukan')
            ->firstOrFail();

        $pengajuanSurat->update(['status' => 'Dibatalkan', 'info' => 'non_active']);

        return response()->json([
            'message' => 'Surat berhasil dibatalkan',
        ], 200);
    }

    // public function sendNotification(Request $request)
    // {
    //     // Langkah 1: Mendapatkan user id_masyarakat dari Request
    //     $userId = $request->user()->id_masyarakat;

    //     // Langkah 2: Mencari data masyarakat berdasarkan id_masyarakat
    //     $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

    //     if ($userMasyarakat) {
    //         // Langkah 3: Mencari data KKS berdasarkan id_kk dari data masyarakat
    //         $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

    //         if ($userKks) {
    //             // Langkah 4: Mengambil nilai rt dari data KKS
    //             $rtValue = $userKks->rt;

    //             // Langkah 5: Mencari akun dengan rt yang sama dan role bernilai 2
    //             $userWithSameRt = MobileMasterAkunModel::join('master_masyarakats', 'master_akuns.id_masyarakat', '=', 'master_masyarakats.id_masyarakat')
    //                 ->where('master_masyarakats.id_kk', $userMasyarakat->id_kk)
    //                 ->where('master_akuns.role', 2)
    //                 ->first();

    //             // Langkah 6: Jika akun dengan rt yang sama dan role 2 ditemukan, kirim notifikasi
    //             if ($userWithSameRt) {
    //                 $token = $userWithSameRt->fcm_token;

    //                 try {
    //                     $message = CloudMessage::new()
    //                         ->withNotification(Notification::create("Surat Masuk", "Pengajuan Surat Masuk"))
    //                         ->withChangedTarget('token', $token);

    //                     FirebaseMessaging::send($message);
    //                     return response()->json(['message' => 'Notification sent successfully.']);
    //                 } catch (\Exception $e) {
    //                     return response()->json(['message' => 'Failed to send notification.'], 500);
    //                 }
    //             } else {
    //                 return response()->json(['message' => 'User with same rt and role 2 not found.'], 404);
    //             }
    //         } else {
    //             return response()->json(['message' => 'User KKS not found.'], 404);
    //         }
    //     } else {
    //         return response()->json(['message' => 'User Masyarakat not found.'], 404);
    //     }
    // }
    public function sendNotification(Request $request)
    {
        // Langkah 1: Mendapatkan user id_masyarakat dari Request
        $userId = $request->user()->id_masyarakat;

        // Langkah 2: Mencari data masyarakat berdasarkan id_masyarakat
        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            // Langkah 3: Mencari akun dengan rt yang sama dan role bernilai 2
            $userWithSameRt = MobileMasterAkunModel::whereHas('masyarakat', function ($query) use ($userMasyarakat) {
                $query->where('id_kk', $userMasyarakat->id_kk);
            })->where('role', 2)->first();

            if ($userWithSameRt) {
                $token = $userWithSameRt->fcm_token;

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
                return response()->json(['message' => 'User with same rt and role 2 not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'User Masyarakat not found.'], 404);
        }
    }

    public function sendNotificationUser(Request $request)
    {
        $nik = $request->input('nik');
        $title = $request->input('title');
        $body = $request->input('body');

        // Cari id_masyarakat berdasarkan NIK
        $idMasyarakat = MobileMasterMasyarakatModel::where('nik', $nik)
            ->value('id_masyarakat');

        if ($idMasyarakat) {
            // Cek apakah ada FCM token berdasarkan id_masyarakat
            $userWithToken = MobileMasterAkunModel::where('id_masyarakat', $idMasyarakat)
                ->whereNotNull('fcm_token')
                ->first();

            if ($userWithToken) {
                // Kirim notifikasi ke FCM token yang ditemukan
                // Implementasi pengiriman notifikasi ke FCM di sini
                $token = $userWithToken->fcm_token;
                $message = CloudMessage::new()
                    ->withNotification(Notification::create($title, $body))
                    ->withChangedTarget('token', $token);

                FirebaseMessaging::send($message);
                return response()->json([
                    'message' => 'Notifikasi berhasil dikirim',
                ], 200);
            } else {
                // Cari id_masyarakat dengan id_kk yang sama yang memiliki FCM token
                $idKK = MobileMasterMasyarakatModel::where('id_masyarakat', $idMasyarakat)
                    ->value('id_kk');

                $userWithSameKK = MobileMasterAkunModel::whereHas('masyarakat', function ($query) use ($idKK) {
                    $query->where('id_kk', $idKK);
                })
                    ->whereNotNull('fcm_token')
                    ->first();

                if ($userWithSameKK) {
                    $token = $userWithSameKK->fcm_token;
                    $message = CloudMessage::new()
                        ->withNotification(Notification::create($title, $body))
                        ->withChangedTarget('token', $token);

                    FirebaseMessaging::send($message);

                    return response()->json([
                        'message' => 'Notifikasi berhasil dikirim berdasarkan id_kk yang sama',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Tidak ada FCM token yang ditemukan untuk id_masyarakat atau id_kk yang sama',
                    ], 404);
                }
            }
        } else {
            return response()->json([
                'message' => 'Tidak ditemukan id_masyarakat berdasarkan NIK yang diberikan',
            ], 404);
        }
    }
}
