<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilePengajuanSuratModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surats';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = ['id_masyarakat', 'id_surat', 'uuid', 'keterangan', 'id_pengajuan', 'status', 'file_pdf', 'info','image_bukti', 'image_kk', 'image_ktp', 'image_suratnikah', 'image_aktacerai', 'image_suratkehilangan', 'image_bidan', 'image_suratlahir', 'image_suratkematian', 'image_aktekelahiran'];


    public function masyarakat()
    {
        return $this->belongsTo(MobileMasterMasyarakatModel::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function surat()
    {
        return $this->belongsTo(MobileMasterSuratModel::class, 'id_surat', 'id_surat');
    }

    public function pengajuan()
    {
        return $this->hasOne(MobileMasterMasyarakatModel::class, 'id_masyarakat', 'id_masyarakat')
            ->join('master_kks', 'master_kks.id', '=', 'master_masyarakats.id')
            ->join('master_surats', 'id_surat', '=', 'id_surat');
    }
}
