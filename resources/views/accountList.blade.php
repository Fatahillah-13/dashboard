@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', '')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

@section('content_body')
    <div class="col" style="padding: 8px">
        <button type="button" class="btn btn-danger" id="deleteSelected">Hapus Data</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAccountModal">Tambah
            Account</button>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Account</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

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
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>No.</th>
                                <th>ID</th>
                                <th>User</th>
                                <th>email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = App\Models\User::get();
                            ?>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td><input type="checkbox" class="rowCheckbox" data-id="{{ $user->id }}"></td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->id ?? 'N/A' }}</td>
                                    <td>{{ $user->name ?? 'N/A' }}</td>
                                    <td>{{ $user->email ?? 'N/A' }}</td>
                                    <td><button class="btn btn-danger delete" data-id="{{ $user->id }}">Delete</button>
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
    {{-- Add Account Modal --}}
    <div class="modal fade" id="addAccountModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Account</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="accountForm" action="/account/store" method="Post">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" id="email" name="email" placeholder="name@example.com">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" id="name" name="name" placeholder="name">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" placeholder="Password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">+ Tambah User</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
                        url: "{{ route('deleteuser.selected') }}", // URL to send the request
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
        // $(document).ready(function() {
        //     // Create form submit
        //     $('#accountForm').on('submit', function(event) {
        //         event.preventDefault(); // Mencegah form dari submit biasa  

        //         // Buat payload JSON
        //         var payload = {
        //             name: $('#name').val(),
        //             email: $('#email').val(),
        //             password: $('#password').val(),
                    
        //         };

        //         $.ajax({
        //             url: '{{ route('account.create') }}',
        //             type: 'POST',
        //             data: payload,
        //             success: function(response) {
        //                 // Handle success
        //                 toastr.success('Data User telah disimpan');
        //                 // Anda bisa mereset form atau melakukan redirect  
        //                 $('#accountForm')[0].reset();
        //                 table.ajax.reload()
        //             },
        //             error: function(xhr, status, error) {
        //                 // Handle error  
        //                 var errors = xhr.responseJSON.errors;
        //                 if (errors) {
        //                     $.each(errors, function(key, value) {
        //                         alert(value[0]);
        //                     });
        //                 } else {
        //                     toastr.error('An error occurred. Please try again.');
        //                 }
        //             }
        //         });
        //     });
        // });
    </script>
@endpush
