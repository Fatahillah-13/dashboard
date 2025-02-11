@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}
@section('content_body')
    {{-- Form Tambah Pegawai --}}
    <div class="col-12">
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
                    <h3 class="text-center">Tambah Kandidat</h3>
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
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
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
                                <input type="number" class="form-control" id="no_foto" name="no_foto" required>
                            </div>
                        </div>
                        {{-- webcamjs --}}
                        <div class="col-12 mx-3 hidden" id="myDiv" style="padding: 16px 0px">
                            <div class="row">
                                <div id="my_camera" class="col-6">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" srcset=""
                                        style="width: 150px" height="150px">
                                </div>
                                <div id="preview" class="col-6">
                                    <img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" srcset=""
                                        style="width: 150px" height="150px">
                                </div>
                            </div>
                            <input type="hidden" id="imagePath" name="imagePath" value="">
                        </div>
                    </div>
                    <div class="col-12 mx-3">
                        <div class="row form-group" style="gap: 16px">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Kandidat karyawan</h3>
                <div class="card-tools">
                    <!-- Collapse Button -->
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                The body of the card
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    {{-- Table --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tabel Datar Kandidat</h3>

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
                <table class="table table-head-fixed text-nowrap" id="karyawanForm">
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
                    <tbody>
                        <?php
                        $karyawans = App\Models\KaryawanBaru::all();
                        ?>
                        @foreach ($karyawans as $index => $karyawan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
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
                                <td>{{ $karyawan->gambarKaryawan->created_at ?? 'Belum Foto' }}</td>
                                <!-- Menampilkan tgl_foto jika ada -->
                                <td>
                                    <a href="{{ route('api.users.update', $karyawan->id) }}"
                                        class="btn btn-warning">Edit</a>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        });
    </script>
    {{-- CRUD --}}
    {{-- <script>

        data_uri= "";
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
        });

        $(document).ready(function() {

            // Create form submit
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
                    console.log(formData);

                    $.ajax({
                        url: '{{ route('karyawan-baru.store') }}',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            // Handle success
                            toastr.success('Data Kandidat telah disimpan')
                            // Anda bisa mereset form atau melakukan redirect  
                            $('#karyawanForm')[0].reset();
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
            });


            // Update button click
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
                        toastr.success(
                            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
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
        });
    </script> --}}
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
        console.log(data_uri);


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

                console.log(payload); // Debugging

                $.ajax({
                    url: '{{ route('karyawan-baru.store') }}',
                    type: 'POST',
                    data: payload,
                    success: function(response) {
                        // Handle success
                        toastr.success('Data Kandidat telah disimpan');
                        // Anda bisa mereset form atau melakukan redirect  
                        $('#karyawanForm')[0].reset();
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

            // Update form submit
            // $('#karyawanForm').on('submit', function(e) {
            //     e.preventDefault();
            //     var id = $('#userId').val();
            //     $.ajax({
            //         url: '/api/karyawan/update/' + id,
            //         type: 'POST',
            //         data: $(this).serialize(),
            //         success: function(response) {
            //             // $('#editModal').modal('hide');
            //             toastr.success('Data berhasil diperbarui.');
            //             table.ajax.reload();
            //         }
            //     });
            // });

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
        });
    </script>
@endpush
