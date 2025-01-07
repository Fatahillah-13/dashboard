@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}

@section('content_body')
    {{-- Table --}}
    <table id="photo-table" class="table table-bordered">
        <thead>
            <tr>
                <th>ID </th>
                <th>ID Karyawan</th>
                <th>Foto</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
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
    <script>
        $(document).ready(function() {
            $('#photo-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('api.photo') }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'karyawan_id',
                        name: 'karyawan_id'
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
        });
    </script>
@endpush
