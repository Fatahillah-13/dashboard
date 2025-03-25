@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', '')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

@section('content_body')
    {{-- Tabel --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Account</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" id="searchInput" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 500px;">
                    <table class="table table-head-fixed text-nowrap" id="listTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIK</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Tempat Lahir</th>
                                <th>Level</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $karyawans = App\Models\KaryawanBaru::with('gambarKaryawan', 'posisi', 'departemen')->where('status', [3])->get();
                            ?>
                            @foreach ($karyawans as $index => $karyawan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $karyawan->nik ?? 'N/A' }}</td>
                                    <td>
                                        @if ($karyawan->gambarKaryawan && $karyawan->gambarKaryawan->foto)
                                            <!-- Cek apakah gambarKaryawan ada dan path-nya ada -->
                                            <img src="{{ asset('storage/' . $karyawan->gambarKaryawan->foto) }}"
                                                alt="Foto" width="100">
                                        @else
                                            Belum foto
                                        @endif
                                    </td>
                                    <td>{{ $karyawan->nama ?? 'N/A' }}</td>
                                    <td>{{ $karyawan->tempat_lahir ?? 'N/A' }}</td>
                                    <td>{{ $karyawan->posisi->level ?? 'N/A' }}</td> <!-- Menampilkan nama level -->
                                    <td>{{ $karyawan->departemen->job_department ?? 'N/A' }}</td>
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
    </div>
@stop

@push('css')
@endpush

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
        var $rows = $('#listTable tr');
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
@endpush
