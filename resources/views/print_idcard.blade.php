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
                    <div class="card-body">
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
                        <div class="template-id-card-staff-up-1-parent">
                            <div class="print" id="image-container">
                                <img class="template-id-card-staff-up-1" id="image-1" alt=""
                                    src="{{ asset('assets/Template ID Card Operator Hitam.jpg') }}">
                                <img class="template-id-card-staff-up-1" id="image-2" alt=""
                                    src="{{ asset('assets/Template ID Card Staff Up.jpg') }}">
                                <img class="photo-icon" alt="" src="{{ asset('assets/img/picture_icon.png') }}">
                                <div class="fullname" id="fullname">Fatahillah Abid A.</div>
                                <div class="department" id="department">HRD</div>
                                <div class="nikid" id="nikid">2412074308</div>
                                <div class="joblevel" id="joblevel">STAFF</div>
                            </div>
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
                            <div class="form-group">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik_show" name="nik_edit" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="form-label">Nama Cetak</label>
                                <input type="text" class="form-control" id="nama_show" name="nama_show"
                                    placeholder="Optional">
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
                                <button type="button" class="btn btn-block bg-gradient-success">Cetak ID Card</button>
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

            document.getElementById('lvl_show').addEventListener('change', function() {
                var selectedValue = this.value;
                var image1 = document.getElementById('image-1');
                var image2 = document.getElementById('image-2');
                var imageContainer = document.getElementById('image-container');

                console.log('selectedValue');

                // Sembunyikan semua gambar terlebih dahulu
                image1.style.display = 'none';
                image2.style.display = 'none';
                imageContainer.style.display = 'none';

                // Tampilkan gambar berdasarkan nilai yang dipilih
                if (selectedValue == 1) {
                    image1.style.display = 'block';
                    imageContainer.style.display = 'block';
                } else {
                    image2.style.display = 'block';
                    imageContainer.style.display = 'block';
                }
            });

        });
    </script>
@endpush
