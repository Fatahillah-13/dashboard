@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', '')
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
                                            ->whereIn('status', [1])
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
                                                        '" alt="Foto" width="360" height="420">'
                                                    );
                                                } else {
                                                    $('#preview').html(
                                                        '<img src="{{ asset('assets/img/pict_template.jpg') }}" alt="picture" width="150" height="150">'
                                                    );
                                                }
                                                $('#nikid').text(data[0].nik); // Fill the department div
                                                $('#fullname').text(data[0].nama.toUpperCase()); // Fill the department div
                                                $('#joblevel').text(data[0].posisi.level
                                                    .toUpperCase()); // Fill the department div
                                                $('#department').text(data[0].departemen.job_department
                                                    .toUpperCase()); // Fill the department div
                                                // console.log(data[0].level + ' ' + typeof(data[0].level));
                                                // Cek Masih Belum Bisa
                                                if (data[0].level === 1) {
                                                    // console.log('operator');
                                                    $('#bg-template').html(
                                                        '<img class="photo-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}' +
                                                        '" alt="">'
                                                    );
                                                } else {
                                                    // console.log('Staff Up');
                                                    $('#bg-template').html(
                                                        '<img class="photo-icon" src="{{ asset('assets/img/template_idcard_staffup.png') }}' +
                                                        '" alt="">'
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
                        {{-- change form --}}
                        <div class="print" id="print">
                            <div class="it-parent" id="it-parent">
                                <div class="bg-template" id="bg-template">
                                    <img class="it-icon" alt=""
                                        src="{{ asset('assets/img/template_idcard_staffup.png') }}">
                                </div>
                                <div class="photo-parent">
                                    <div class="preview" id="preview">
                                        <img class="photo-icon" alt=""
                                            src="{{ asset('assets/img/picture_icon.png') }}">
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
                                <input type="text" class="form-control" id="nama_show" name="nama_show"
                                    onchange="changeName()">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="ctpat_check"
                                        onclick="selectCTPAT()">
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
                                <button type="button" id="printButton" class="btn btn-block bg-gradient-success">Cetak
                                    ID
                                    Card</button>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <script>
                    function changeName() {
                        var name = document.getElementById('nama_show').value;
                        $('#fullname').text(name.toUpperCase()); // Fill the department div
                        if (name.split(' ').length > 2) {
                            $('#fullname').css('font-size', '36px');
                        } else {
                            $('#fullname').css('font-size', '');
                        }
                    }

                    function selectCTPAT() {
                        var ctpat = document.getElementById('ctpat_check');
                        var department = document.getElementById('department').innerText;
                        var joblevel = document.getElementById('joblevel').innerText;
                        console.log(department);

                        if (ctpat.checked) {
                            $('.fullname-parent').css('padding-top', '24px');
                        }

                        if (ctpat.checked && department === 'HRD') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked && department === 'SEA') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked && department === 'IT') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/it.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked && department === 'EXIM') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/exim.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked && department === 'FINISH GOOD') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/finishgood.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked && department === 'QIP') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/qip.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked && joblevel === 'OPERATOR') {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/production.jpg') }}' +
                                '" alt="">'
                            );
                        } else if (ctpat.checked) {
                            $('#bg-template').html(
                                '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}' +
                                '" alt="">'
                            );
                        }
                    }
                </script>
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
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
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
        // document.getElementById('printButton').addEventListener('click', function() {
        //     var printContents = document.getElementById('print').innerHTML;
        //     var originalContents = document.body.innerHTML;
        //     document.body.innerHTML = printContents;
        //     window.print();
        //     document.body.innerHTML = originalContents;
        //     location.reload();
        // });
    </script>
    <script>
        const container = document.getElementById('print');
        const printButton = document.getElementById('printButton');
        const name = document.getElementById('nama_show');
        const nik = document.getElementById('nik_show');
        const {
            jsPDF
        } = window.jspdf;

        printButton.addEventListener("click", async () => {
            // Use html2canvas to capture the container
            html2canvas(container).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', [53, 85]);
                // Set the dimensions for the PDF
                const pdfWidth = 53; // 5.3 cm
                const pdfHeight = 85; // 8.5 cm
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save(`${name.value}_${nik.value}.pdf`);
            });

            // Update the status in database
            $.ajax({
                url: '/karyawan/updatestatus', // Update with your endpoint
                method: 'POST',
                data: {
                    employees: [{
                        nik: nik
                        .value, // Assuming you want to update the status for this employee
                        // Add other fields if necessary
                    }]
                },
                success: function(response) {
                    toastr.success('Status berhasil diperbarui.');
                },
                error: function(xhr) {
                    console.error(xhr);
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                        .responseJSON.message : 'Terjadi kesalahan saat memperbarui status.';
                    toastr.error(errorMessage);
                }
            });

        });
    </script>
@endpush
