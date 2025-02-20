@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}
@section('content_body')
    <form action="">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Print ID Card</h3>
                        <div class="card-tools">
                            <!-- Buttons, labels, and many other things can be placed here! -->
                            <!-- Here is a label for example -->
                            {{-- <span class="badge badge-primary">Cetak</span> --}}
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="background-color: #F2F5F7">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="nama_edit2">Pilih Karyawan</label>
                                <select id="nama_edit2" name="nama_edit2" class="select-department form-control nama_edit2"
                                    onchange="GetDataKaryawan();" required>
                                    <option value="0">Pilih</option>
                                    @php
                                        $karyawans_belum = App\Models\KaryawanBaru::with(
                                            'gambarkaryawan',
                                            'posisi',
                                            'departemen',
                                        )
                                            ->whereHas('gambarKaryawan')
                                            ->whereNotNull('nik')
                                            ->get();
                                    @endphp
                                    @foreach ($karyawans_belum as $karyawan)
                                        <option value="{{ $karyawan->id }}">Nama : {{ $karyawan->nama }} | NIK :
                                            {{ $karyawan->nik }} | Tempat Lahir :
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
                                                $('#nik_show').val(data[0].nik);
                                                $('#nama_show').val(data[0].nama);
                                                $('#lvl_show').val(data[0].level).trigger('change');
                                                $('#dpt_show').val(data[0].workplace).trigger('change');
                                                $('#no_foto_show').val(data[1].no_foto).trigger('change');
                                                if (data[1].foto) {
                                                    $('#preview').html(
                                                        '<img src="{{ asset('storage/') }}' +
                                                        '/' + data[1].foto +
                                                        '" alt="Foto" width="280" height="320">'
                                                    );
                                                } else {
                                                    $('#preview').html(
                                                        '<img src="{{ asset('assets/img/pict_template.jpg') }}" alt="picture" width="150" height="150">'
                                                    );
                                                }

                                                $('#nikid').text(data[0].nik); // Fill the department div
                                                $('#fullname').text(data[0].nama.toUpperCase()); // Fill the department div
                                                $('#joblevel').text(data[0].posisi.level.toUpperCase()); // Fill the department div
                                                $('#department').text(data[0].departemen.job_department.toUpperCase()); // Fill the department div
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Gagal mengambil data:', error);
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="print-card">
                            <div class="photo-parent">
                                <div class="preview" id="preview">
                                    <img class="photo-icon" alt="" src="{{ asset('assets/img/foto_icon.jpg') }}">
                                </div>
                                <div class="fullname-parent">
                                    <div class="fullname" id="fullname">FULLNAME</div>
                                    <div class="department" id="department">DEPARTMENT</div>
                                    <div class="joblevel" id="joblevel">LEVEL</div>
                                    <div class="nikid" id="nikid">NIK</div>
                                </div>
                            </div>
                            <img class="template-id-card-staff-up-2" alt=""
                                src="{{ asset('assets/img/Template ID Card Staff Up 2.png') }}">
                            <img class="template-id-card-staff-up-1" alt=""
                                src="{{ asset('assets/img/Template ID Card Staff Up 1.png') }}">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    {{-- <div class="card-footer">
                    </div> --}}
                    <!-- /.card-footer -->
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data karyawan</h3>
                        <div class="card-tools">
                            <!-- Buttons, labels, and many other things can be placed here! -->
                            <!-- Here is a label for example -->
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                            $karyawans_belum = App\Models\KaryawanBaru::whereHas('gambarKaryawan')
                                ->whereNotNull('nik')
                                ->get();
                        @endphp
                        <div class="col md-6">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nofoto" class="form-label">No. Foto</label>
                                        <input type="text" class="form-control" id="no_foto_show" name="nofoto_edit"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="nik_show" name="nik_edit" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="form-label">Nama Cetak</label>
                                <input type="text" class="form-control" id="nama_show" name="nama_show" readonly>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="ctpat_check">
                                    <label class="form-check-label" for="exampleCheck1">CTPAT</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php
                                $posisi = App\Models\Posisi::all();
                                ?>
                                <label for="posisi">Level</label>
                                <select name="lvl_show" id="lvl_show" class="select-level form-control"
                                    disabled="disabled">
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
                                <select name="dpt_show" id="dpt_show" class="select-department form-control"
                                    disabled="disabled">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($department as $departemen)
                                        <option value="{{ $departemen->id }}">{{ $departemen->job_department }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" id="printButton" class="btn btn-block bg-gradient-success">Cetak ID
                                    Card</button>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/idcard_style.css') }}">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function() {
                // Show the print div
                var printDiv = document.getElementById('print');
                printDiv.style.display = 'block';

                // Print the div
                window.print();

                // Hide the print div again after printing
                printDiv.style.display = 'none';
            });
        });
    </script>
@endpush
