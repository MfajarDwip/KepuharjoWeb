@extends('layouts.mainlayout')
@section('title', 'Master Surat')
@include('sweetalert::alert')
<!-- partial -->
@section('content')
    @if (session::has('success'))
        <script>
            toastr.success('Data Berhasil Ditambahkan', '')
        </script>
    @endif
    @if (session::has('relation'))
        <script>
            toastr.error('Data Gagal Dihapus', 'Data Memiliki Relasi')
        </script>
    @endif
    @if (session::has('successedit'))
        <script>
            toastr.success('Data Berhasil Diperbarui', '')
        </script>
    @endif
    @if (session::has('successhapus'))
        <script>
            toastr.success('Data Berhasil Dihapus', '')
        </script>
    @endif
    @if (session::has('exist'))
        <script>
            toastr.error('Id Surat Sudah Ada', 'Data Gagal ditamabahkan')
        </script>
    @endif
    @if ($errors->any())
        <script>
            toastr.error('Cek Kembali Data yang Anda Input', 'Data Gagal Ditambahkan')
        </script>
    @endif
    <div class="card" style="border-radius: 2px;">
        <div class="card-body">
            <div class="header-atas">
                <h5 class="font-weight-bold text-dark">Halaman Master Surat</h5>
                <button data-toggle="modal" name='tambah' data-target="#modal-tambah">Tambah
                    data</button>
            </div>
            <table id="myTable" class="table table-bordered">
                <thead style="background-color: grey; color: white;">
                    <tr>
                        <th>
                            <h6>No</h6>
                        </th>
                        <th>
                            <h6>Jenis Surat</h6>
                        </th>
                        <th>
                            <h6>Ikon Surat</h6>
                        </th>
                        <th>
                            <h6>Aksi</h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $no => $value)
                        <tr>
                            <td class="text-lg">{{ $no + 1 }}</td>
                            <td class="text-lg">{{ $value->nama_surat }}</td>
                            <td><img style="width: 30px; height: 30px;  border-radius: 5%;"
                                    src="{{ asset('images/' . $value->image) }}">
                            </td>
                            <td>
                                <div class="row">
                                    <div class="btn-group">
                                        <a class="btn btn-warning btn-sm fa fa-pencil" style="color:white;"
                                            data-toggle="modal" data-target="#modal-editsurat{{ $value->id_surat }}">
                                        </a>
                                        <a class="btn btn-danger btn-sm icon-trash" data-toggle="modal"
                                            data-target="#modal-hapus{{ $value->id_surat }}"></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" autocomplete="off">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Master Surat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('simpansurat') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label for="">Id Surat</label>
                        <div class="form-group">
                            <label for="nomor-kartu"></label>
                            <input type="number" name="id_surat"
                                class="form-control  @error('id_surat')is-invalid
                                @enderror"
                                value="" placeholder="Id Surat" autocomplete="off">
                            @error('id_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="">Nama Surat</label>
                        <div class="form-group">
                            <label for="nomor-kartu"></label>
                            <input type="text" name="nama_surat"
                                class="form-control @error('nama_surat')is-invalid
                                @enderror"
                                value="" placeholder="Jenis Surat" autocomplete="off">
                            @error('nama_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="">Ikon Surat</label>
                        <div class="form-group">
                            <input type="file" name="image"
                                class="form-control  @error('image')is-invalid
                                @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-Success"
                            style="background-color: rgb(0, 189, 0); color: white;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal Edit Surat --}}
    @foreach ($data as $no => $value)
        <div class="modal fade" id="modal-editsurat{{ $value->id_surat }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Master Surat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('updateimages/' . $value->id_surat) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <label for="">Ikon Surat</label>
                            <div class="form-group">
                                <img width="100%;" height="100%;" src="{{ asset('images/' . $value->image) }}"
                                    alt="">
                            </div>
                            <div class="row form-group">
                                <div class="col-md-9">
                                    <input type="file" name="image"
                                        class="form-control @error('image')is-invalid
                            @enderror">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 ml-3 mt-1">
                                    <button type=" submit" class="btn btn-Success"
                                        style="background-color: rgb(0, 189, 0); color: white;">Update</button>
                                </div>
                            </div>
                        </form>
                        <form action="{{ url('editsurat/' . $value->id_surat) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <label for="">Id Surat</label>
                            <div class="form-group">
                                <label for="nomor-kartu"></label>
                                <input type="number" name="id_surat"
                                    class="form-control  @error('id_surat')is-invalid
                                @enderror"
                                    value="{{ old('id_surat', $value->id_surat) }}" placeholder="Id Surat"
                                    autocomplete="off" readonly>
                                @error('id_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="">Nama Surat</label>
                            <div class="form-group">
                                <label for="nomor-kartu"></label>
                                <input type="text" name="nama_surat"
                                    class="form-control @error('nama_surat')is-invalid
                                @enderror"
                                    value="{{ old('nama_surat', $value->nama_surat) }}" placeholder="Jenis Surat"
                                    autocomplete="off">
                                @error('nama_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-Success"
                                    style="background-color: rgb(0, 189, 0); color: white;">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- Batas Modal Edit Berita --}}

    @foreach ($data as $no => $value)
        <div class="modal fade" id="modal-hapus{{ $value->id_surat }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ url('hapussurat/' . $value->id_surat) }}" method="get">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Master Surat</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label for="">Yakin untuk Menghapus Jenis {{ $value->nama_surat }} ?</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection


<style>
    .header-atas {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header h4 {
        margin: 0;
    }

    .header button {
        margin-left: auto;
    }

    a {
        style="margin-top: 10px;

    }
</style>

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- jquery cdn --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
