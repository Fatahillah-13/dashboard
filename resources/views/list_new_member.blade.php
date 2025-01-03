@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}

@section('content_body')
    {{-- PHP for Datatables Tool --}}
    {{-- @php
        $heads = [
            'ID',
            'Name',
            ['label' => 'Phone', 'width' => 40],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
        $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
        $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';

        $config = [
            'data' => [
                [22, 'John Bender', '+02 (123) 123456789', '<nobr>' . $btnEdit . $btnDelete . $btnDetails . '</nobr>'],
                [
                    19,
                    'Sophia Clemens',
                    '+99 (987) 987654321',
                    '<nobr>' . $btnEdit . $btnDelete . $btnDetails . '</nobr>',
                ],
                [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>' . $btnEdit . $btnDelete . $btnDetails . '</nobr>'],
            ],
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];
    @endphp --}}
    {{-- Table --}}
    <table id="users-table" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Level</th>
                <th>Departemen</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    {{-- Modal Edit --}}
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="userId">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="level">Level</label>
                            <input type="text" class="form-control" id="level" name="level" required>
                        </div>
                        <div class="form-group">
                            <label for="departemen">Departemen</label>
                            <input type="text" class="form-control" id="departemen" name="departemen" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    {{-- View --}}
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('api.users') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'departemen',
                        name: 'departemen'
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
    {{-- Edit --}}
    <script>
        // Edit button click
        $('#users-table').on('click', '.edit', function() {
            var id = $(this).data('id');
            $.get('/api/karyawan/' + id, function(data) {
                $('#userId').val(data.id);
                $('#nama').val(data.nama);
                $('#level').val(data.level);
                $('#departemen').val(data.departemen);
                $('#editModal').modal('show');
            });
        });

        // Update form submit
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#userId').val();
            $.ajax({
                url: '/api/karyawan/update/' + id,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    alert(response.success);
                }
            });
        });
    </script>
    {{-- Delete --}}
    <script>
        // Delete button click
        $('#users-table').on('click', '.delete', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '/api/karyawan/delete/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        table.ajax.reload();
                        alert(response.success);
                    }
                });
            }
        });
    </script>
@endpush
