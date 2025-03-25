@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                    <?php
                    $kandidat = App\Models\KaryawanBaru::whereIn('status', [1])->get();
                    ?>
                    @foreach ($kandidat as $kandidat_index => $k)
                    <?php $kandidat_index++; ?>
                    @endforeach
                    <span class="info-box-text">Jumlah Kandidat</span>
                    <span class="info-box-number">{{ $kandidat_index }} Peserta</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <?php
            $foto = App\Models\KaryawanBaru::whereIn('status', [1])
                ->whereHas('gambarKaryawan')
                ->get();
            ?>
            @foreach ($foto as $foto_index => $k)
            <?php $foto_index++; ?>
            @endforeach
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Sudah Foto</span>
                    <span class="info-box-number">{{ $foto_index }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <?php
            $cetak = App\Models\KaryawanBaru::whereIn('status', [3])->get();
            ?>
            @foreach ($cetak as $cetak_index => $k)
            <?php $cetak_index++; ?>
            @endforeach
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Sudah Cetak</span>
                    <span class="info-box-number">{{ $cetak_index }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <?php
            $out = App\Models\KaryawanBaru::whereIn('status', [2])->get();
            ?>
            @foreach ($out as $out_index => $k)
            <?php $out_index++; ?>
            @endforeach
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-comments"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Kandidat Out</span>
                    <span class="info-box-number">{{$out_index}}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
