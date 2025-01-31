@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <style>
        .dataTables_wrapper {
            width: 100%;
        }

        table.dataTable {
            width: auto;
            min-width: 100%;
        }

        th,
        td {
            width: auto;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            border-bottom: 2px solid #ddd;
        }

        /* Memastikan lebar header dan kolom sama */
        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        .dataTables_scrollBody {
            width: 100% !important;
        }

        .dataTables_scrollHead table,
        .dataTables_scrollBody table {
            width: 100% !important;
        }
    </style>
    @hasSection('content_header_title')
        {{-- <h1 class="text-muted">
            @yield('content_header_title')
            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1> --}}
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
    @yield('content_body')
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add your common script logic here...
            $('.js-example-basic-single').select2();
        });
    </script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
    <style type="text/css">
        {{-- You can add AdminLTE customizations here --}}
        /*
                                    .card-header {
                                        border-bottom: none;
                                    }
                                    .card-title {
                                        font-weight: 600;
                                    }
                                    */
    </style>
@endpush
