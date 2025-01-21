@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <h1>Tambah Karyawan Baru</h1>
        <form action="{{ route('karyawan-baru.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">NIK:</label>
                <input type="number" class="form-control" id="nik" name="nik" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="level">Posisi:</label>
                <input type="text" class="form-control" id="level" name="level" required>
            </div>
            <div class="form-group">
                <label for="departemen">Departemen:</label>
                <input type="text" class="form-control" id="departemen" name="departemen" required>
            </div>
            <div class="form-group">
                <label for="nama">Tempat Lahir:</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
            </div>
            <div class="form-group">
                <label for="nama">Tgl. Lahir:</label>
                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
            </div>
            <div class="form-group">
                <label for="nama">Tgl. Masuk:</label>
                <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
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
