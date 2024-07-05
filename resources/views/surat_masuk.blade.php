@extends('layouts.mainlayout')
@section('title', 'Surat Masuk')
@include('sweetalert::alert')
<!-- partial -->
@php
$nama = session()->get('nama');
$akses = session()->get('hak_akses');
$rt = session()->get('rt');
$rw = session()->get('rw');
@endphp
@section('content')

@if (session::has('success'))
<script>
    toastr.success('Data Berhasil Ditambahkan', '')
</script>
@endif
@if (session::has('successedit'))
<script>
    toastr.success('Pengajuan Berhasil Disetujui', '')
</script>
@endif
@if (session::has('successhapus'))
<script>
    toastr.success('Data Berhasil Dihapus', '')
</script>
@endif
@if ($errors->any())
<script>
    toastr.error('Cek Kembali Data Pengajuan', 'Pengajuan Gagal Diupdate')
</script>
@endif
<div class="card" style="border-radius: 2px;">
    <div class="card-body">
        <div class="header-atas">
            <h5 class="font-weight-bold text-dark">Surat Masuk</h5>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jenis Surat</th>
                    <th>Waktu Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $no => $value)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $value->nik }}</td>
                    <td>{{ $value->nama_lengkap }}</td>
                    <td>{{ $value->nama_surat }}</td>
                    <td>{{ $value->created_at }} Pukul {{ $value->created_at }}</td>
                    <td>{{ $value->status }}</td>
                    <td>
                        <a class="btn btn-secondary" style="background: #00AAAA; color: white;" data-toggle="modal" data-target="#exampleModal{{ $value->id_pengajuan }}" style="color: white;" href="#">Proses
                            Surat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



<!-- Modal RT dan RW-->
@foreach ($data as $no => $value)
<div class="modal fade" id="exampleModal{{ $value->id_pengajuan }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('updatestatus/' . $value->id_pengajuan . '/' . $akses) }}" method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pratinjau Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($akses == 'admin')
                    <div class="form-group">
                        <label for="">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control @error('nomor_surat')is-invalid
                                @enderror" value="{{ old('nomor_surat') }}" autocomplete="off">
                        @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Nomor Pengantar</label>
                        <input type="text" name="" class="form-control" value="{{ $value->no_pengantar }}" autocomplete="off">
                    </div>
                    @endif
                    @if ($akses == 'RT')
                    <div class="form-group">
                        <label for="">Nomor Pengantar</label>
                        <input type="text" name="nomor_surat" class="form-control @error('nomor_surat')is-invalid
                            @enderror" value="{{ old('nomor_surat') }}" autocomplete="off">
                        @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    @if ($akses == 'RW')
                    <div class="form-group">
                        <label for="">Nomor Pengantar</label>
                        <input type="text" name="nomor_surat" class="form-control" value="{{ $value->no_pengantar }}" autocomplete="off" readonly>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ $value->nik }}" maxlength="50" required="">
                        <span class="text-danger">
                    </div>
                    <div class="form-group">
                        <label>Nama </label>
                        <input type="text" name="nama" class="form-control" value="{{ $value->nama_lengkap }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Tempat, Tanggal Lahir </label>
                        <input type="text" name="ttl" class="form-control" value="{{ $value->tempat_lahir }}, {{ $value->tgl_lahir }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Jenis kelamin</label>
                        <input type="text" name="kelamin" class="form-control" value="{{ $value->jenis_kelamin }}" maxlength="50" required="">
                    </div>
                    <div class="form-group ">
                        <label>Kebangsaan/Agama</label>
                        <input type="text" name="kebangsaan" class="form-control" value="{{ $value->kewarganegaraan }} / {{ $value->agama }}" maxlength="30" required="">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control" value="{{ $value->status_perkawinan }}" maxlength="50" required="">
                        <span class="text-danger">
                    </div>
                    <div class="form-group">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ $value->pekerjaan }}" maxlength="50" required="">
                        <span class="text-danger">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $value->alamat }}" maxlength="50" required="">
                        <span class="text-danger">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pengajuan</label>
                        <input type="text" name="tglpengajuan" class="form-control" value="{{ $value->created_at }}" maxlength="50" required="">
                        <span class="text-danger">
                    </div>
                    <div class="form-group">
                        <label>Keperluan Surat</label>
                        <input type="text" name="keperluan" class="form-control" value="{{ $value->keterangan }}" maxlength="50" required="">
                        <span class="text-danger">
                    </div>
                    <div class="form-group">
                        <label for="">Bukti Pendukung</label>
                        <img src="{{ asset('images/' . $value->image_bukti) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    <div class="form-group">
                        <label for="">Foto Kartu Keluarga</label>
                        <img src="{{ asset('images/' . $value->image_kk) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    <div class="form-group">
                        <label for="">Bukti ktp</label>
                        <img src="{{ asset('images/' . $value->image_ktp) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @foreach ($data as $no => $value)
                    @if ($value->nama_surat == 'Cetak Kartu Keluarga Baru')
                    <div class="form-group">
                        <label for="">Foto Surat Nikah</label>
                        <img src="{{ asset('images/' . $value->image_suratnikah) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @elseif ($value->nama_surat == 'Cetak Akta Kelahiran')
                    <div class="form-group">
                        <label for="">Foto Surat Nikah</label>
                        <img src="{{ asset('images/' . $value->image_suratnikah) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @endforeach
                    @if($value->nama_surat == 'Cetak Kartu Keluarga Baru')
                    <div class="form-group">
                        <label for="">Foto Akta Cerai</label>
                        <img src="{{ asset('images/' . $value->image_aktacerai) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if($value->nama_surat == 'Cetak Kartu Keluarga Hilang')
                    <div class="form-group">
                        <label for="">Foto Bukti Kehilangan</label>
                        <img src="{{ asset('images/' . $value->image_suratkehilangan) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if($value->nama_surat == 'Cetak Akta Kelahiran')
                    <div class="form-group">
                        <label for="">Foto Surat Bidan</label>
                        <img src="{{ asset('images/' . $value->image_bidan) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if($value->nama_surat == 'Cetak Akta Kelahiran')
                    <div class="form-group">
                        <label for="">Foto Bukti Surat Lahir</label>
                        <img src="{{ asset('images/' . $value->image_suratlahir) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if($value->nama_surat == 'Surat Keterangan Catatan Kepolisian')
                    <div class="form-group">
                        <label for="">Foto Akte Kelahiran</label>
                        <img src="{{ asset('images/' . $value->image_aktekelahiran) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    {{-- @if ($akses == 'RT')
                                <div class="form-group">
                                    <label>Keterangan Ditolak</label>
                                    <input type="text" name="keperluan" class="form-control"
                                        value="{{ $value->keterangan }}" maxlength="50" required="">
                    <span class="text-danger">
                </div>
                @endif --}}
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-secondary" style="background-color: rgb(0, 189, 0); color: white;">Setujui</button>
            @if ($akses == 'RT')
            <a type="button" data-toggle="modal" data-target="#modal-ditolak{{ $value->id }}" class="btn btn-danger" data-dismiss="modal" href="">Tolak</a>
            @endif
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        </div>
        </form>
    </div>
</div>
</div>
@endforeach

@foreach ($data as $no => $value)
<div class="modal fade" id="modal-ditolak{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('updatestatustolak/' . $value->id . '/' . $akses) }}" method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Keterangan Surat Ditolak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Keterangan Ditolak</label>
                        <textarea type="text" name="keterangan_ditolak" class="form-control" maxlength="50" required=""></textarea>
                        <span class="text-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection


<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- jquery cdn --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>