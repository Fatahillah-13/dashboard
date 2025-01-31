@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}

@section('content_body')
    {{-- Input Section --}}
    <div class="container p-4">
        <div class="row">
            {{-- Form Input --}}
            <div class="col-md-6 p-3">
                <div class="card">
                    <form id="karyawanForm"
                        action="{{ isset($karyawan) ? route('api.users.update', $karyawan->id) : route('karyawan-baru.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($karyawan))
                            @method('PUT')
                        @endif
                        <h3 class="text-center">Tambah Calon Karyawan</h3>
                        <div class="row">
                            <div class="col md-6">
                                <input type="number" class="form-control" id="userId" name="userId" hidden>
                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik"
                                        placeholder="optional">
                                </div>
                                <div class="mb-3">
                                    <label for="posisi">Level</label>
                                    <x-posisi-dropdown />
                                </div>
                                <div class="mb-3">
                                    <label for="workplace">Departemen</label>
                                    <x-departemen-dropdown />
                                </div>
                                <div class="mb-3">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        required>
                                </div>
                            </div>
                            <div class="col md-6 mx-3">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_foto" class="form-label">No Foto</label>
                                    <input type="number" class="form-control" id="	no_foto" name="	no_foto" required>
                                </div>

                            </div>
                            <div class="col-12 mx-3">
                                <div id="my_camera" class="d-flex justify-content-center">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" srcset=""
                                        style="width: 150px" height="150px">
                                </div>
                                <div id="result" class="d-flex justify-content-center">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" srcset=""
                                        style="width: 150px" height="150px">
                                </div>
                                <div class="col md-4 d-flex justify-content-center">
                                    <button id="toggleWebcamBtn" class="btn btn-primary">Nyalakan Kamera</button>
                                </div>
                                <div class="col md-4 d-flex justify-content-center">
                                    <button id="captureBtn" class="btn btn-success">Ambil Gambar</button>
                                </div>
                                <div class="col md-4 d-flex justify-content-center">
                                    <button id="saveBtn" class="btn btn-info">Simpan Foto</button>
                                </div>


                            </div>
                            <div class="col-12 mx-3">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 p-3">
                <div class="card">
                    <h3 class="text-center">Update Calon Karyawan</h3>
                    <form id="karyawanForm"
                        action="{{ isset($karyawan) ? route('api.users.update', $karyawan->id) : route('karyawan-baru.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($karyawan))
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col md-6">
                                <input type="number" class="form-control" id="userId" name="userId" hidden>
                                <div class="mb-3">
                                    <label for="nik" class="form-label">Cari</label>
                                    <select class="form-control js-example-basic-single" name="nik" id="nik">
                                        <?php
                                        $karyawan_barus = App\Models\KaryawanBaru::all();
                                        ?>
                                        @foreach ($karyawan_barus as $kar)
                                            <option value="{{ $kar->id }}">{{ $kar->tgl_masuk }} | {{ $kar->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="posisi">Level</label>
                                    <x-posisi-dropdown />
                                </div>
                                <div class="mb-3">
                                    <label for="workplace">Departemen</label>
                                    <x-departemen-dropdown />
                                </div>
                                <div class="mb-3">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        required>
                                </div>
                            </div>
                            <div class="col md-6 mx-3">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_foto" class="form-label">No Foto</label>
                                    <input type="number" class="form-control" id="	no_foto" name="	no_foto" required>
                                </div>

                            </div>

                            <div class="col-12 ml-3">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>

    {{-- Buttons --}}
    {{-- <div class="row pb-3">
        <button type="button" class="btn btn-primary mx-2" data-toggle="modal" data-target="#createModal">+ Tambah
            Karyawan</button>
        <button type="button" class="btn btn-success mx-2" data-toggle="modal" data-target="#nikModal">Generate NIK
            Karyawan</button>
    </div> --}}

    {{-- Table --}}
    <table id="users-table" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Foto</th>
                <th>Nama</th>
                <th>Level</th>
                <th>Departemen</th>
                <th>Foto</th>
                <th>Tgl. Input</th>
                <th>Tgl. Foto</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    {{-- Modal Create --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="">
                        @csrf
                        <input type="number" class="form-control" id="id" name="id" type="hidden">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="level">Level</label>
                            <input type="text" class="form-control" id="level_karyawan" name="level" required>
                        </div>
                        <div class="form-group">
                            <label for="departemen">Departemen</label>
                            <input type="text" class="form-control" id="departemen_karyawan" name="departemen"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    {{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="userId" readonly>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama">
                        </div>
                        <div class="form-group">
                            <label for="level">Level</label>
                            <input type="text" class="form-control" id="level" name="level">
                        </div>
                        <div class="form-group">
                            <label for="departemen">Departemen</label>
                            <input type="text" class="form-control" id="departemen" name="departemen">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Modal Foto --}}
    {{-- <div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Foto Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="fotoform">
                        @csrf
                        <input type="hidden" id="userId">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama_foto" name="nama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="level">Level</label>
                            <input type="text" class="form-control" id="level_foto" name="level" readonly>
                        </div>
                        <div class="form-group">
                            <label for="departemen">Departemen</label>
                            <input type="text" class="form-control" id="departemen_foto" name="departemen" readonly>
                        </div>
                        <x-adminlte-input name="iNum" label="Number" id="no_foto" placeholder="number"
                            type="number" igroup-size="lg" min=1 max=10>
                        </x-adminlte-input>
                    </form>

                    <div class="mt-3" d-flex>
                        <div id="my_camera" style="margin-right: 20px;"></div>
                        <div id="result" class="mt-3"></div>
                    </div>

                    <button id="toggleWebcamBtn" class="btn btn-primary">Nyalakan Kamera</button>
                    <button id="captureBtn" class="btn btn-success">Ambil Gambar</button>
                    <button id="saveBtn" class="btn btn-info mt-3">Simpan Foto</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Modal Show Foto --}}
    <div class="modal fade" id="fotoShowModal" tabindex="-1" role="dialog" aria-labelledby="fotoShowModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Foto Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalShowFoto" src="" alt="Foto Karyawan" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Modal Generate NIK --}}
    {{-- <div class="modal fade" id="nikModal" tabindex="-1" role="dialog" aria-labelledby="nikModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nikModalLabel">Tambah Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="nikForm">
                        @csrf
                        <input type="hidden">
                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="date">Tanggal</label>
                                <input type="date" class="form-control" id="date_nik" name="date_nik" required>
                            </div>
                            <div class="col-md-4 col align-self-end form-group">
                                <button class="btn btn-primary" id="tampilkanBtn">Tampilkan</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="level">NIK Dimulai</label>
                                <input type="number" class="form-control" id="nikNumber" name="nikNumber">
                            </div>
                            <div class="col-md-4 col align-self-end form-group">
                                <button class="btn btn-success">Generate NIK</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="karyawanTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Level</th>
                                            <th>Departemen</th>
                                            <th>Foto</th>
                                            <th>Tgl Foto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dimuat di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

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
    {{-- CRUD --}}
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                autowidth: true,
                ajax: '{{ route('api.users') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'no_foto',
                        name: 'no_foto'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'workplace',
                        name: 'workplace'
                    },
                    {
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            var karyawanTable = $('#karyawanTable').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('api.karyawan.byDate') }}',
                    data: function(d) {
                        d.date_nik = $('#date_nik').val();

                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'workplace',
                        name: 'workplace'
                    },
                    {
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                rawColumns: ['foto'], // Pastikan 'foto' di sini  
                searching: false,
                paging: false,
                info: false,
                deferLoading: 0
            });

            // Event Listener untuk Tombol Tampilkan  
            $('#tampilkanBtn').on('click', function(e) {
                e.preventDefault();
                karyawanTable.ajax.reload();
            });

            const fotoModal = new bootstrap.Modal(document.getElementById('fotoShowModal'));
            const modalFoto = document.getElementById('modalShowFoto');

            $(document).on('click', '.foto-btn', function() {
                const fotoPath = $(this).data('foto-path');
                const fotoTitle = $(this).data('foto-title');

                modalFoto.src = fotoPath;
                document.getElementById('fotoModalLabel').textContent = fotoTitle;
                fotoModal.show();
            });

            //Create form submit
            // $('#createForm').on('submit', function(e) {
            //     e.preventDefault();
            //     var id = $('#userId').val();
            //     $.ajax({
            //         url: '/karyawan-baru/create',
            //         type: 'POST',
            //         data: $(this).serialize(),
            //         success: function(response) {
            //             table.ajax.reload();
            //         }
            //     });
            // });

            $(document).ready(function() {
                $('#karyawanForm').on('submit', function(event) {
                    event.preventDefault(); // Mencegah form dari submit biasa  
                    var id = $('#userId').val();
                    var formData = {
                        nik: $('#nik').val(),
                        nama: $('#nama').val(),
                        level: $('#level').val(),
                        workplace: $('#workplace').val(),
                        tempat_lahir: $('#tempat_lahir').val(),
                        tgl_lahir: $('#tgl_lahir').val(),
                        tgl_masuk: $('#tgl_masuk').val(),
                        _token: '{{ csrf_token() }}'
                    };
                    no_foto: $('#no_foto').val(),
                    console.log(formData);

                    // Ambil dataUri (jika ada)
                    var dataUri = $('#result img').attr('src'); // Ambil gambar dari elemen #result

                    // Kirim data ke server
                    if (dataUri && formData.no_foto) {
                        // Jika dataUri dan no_foto terisi, kirim ke dua tabel
                        $.ajax({
                            url: '{{ route('karyawan-baru.store') }}',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                // Jika data karyawan berhasil disimpan, simpan foto
                                if (response.success) {
                                    var karyawan_id = response
                                        .id; // Ambil ID karyawan yang baru dibuat
                                    $.ajax({
                                        url: '{{ route('api.karyawan.foto.store') }}', // Route untuk menyimpan foto
                                        type: 'POST',
                                        data: {
                                            karyawan_id: id,
                                            no_foto: formData.no_foto,
                                            image: dataUri,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            alert(
                                                'Data karyawan dan foto berhasil disimpan!'
                                                );
                                            $('#karyawanForm')[0]
                                                .reset(); // Reset form
                                            $('#result').html(
                                                ''
                                                ); // Kosongkan hasil gambar
                                            table.ajax
                                                .reload(); // Reload tabel jika menggunakan DataTables
                                        },
                                        error: function(xhr, status, error) {
                                            alert('Gagal menyimpan foto: ' +
                                                error);
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Gagal menyimpan data karyawan: ' + error);
                            }
                        });
                    } else {
                        // Jika dataUri atau no_foto tidak terisi, hanya kirim data karyawan
                        $.ajax({
                            url: '{{ route('karyawan-baru.store') }}',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                alert('Data karyawan berhasil disimpan!');
                                $('#karyawanForm')[0].reset(); // Reset form
                                table.ajax
                                    .reload(); // Reload tabel jika menggunakan DataTables
                            },
                            error: function(xhr, status, error) {
                                alert('Gagal menyimpan data karyawan: ' + error);
                            }
                        });
                    }

                    // $.ajax({
                    //     url: '{{ route('karyawan-baru.store') }}',
                    //     type: 'POST',
                    //     data: formData,
                    //     success: function(response) {
                    //         // Handle success  
                    //         alert('Karyawan created successfully!');
                    //         // Anda bisa mereset form atau melakukan redirect  
                    //         $('#karyawanForm')[0].reset();
                    //     },
                    //     error: function(xhr, status, error) {
                    //         // Handle error  
                    //         var errors = xhr.responseJSON.errors;
                    //         if (errors) {
                    //             $.each(errors, function(key, value) {
                    //                 alert(value[0]);
                    //             });
                    //         } else {
                    //             alert('An error occurred. Please try again.');
                    //         }
                    //     }
                    // });

                });
            });


            // Edit button click
            $('#users-table').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.get('/api/karyawan/' + id, function(data) {
                    $('#userId').val(data.id);
                    $('#nik').val(data.nik);
                    $('#nama').val(data.nama);
                    $('#level').val(data.level);
                    $('#workplace').val(data.workplace);
                    $('#tempat_lahir').val(data.tempat_lahir);
                    $('#tgl_lahir').val(data.tgl_lahir);
                    $('#tgl_masuk').val(data.tgl_masuk);
                    // $('#editModal').modal('show');
                });
            });

            // Update form submit
            $('#karyawanForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#userId').val();
                $.ajax({
                    url: '/api/karyawan/update/' + id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // $('#editModal').modal('hide');
                        table.ajax.reload();
                    }
                });
            });

            // Delete button click
            $('#users-table').on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: '/api/karyawan/delete/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            table.ajax.reload();
                        }
                    });
                }
            });

            // Foto button click
            $('#users-table').on('click', '.foto', function() {
                var id = $(this).data('id');
                $.get('/api/karyawan/' + id, function(data) {
                    $('#userId').val(data.id);
                    $('#nama_foto').val(data.nama);
                    $('#level_foto').val(data.level);
                    $('#departemen_foto').val(data.departemen);
                    $('#fotoModal').modal('show');
                });
            });

            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            let toggleWebcamBtn = document.getElementById('toggleWebcamBtn');
            let captureBtn = document.getElementById('captureBtn');
            let saveBtn = document.getElementById('saveBtn');
            let webcamActive = false;
            let dataUri = '';

            // Event Listener untuk Tombol On/Off Webcam
            toggleWebcamBtn.addEventListener('click', () => {
                if (webcamActive) {
                    Webcam.reset();
                    toggleWebcamBtn.textContent = 'Turn On Webcam';
                    captureBtn.style.display = 'none';
                    webcamActive = false;
                } else {
                    Webcam.attach('#my_camera');
                    toggleWebcamBtn.textContent = 'Turn Off Webcam';
                    captureBtn.style.display = 'block';
                    webcamActive = true;
                }
            });

            // Event Listener untuk Tombol Capture
            captureBtn.addEventListener('click', () => {
                Webcam.snap(function(uri) {
                    dataUri = uri;
                    // Tampilkan Hasil Gambar
                    document.getElementById('result').innerHTML = `<img src="${dataUri}" />`;
                    saveBtn.style.display = 'block';
                });
            });

            // Event Listener untuk Tombol Simpan
            saveBtn.addEventListener('click', () => {
                let karyawan_id = document.getElementById('userId').value;
                let no_foto = document.getElementById('no_foto').value;

                // Kirim ke Server
                fetch("{{ route('api.karyawan.foto.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            karyawan_id: parseInt(karyawan_id),
                            no_foto: parseInt(no_foto),
                            image: dataUri,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        table.ajax.reload();

                    })
                    .catch(error => console.error(error));
            });
        });
    </script>
@endpush
