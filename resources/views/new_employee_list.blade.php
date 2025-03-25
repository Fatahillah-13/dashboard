@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', '')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')


{{-- Content body: main page content --}}
@section('content_body')
    {{-- Buttons --}}
    <div class="col" style="padding: 8px">
        <button type="button" class="btn btn-danger" id="deleteSelected">Hapus Data</button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#nikModal">Generate
            NIK</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printIdCardModal">Cetak
            ID
            Card</button>
    </div>
    {{-- /.Buttons --}}
    {{-- Table --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 400px;">
                        <input type="text" name="table_search" id="searchInput" class="form-control float-right"
                            placeholder="Search">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 800px;">
                <table class="table table-bordered table-striped table-head-fixed text-nowrap" id="employeetable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
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
                        $karyawans = App\Models\KaryawanBaru::whereIn('status', [1, 2])->whereHas('gambarKaryawan')->get();
                        ?>
                        @foreach ($karyawans as $index => $karyawan)
                            <tr>
                                <td><input type="checkbox" class="rowCheckbox" data-id="{{ $karyawan->id }}"></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $karyawan->nik ?? '-' }}</td>
                                <td>{{ $karyawan->gambarKaryawan->no_foto ?? 'N/A' }}</td>
                                <!-- Menampilkan no_foto jika ada -->
                                <td>{{ $karyawan->nama }} @if($karyawan->status == 2) (Tidak Lanjut) @endif</td>
                                <td>{{ $karyawan->posisi->level ?? 'N/A' }}</td> <!-- Menampilkan nama level -->
                                <td>{{ $karyawan->departemen->job_department ?? 'N/A' }}</td>
                                <!-- Menampilkan nama departemen -->
                                <td>
                                    @if ($karyawan->gambarKaryawan && $karyawan->gambarKaryawan->foto)
                                        <!-- Cek apakah gambarKaryawan ada dan path-nya ada -->
                                        <img src="{{ asset('storage/' . $karyawan->gambarKaryawan->foto) }}" alt="Foto"
                                            width="100">
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
                                    <button class="btn btn-warning btn-sm edit" data-id="{{ $karyawan->id }}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete"
                                        data-id="{{ $karyawan->id }}">Delete</button>
                                    <button class="btn btn-success btn-sm statusbtn" id="statusBtn"
                                        data-id="{{ $karyawan->id }}">Batal
                                        Masuk</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    {{-- /.Table --}}
    {{-- Modal Generate NIK --}}
    <div class="modal fade" id="nikModal" tabindex="-1" role="dialog" aria-labelledby="nikModalLabel" aria-hidden="true">
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
                                <label for="date">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="date_nik" name="date_nik" required>
                            </div>
                            <div class="col-md-4 col align-self-end form-group">
                                <button class="btn btn-primary" id="tampilkanBtn">Tampilkan</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <label for="numdate">Prefix</label>
                                <input type="text" class="form-control" id="prefix" class="prefix" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="nikNumber">NIK Dimulai</label>
                                <input type="number" class="form-control" id="niknumber" name="nikNumber">
                            </div>
                            <div class="col-md-4 col align-self-end form-group">
                                <button class="btn btn-success" id="generateBtn">Generate NIK</button>
                            </div>
                        </div>
                        <div class="row">
                            <table id="karyawanNikTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Level</th>
                                        <th>Departemen</th>
                                        <th>No Foto</th>
                                        <th>Foto</th>
                                        <th>Tgl Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data Goes Here --}}
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- /.Modal Generate NIK --}}
    {{-- Modal Print ID Card --}}
    <div class="modal fade" id="printIdCardModal" tabindex="-1" role="dialog" aria-labelledby="printIdCardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nikModalLabel">Cetak ID Card</h5>
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
                                <label for="startworkdate">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="startworkdate" name="startworkdate"
                                    required>
                            </div>
                            <div class="col-md-4 col align-self-end form-group">
                                <button class="btn btn-primary" id="showEmployeeFilterbtn">Tampilkan</button>
                            </div>
                        </div>
                        <div class="row">
                            <table id="employeePrintTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectPrintAll" class="selectPrintAll"></th>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Level</th>
                                        <th>Departemen</th>
                                        <th>No Foto</th>
                                        <th>Foto</th>
                                        <th>CTPAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data Goes Here --}}
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="printIdCardsButton">Print ID Card</button>
                </div>
            </div>
        </div>
    </div>
    {{-- /.Modal Generate NIK --}}
    {{-- Template Foto --}}
    <div class="col-8" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Print ID Card</h3>
                <div class="card-tools">
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="background-color: #F2F5F7">
                <div class="col-12">
                </div>
                {{-- change form --}}
                <div class="print" id="print">
                    <div class="it-parent" id="it-parent">
                        <div class="bg-template" id="bg-template">
                            <img class="it-icon" alt=""
                                src="{{ asset('assets/img/template_idcard_staffup.png') }}">
                        </div>
                        <div class="photo-parent">
                            <div class="preview" id="preview">
                                <img id="photo" alt="" src="{{ asset('assets/img/picture_icon.png') }}"
                                    width="630px" height="770px">
                            </div>
                            <div class="fullname-parent">
                                <b class="fullname" id="fullname">FULLNAME</b>
                                <div class="department" id="department">DEPARTMENT</div>
                                <div class="joblevel" id="joblevel">LEVEL</div>
                                <div class="nikid" id="nikid">NIK ID</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- /.Template Foto --}}
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="{{ asset('css/new_employee_list_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/idcardAll_style.css') }}">
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
    <script src="https://cdn.datatables.net/select/3.0.0/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/3.0.0/js/select.dataTables.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="lodash.js"></script>
    <script>
        $(function() {
            // Fungsi untuk memilih semua checkbox
            $('#selectAll').on('click', function() {
                var checked = this.checked;
                $('.rowCheckbox').each(function() {
                    this.checked = checked;
                });
            });

            // Fungsi untuk mengatur checkbox "select all" berdasarkan checkbox individu
            $('#employeetable tbody').on('change', '.rowCheckbox', function() {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                }
                if ($('.rowCheckbox:checked').length === $('.rowCheckbox').length) {
                    $('#selectAll').prop('checked', true);
                }
            });

            // Event handler for delete button
            $('#deleteSelected').on('click', function() {
                var selectedIds = [];
                $('.rowCheckbox:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });
                if (selectedIds.length > 0) {
                    // Perform delete operation with selectedIds
                    console.log('Deleting IDs:', selectedIds);
                    // Add your AJAX call or delete logic here
                    $.ajax({
                        url: "{{ route('delete.selected') }}", // URL to send the request
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            alert(response.success); // Show success message
                            location.reload(); // Reload the page to see changes
                        },
                        error: function(xhr) {
                            alert('An error occurred while deleting records.'); // Handle error
                        }
                    });
                } else {
                    alert('Please select at least one checkbox to delete.');
                }
            });
        });
    </script>
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
        $(document).ready(function() {

            // Update button click
            $('#employeetable').on('click', '.edit', function() {
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
                    $('#editModal').modal('show');
                });
            });

            // Delete button click
            $('#employeetable').on('click', '.delete', function() {
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

            $('#employeetable').on('click', '.statusbtn', function() {
                var nik = $(this).data('id')
            .toString(); // Get the NIK from the button's data attribute and convert to string

                $.ajax({
                    url: '/karyawan/updatestatus', // Update with your endpoint
                    method: 'POST',
                    data: {
                        employees: [{
                            nik: nik, // Use the NIK directly
                            status: 2, // Set the status you want to update
                        }],
                    },
                    success: function(response) {
                        toastr.success('Status berhasil diperbarui.'); // Show success message
                    },
                    error: function(xhr) {
                        console.error(xhr); // Log the error for debugging
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message :
                            'Terjadi kesalahan saat memperbarui status.';
                        toastr.error(errorMessage); // Show error message
                    }
                });
            });

        });
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
    <script>
        $(document).ready(function() {
            $('#tampilkanBtn').on('click', function(e) {
                e.preventDefault(); // Mencegah form submit

                // Ambil nilai tanggal dari input
                var date = $('#date_nik').val();

                // Lakukan permintaan AJAX
                $.ajax({
                    url: '/karyawan/filter', // Ganti dengan URL endpoint Anda
                    method: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#karyawanNikTable tbody').empty();

                        // Tambahkan data ke tabel
                        response.data.forEach(function(karyawan, index) {
                            $('#karyawanNikTable tbody').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${karyawan.nik || '-'}</td>
                                    <td>${karyawan.nama}</td>
                                    <td>${karyawan.posisi.level || 'N/A'}</td>
                                    <td>${karyawan.departemen.job_department || 'N/A'}</td>
                                    <td>${karyawan.gambarkaryawan.no_foto || 'N/A'}</td>
                                    <td>
                                        ${karyawan.gambarkaryawan && karyawan.gambarkaryawan.foto ? 
                                            `<img src="/storage/${karyawan.gambarkaryawan.foto}" alt="Foto" width="100">` : 
                                            'Belum foto'}
                                    </td>
                                    <td>${karyawan.gambarkaryawan ? formatDate(karyawan.gambarkaryawan.created_at) : 'Belum Foto'}</td>
                                </tr>
                            `);
                        });

                        $('#prefix').val(formatDateToMMYY(date));
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('Terjadi kesalahan saat mengambil data.');
                    }
                });
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0'); // Mendapatkan hari dan menambahkan 0 di depan jika perlu
            const month = String(date.getMonth() + 1).padStart(2,
                '0'); // Mendapatkan bulan (0-11) dan menambahkan 0 di depan
            const year = date.getFullYear(); // Mendapatkan tahun

            return `${day}/${month}/${year}`; // Mengembalikan format ddmmyyyy
        }

        function formatDateToMMYY(date) {
            const d = new Date(date);
            const month = String(d.getMonth() + 1).padStart(2, '0'); // Menambahkan 1 karena bulan dimulai dari 0
            const year = String(d.getFullYear()).slice(-2); // Mengambil dua digit terakhir dari tahun
            return `${year}${month}0`;
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#generateBtn').on('click', function(e) {
                e.preventDefault(); // Mencegah form submit

                var tglmasuk = $('#date_nik').val();
                var prefix = $('#prefix').val();
                var newnik = $('#niknumber').val();

                $.ajax({
                    url: '/karyawan/changenik', // Ganti dengan URL endpoint Anda
                    method: 'POST',
                    data: {
                        tglmasuk: tglmasuk,
                        prefix: prefix,
                        newnik: newnik
                    },
                    success: function(response) {
                        toastr.success('Data berhasil diperbarui.');
                        console.log(prefix + newnik);

                    },
                    error: function(xhr) {
                        console.error(xhr);
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Terjadi kesalahan saat memperbarui NIK.';
                        toastr.error(errorMessage);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#showEmployeeFilterbtn').on('click', function(e) {
                e.preventDefault(); // Mencegah form submit

                // Ambil nilai tanggal dari input
                var date = $('#startworkdate').val();

                // Lakukan permintaan AJAX
                $.ajax({
                    url: '/karyawan/filter', // Ganti dengan URL endpoint Anda
                    method: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#employeePrintTable tbody').empty();

                        // Tambahkan data ke tabel
                        response.data.forEach(function(karyawan, index) {
                            // Cek panjang nama karyawan
                            var namaStyle = karyawan.nama.length > 17 ?
                                'style="color: red; text-transform: uppercase;"' :
                                'style="text-transform: uppercase;"';
                            var editable = karyawan.nama.length > 17 ?
                                'contenteditable="true"' : '';
                            $('#employeePrintTable tbody').append(`
                                <tr>
                                    <td><input type="checkbox" class="rowPrintCheckbox" name="checkbox" id="rowPrintCheckbox${index}" checked></td>
                                    <td>${index + 1}</td>
                                    <td>${karyawan.nik || '-'}</td>
                                    <td ${namaStyle} ${editable}>${karyawan.nama}</td>
                                    <td>${karyawan.posisi.level || 'N/A'}</td>
                                    <td>${karyawan.departemen.job_department || 'N/A'}</td>
                                    <td>${karyawan.gambarkaryawan.no_foto || 'N/A'}</td>
                                    <td>
                                        ${karyawan.gambarkaryawan && karyawan.gambarkaryawan.foto ? 
                                            `<img src="/storage/${karyawan.gambarkaryawan.foto}" alt="Foto" width="100">` : 
                                            'Belum foto'}
                                    </td>
                                    <td><input type="checkbox" class="rowCtpatCheckbox" name="checkbox" id="rowCtpatCheckbox${index}"></td>
                                </tr>
                            `);
                        });
                        $('#selectPrintAll').prop('checked', true);

                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('Terjadi kesalahan saat mengambil data.');
                    }
                });
            });

            $('#printIdCardsButton').on('click', async function() {
                const {
                    jsPDF
                } = window.jspdf;

                // Create a new PDF document with custom size (width: 53 mm, height: 85 mm)
                const pdf = new jsPDF('p', 'mm', [53, 85]);

                // Get all employee data from the table
                const employees = [];
                $('#employeePrintTable tbody tr').each(function() {
                    const row = $(this);
                    const checkbox = row.find('.rowPrintCheckbox');
                    const ctpatcheckbox = row.find('.rowCtpatCheckbox').is(':checked');
                    if (checkbox.is(':checked')) {
                        const nik = row.find('td:nth-child(3)').text();
                        const name = row.find('td:nth-child(4)').text();
                        const position = row.find('td:nth-child(5)').text();
                        const department = row.find('td:nth-child(6)').text();
                        const photoSrc = row.find('img').attr('src');
                        const ctpat = ctpatcheckbox;
                        employees.push({
                            photoSrc,
                            name,
                            department,
                            position,
                            nik,
                            ctpat,
                        });
                    }
                });

                // Create ID cards for each employee
                await generateIDCards(employees, pdf);

                // Update the status in database
                $.ajax({
                    url: '/karyawan/updatestatus', // Ganti dengan URL endpoint Anda
                    method: 'POST',
                    data: {
                        employees: employees
                    },
                    success: function(response) {
                        toastr.success('Data berhasil diperbarui.');
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr
                            .responseJSON.message :
                            'Terjadi kesalahan saat memperbarui status.';
                        toastr.error(errorMessage);
                    }
                });

                // After all ID cards are generated, save the PDF
                pdf.save('employee_id_cards.pdf');
            });

            // Fungsi untuk memilih semua checkbox
            $('#selectPrintAll').on('click', function() {
                var checked = this.checked;
                $('.rowPrintCheckbox').each(function() {
                    this.checked = checked;
                });
            });

            // Fungsi untuk mengatur checkbox "select all" berdasarkan checkbox individu
            $('#employeePrintTable tbody').on('change', '.rowPrintCheckbox', function() {
                if (!this.checked) {
                    $('#selectPrintAll').prop('checked', false);
                }
                if ($('.rowPrintCheckbox:checked').length === $('.rowPrintCheckbox').length) {
                    $('#selectPrintAll').prop('checked', true);
                }
            });
        });

        async function generateIDCards(employees, pdf) {
            for (const [index, employee] of employees.entries()) {
                // Clone the ID card template
                const idCard = $('#print').clone().removeAttr('id').css('display', 'block');
                idCard.find('#photo').attr('src', employee.photoSrc || '');
                idCard.addClass('print');
                idCard.addClass('it-parent');
                idCard.addClass('bg-template');
                idCard.addClass('it-icon');
                idCard.addClass('photo-parent');
                idCard.addClass('preview');
                idCard.addClass('fullname-parent');
                idCard.find('.fullname').text(employee.name.toUpperCase());
                idCard.find('.department').text(employee.department.toUpperCase());
                idCard.find('.joblevel').text(employee.position.toUpperCase());
                idCard.find('.nikid').text(employee.nik);

                // Set the background template based on the CTPAT
                await setBackgroundTemplate(employee, idCard);

                // Ensure the ID card is in the DOM
                $('body').append(idCard);

                try {
                    await captureAndAddToPDF(idCard, employee, index, employees.length, pdf);
                } catch (error) {
                    console.error(`Error processing ID card for ${employee.name}:`, error);
                } finally {
                    idCard.remove(); // Clean up the DOM after processing
                }
            }
        }

        async function captureAndAddToPDF(idCard, employee, index, totalEmployees, pdf) {
            const canvas = await html2canvas(idCard[0]);
            const imgData = canvas.toDataURL('image/png');

            // Add the image to the PDF at position (0, 0)
            pdf.addImage(imgData, 'PNG', 0, 0, 53, 85); // Custom size for ID card

            // Add a new page if there are more employees
            if (index < totalEmployees - 1) {
                pdf.addPage(); // Add a new page for the next ID card
            }
        }

        function setBackgroundTemplate(employee, idCard) {
            return new Promise((resolve) => {
                const bgTemplate = idCard.find('#bg-template'); // Find bg-template in the cloned idCard
                bgTemplate.empty(); // Clear previous background
                // bgTemplate.html('<img class="it-icon" src="{{ asset('assets/ctpat/qip.jpg') }}" alt="">');

                if (!employee.ctpat && employee.position !== 'Operator') {
                    bgTemplate.html(
                        '<img class="it-icon" src="{{ asset('assets/img/template_idcard_staffup.png') }}" alt="">'
                    );
                } else if (employee.ctpat && employee.department === 'HRD') {
                    bgTemplate.html('<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}" alt="">');
                } else if (employee.ctpat && employee.department === 'SEA') {
                    bgTemplate.html('<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}" alt="">');
                } else if (employee.ctpat && employee.department === 'IT') {
                    bgTemplate.html('<img class="it-icon" src="{{ asset('assets/ctpat/it.jpg') }}" alt="">');
                } else if (employee.ctpat && employee.department === 'QIP') {
                    bgTemplate.html('<img class="it-icon" src="{{ asset('assets/ctpat/qip.jpg') }}" alt="">');
                } else if (employee.ctpat && employee.position === 'Operator') {
                    bgTemplate.html(
                        '<img class="it-icon" src="{{ asset('assets/ctpat/production.jpg') }}" alt="">');
                } else if (!employee.ctpat && employee.position === 'Operator') {
                    bgTemplate.html(
                        '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}" alt="">'
                    );
                } else {
                    bgTemplate.html(
                        '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}" alt="">'
                    );
                }

                // Resolve the promise after the background is set
                resolve();
            });
        }
    </script>
    <script>
        var $rows = $('#employeetable tr');
        $('#searchInput').keyup(debounce(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        }, 300));

        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this,
                    args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };
    </script>
    <script>
        $(document).on('click', '.statusbtn', function() {
            var karyawanId = $(this).data('id');

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    url: '/karyawan/statusmasuk/' + karyawanId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token for security
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Optionally, you can refresh the page or update the UI
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            }
        });
    </script>
@endpush
