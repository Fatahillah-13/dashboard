@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}
@section('content_body')
    <div class="row">
        {{-- Form Tambah Pegawai --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Kandidat karyawan</h3>
                    <div class="card-tools">
                        <!-- Collapse Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i></button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form id="karyawanForm">
                        @csrf
                        <div class="row">
                            <div class="col md-6">
                                <div class="form-group">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik"
                                        placeholder="optional">
                                </div>
                                <div class="form-group">
                                    <?php
                                    $posisi = App\Models\Posisi::all();
                                    ?>
                                    <label for="posisi">Level</label>
                                    <select name="level" id="level" class="select-level form-control" required>
                                        <option value="">Pilih Posisi</option>
                                        @foreach ($posisi as $level)
                                            <option value="{{ $level->id }}">{{ $level->level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $department = App\Models\Departemen::all();
                                    ?>
                                    <label for="workplace">Departemen</label>
                                    <select name="workplace" id="workplace" class="select-department form-control" required>
                                        <option value="">Pilih Departemen</option>
                                        @foreach ($department as $departemen)
                                            <option value="{{ $departemen->id }}">{{ $departemen->job_department }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        required>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input " id="myCheckbox" type="checkbox">
                                        <label class="form-check-label">Ambil Foto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col md-6 mx-3">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_foto" class="form-label">No Foto</label>
                                    <input type="number" class="form-control" id="no_foto" name="no_foto">
                                </div>
                            </div>
                            {{-- webcamjs --}}
                            <div class="col-12 mx-3 hidden" id="myDiv" style="padding: 16px 0px; justify-items:center">
                                <div id="my_camera" class="" style="margin-bottom: 16px">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" srcset=""
                                        style="width: 150px" height="150px">
                                </div>
                                <div id="preview" class="">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" srcset=""
                                        style="width: 150px" height="150px">
                                </div>
                                <input type="hidden" id="imagePath" name="imagePath" value="">
                            </div>
                        </div>
                        <div class="col-12 mx-3">
                            <div class="row form-group" style="gap: 16px">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#importExcel">Import Excel</button>
                                <button type="button" id="captureBtn" class="btn btn-success hidden"
                                    onclick="take_snapshot()">Ambil
                                    Gambar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.card -->
        {{-- Form Search & Edit Pegawai --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Kandidat Karyawan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="karyawanEditForm">
                        <input type="text" class="id_candidate" id="id_candidate" name="id_candidate"
                            placeholder="optional" hidden>
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="nama_edit2">Calon Karyawan</label>
                                    <select id="nama_edit2" name="nama_edit2"
                                        class="select-department form-control nama_edit2" onchange="GetDataKaryawan();"
                                        required>
                                        @php
                                            $karyawans_belum = App\Models\KaryawanBaru::whereDoesntHave(
                                                'gambarKaryawan',
                                            )->get();
                                        @endphp
                                        @foreach ($karyawans_belum as $karyawan)
                                            <option value="{{ $karyawan->id }}">Nama : {{ $karyawan->nama }} | Tempat Lahir
                                                {{ $karyawan->tempat_lahir }} | Tanggal Lahir : {{ $karyawan->tgl_lahir }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <script>
                                        function GetDataKaryawan() {
                                            var id = document.getElementById('nama_edit2').value;
                                            $.ajax({
                                                url: '{{ route('autocomplete2') }}', // Ganti dengan URL endpoint Anda
                                                type: 'GET',
                                                data: {
                                                    id: id
                                                },
                                                success: function(data) {
                                                    $('#id_candidate').val(data[0].id);
                                                    $('#nik_edit').val(data[0].nik);
                                                    $('#nama_edit').val(data[0].nama);
                                                    $('#tempat_lahir_edit').val(data[0].tempat_lahir);
                                                    $('#level_edit').val(data[0].level).trigger('change');
                                                    $('#workplace_edit').val(data[0].workplace).trigger('change');
                                                    $('#tgl_masuk_edit').val(data[0].tgl_masuk);
                                                    $('#no_foto_edit').val(data[1].no_foto).trigger('change');
                                                    if (data[1].foto) {
                                                        $('#preview_edit').html(
                                                            '<img src="{{ asset('storage/') }}' +
                                                            '/' + data[1].foto +
                                                            '" alt="Foto" width="150" height="150">'
                                                        );
                                                    } else {
                                                        $('#preview_edit').html(
                                                            '<img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" width="150" height="150">'
                                                        );
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Gagal mengambil data:', error);
                                                }
                                            });
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="col md-6">
                                <div class="form-group">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="nik_edit" name="nik_edit"
                                        placeholder="optional">
                                </div>
                                <div class="form-group">
                                    <label for="posisi">Level</label>
                                    <select name="level_edit" id="level_edit" class="select-level form-control" required>
                                        <option value="">Pilih Posisi</option>
                                        @foreach ($posisi as $level)
                                            <option value="{{ $level->id }}">{{ $level->level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="workplace">Departemen</label>
                                    <select name="workplace_edit" id="workplace_edit"
                                        class="select-department form-control" required>
                                        <option value="">Pilih Departemen</option>
                                        @foreach ($department as $departemen)
                                            <option value="{{ $departemen->id }}">{{ $departemen->job_department }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir_edit"
                                        name="tempat_lahir_edit" required>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input " id="myCheckbox_edit" type="checkbox">
                                        <label class="form-check-label">Ambil Foto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col md-6 mx-3">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control nama_edit" id="nama_edit" name="nama_edit"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" value="2025-01-17" class="form-control" id="tgl_lahir_edit"
                                        name="tgl_lahir_edit" required>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk_edit" name="tgl_masuk_edit"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="no_foto" class="form-label">No Foto</label>
                                    <input type="number" class="form-control" id="no_foto_edit" name="no_foto_edit">
                                </div>
                            </div>
                            <div class="col-12 mx-3" id="myDiv_edit" style="padding: 16px 0px; justify-items: center">
                                <div id="my_camera_edit" class="hidden" style="margin-bottom: 16px">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture"
                                        style="width: 150px" height="150px">
                                </div>
                                <div id="preview_edit" class="">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture"
                                        style="width: 150px" height="150px">
                                </div>
                                <input type="hidden" id="imagePath_edit" name="imagePath_edit" value="">
                            </div>
                        </div>
                        <div class="col-12 mx-3">
                            <div class="row form-group" style="gap: 16px">
                                <button type="submit" id="submitEdit" class="btn btn-primary">Simpan</button>
                                <button type="button" id="captureBtn_edit" class="btn btn-success hidden"
                                    onclick="take_snapshot_edit()">Ambil
                                    Gambar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Table --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tabel Daftar Kandidat</h3>

                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-head-fixed text-nowrap" id="karyawanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>No. Foto</th>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Departemen</th>
                            <th>Foto</th>
                            <th>Tgl. Daftar</th>
                            <th>Tgl. Lahir</th>
                            <th>Tgl. Masuk</th>
                            <th>Tgl. Foto</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $karyawans = App\Models\KaryawanBaru::whereDoesntHave('gambarKaryawan')->get();
                        ?>
                        @foreach ($karyawans as $index => $karyawan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $karyawan->nik }}</td>
                                <td>{{ $karyawan->gambarKaryawan->no_foto ?? 'N/A' }}</td>
                                <!-- Menampilkan no_foto jika ada -->
                                <td>{{ $karyawan->nama }}</td>
                                <td>{{ $karyawan->posisi->level ?? 'N/A' }}</td> <!-- Menampilkan nama level -->
                                <td>{{ $karyawan->departemen->job_department ?? 'N/A' }}</td>
                                <!-- Menampilkan nama departemen -->
                                <td>
                                    @if ($karyawan->gambarKaryawan && $karyawan->gambarKaryawan->foto)
                                        <!-- Cek apakah gambarKaryawan ada dan path-nya ada -->
                                        <img src="{{ asset('storage/' . $karyawan->gambarKaryawan->foto) }}"
                                            alt="Foto" width="100">
                                    @else
                                        Belum foto
                                    @endif
                                </td>
                                <td>{{ $karyawan->created_at }}</td>
                                <td>{{ $karyawan->tgl_lahir }}</td>
                                <td>{{ $karyawan->tgl_masuk }}</td>
                                <td>{{ $karyawan->gambarKaryawan->created_at ?? 'Belum Foto' }}</td>
                                <!-- Menampilkan tgl_foto jika ada -->
                                <td>
                                    {{-- <a href="{{ route('api.users.update', $karyawan->id) }}"
                                        class="btn btn-warning">Edit</a> --}}
                                    <form action="{{ route('api.users.delete', $karyawan->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    {{-- /.Table --}}
    {{-- Modal Delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- /. Modal Delete --}}

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/siswa/import_excel" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                    </div>
                    <div class="modal-body">

                        {{ csrf_field() }}

                        <label>Pilih file excel</label>
                        <div class="form-group">
                            <input type="file" name="file" required="required">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/toastr/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('.select-level').select2();
            $('.select-department').select2();
            // $('.nama_edit').select2(
            //     selectOnClose: true
            // );
        });
    </script>
    {{-- CRUD --}}
    <script>
        data_uri = "";
        // Configure the webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        // Function to take a snapshot
        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                // Show the preview
                document.getElementById('preview').src = data_uri;
                document.getElementById('preview').style.display = 'block';
                document.getElementById('preview').innerHTML = '<img src="' + data_uri + '"/>';

                // Simpan data URI ke dalam input hidden
                document.getElementById('imagePath').value = data_uri; // Menyimpan data URI ke input hidden

            });
        }


        $(document).ready(function() {
            const checkbox = document.getElementById('myCheckbox');
            const myDiv = document.getElementById('myDiv');
            const shuterBtn = document.getElementById('captureBtn');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    myDiv.classList.remove('hidden');
                    shuterBtn.classList.remove('hidden');
                    // Attach the webcam to the div
                    Webcam.attach('#my_camera');
                } else {
                    myDiv.classList.add('hidden');
                    shuterBtn.classList.add('hidden');
                    Webcam.reset('#my_camera');
                }
            });

            // Create form submit
            $('#karyawanForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah form dari submit biasa  
                // Prepare form data
                var imageData = $('#imagePath').val(); // Ambil data URI dari input hidden

                // Buat payload JSON
                var payload = {
                    nik: $('#nik').val(),
                    nama: $('#nama').val(),
                    level: $('#level').val(),
                    workplace: $('#workplace').val(),
                    tempat_lahir: $('#tempat_lahir').val(),
                    tgl_lahir: $('#tgl_lahir').val(),
                    tgl_masuk: $('#tgl_masuk').val(),
                    no_foto: $('#no_foto').val(),
                    foto: imageData // Tambahkan data gambar
                };

                $.ajax({
                    url: '{{ route('karyawan-baru.store') }}',
                    type: 'POST',
                    data: payload,
                    success: function(response) {
                        // Handle success
                        toastr.success('Data Kandidat telah disimpan');
                        // Anda bisa mereset form atau melakukan redirect  
                        $('#karyawanForm')[0].reset();
                        table.ajax.reload()
                    },
                    error: function(xhr, status, error) {
                        // Handle error  
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                alert(value[0]);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });

            // Update button click
            // $('#users-table').on('click', '.edit', function() {
            //     var id = $(this).data('id');
            //     $.get('/api/karyawan/' + id, function(data) {
            //         $('#userId').val(data.id);
            //         $('#nik').val(data.nik);
            //         $('#nama').val(data.nama);
            //         $('#level').val(data.level);
            //         $('#workplace').val(data.workplace);
            //         $('#tempat_lahir').val(data.tempat_lahir);
            //         $('#tgl_lahir').val(data.tgl_lahir);
            //         $('#tgl_masuk').val(data.tgl_masuk);
            //         // $('#editModal').modal('show');
            //     });
            // });

            // Delete button click
            $('#karyawanTable').on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: '/api/karyawan/delete/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            toastr.success('Data berhasil dihapus.');
                        }
                    });
                }
            });
        });

        /*
        $(document).ready(function() {
            $('#nama_edit, #tgl_lahir_edit').on('input change', function() {
                var nama = $('#nama_edit').val();
                var tgl_lahir = $('#tgl_lahir_edit').val();

                if (nama && tgl_lahir) {
                    $.ajax({
                        url: '{{ route('autocomplete') }}', // Ganti dengan URL endpoint Anda
                        type: 'GET',
                        data: {
                            nama: nama,
                            tgl_lahir: tgl_lahir
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(index, karyawan) {
                                    $('#id_candidate').val(karyawan.id);
                                    $('#tempat_lahir_edit').val(karyawan.tempat_lahir);
                                    $('#no_foto_edit').val(karyawan.gambarkaryawan
                                        .no_foto);
                                    $('#level_edit').val(karyawan.posisi.id).trigger(
                                        'change');
                                    $('#workplace_edit').val(karyawan.departemen.id)
                                        .trigger('change');
                                    $('#tgl_masuk_edit').val(karyawan.tgl_masuk)
                                        .trigger('change');
                                    if (karyawan.gambarkaryawan && karyawan
                                        .gambarkaryawan.foto) {
                                        $('#preview_edit').html(
                                            '<img src="{{ asset('storage/') }}' +
                                            '/' + karyawan.gambarkaryawan.foto +
                                            '" alt="Foto" width="150" height="150">'
                                        );
                                    } else {
                                        $('#preview_edit').html(
                                            '<img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" width="150" height="150">'
                                        );
                                    }

                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Gagal mengambil data:', error);
                        }
                    });
                }
            });
        });
        */
    </script>
    <script>
        data_uri_update = "";
        // Configure the webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        function take_snapshot_edit() {
            Webcam.snap(function(data_uri_update) {
                // Show the preview
                document.getElementById('preview_edit').src = data_uri_update;
                document.getElementById('preview_edit').style.display = 'block';
                document.getElementById('preview_edit').innerHTML = '<img src="' + data_uri_update + '"/>';
                // Simpan data URI ke dalam input hidden
                document.getElementById('imagePath_edit').value =
                    data_uri_update; // Menyimpan data URI ke input hidden
            });
        }

        $(document).ready(function() {

            const checkbox = document.getElementById('myCheckbox_edit');
            const myDiv = document.getElementById('my_camera_edit');
            const shuterBtn = document.getElementById('captureBtn_edit');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    myDiv.classList.remove('hidden');
                    shuterBtn.classList.remove('hidden');
                    // Attach the webcam to the div
                    document.getElementById('my_camera_edit').style.display = "";
                    Webcam.attach('#my_camera_edit');
                } else {
                    myDiv.classList.add('hidden');
                    shuterBtn.classList.add('hidden');
                    Webcam.reset('#my_camera_edit');
                }
            });

            // Update form submit
            $('#karyawanEditForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#id_candidate').val();
                var imageData_update = $('#imagePath_edit').val();
                // Buat payload JSON
                var payload_update = {
                    id: id,
                    nik: $('#nik_edit').val(),
                    nama: $('#nama_edit').val(),
                    level: $('#level_edit').val(),
                    workplace: $('#workplace_edit').val(),
                    tempat_lahir: $('#tempat_lahir_edit').val(),
                    tgl_lahir: $('#tgl_lahir_edit').val(),
                    tgl_masuk: $('#tgl_masuk_edit').val(),
                    no_foto: $('#no_foto_edit').val(),
                    foto: imageData_update
                };
                // console.log(payload_update);
                $.ajax({
                    url: '/api/karyawan/update/' + id,
                    type: 'POST',
                    // type: 'application/json',
                    data: payload_update,
                    success: function(response) {
                        // console.log(response);
                        var calon_option = ``;
                        for (let kl = 0; kl < response.length; kl++) {
                            calon_option += `<option value="` + response[kl].id + `"> Nama : ` +
                                response[kl].nama + ` | TEMPAT LAHIR : ` + response[kl]
                                .tempat_lahir + ` | TGL LAHIR : ` + response[kl].tgl_lahir +
                                `</option>`;
                        }
                        document.getElementById('nama_edit2').innerHTML = calon_option;
                        document.getElementById('my_camera_edit').style.display = "none";
                        Webcam.reset('#my_camera_edit');
                        document.getElementById('myCheckbox_edit').checked = false;
                        toastr.success('Data berhasil diperbarui.');
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                alert(value[0]);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });

            });
        });
    </script>
@endpush
