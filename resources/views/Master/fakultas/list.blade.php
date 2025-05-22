@extends('layouts.main')

@section('title') Fakultas @endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('headcontent') Data Fakultas @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-12">
        {{-- @include('auth._message') --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Fakultas</h3>
                @if (!empty($permissionAdd))
                    <a href="{{ url('master/fakultas/add') }}" class="btn btn-primary float-right btn-sm"><i class="fas fa-plus mr-1"></i> Add Fakultas</a>
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="jenisJabatan" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th style="width: 50px; text-center">#</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Jenjang</th>
                    <th>Singkatan Fakultas</th>
                    @if (!empty($permissionEdit) || !empty($permissionDelete) || !empty($permissionDetail))
                        <th>Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach ($getRecord as $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->nama_fakultas }}</td>
                            <td>{{ $value->status }}</td>
                            <td>{{ $value->nama_jenjang_didik }}</td>
                            <td>{{ $value->singkatan }}</td>
                            @if (!empty($permissionEdit) || !empty($permissionDelete) || !empty($permissionDetail))
                            <td>
                                @if (!empty($permissionDetail))
                                    <a href="{{ url('master/fakultas/detail/'.$value->id_fakultas) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Detail</a>
                                @endif
                                @if (!empty($permissionEdit))
                                    <a href="{{ url('master/fakultas/edit/'.$value->id_fakultas) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                @endif
                                @if (!empty($permissionDelete))
                                    {{-- <a href="{{ url('master/fakultas/delete/'.$value->id_fakultas) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</a> --}}
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="{{ $value->id_fakultas }}">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                @endif
                            </td>
                            @endif
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
  <!-- /.row -->
@endsection

@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ url('') }}/public/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ url('') }}/public/plugins/jszip/jszip.min.js"></script>
    <script src="{{ url('') }}/public/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ url('') }}/public/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ url('') }}/public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            $("#jenisJabatan").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#jenisJabatan_wrapper .col-md-6:eq(0)');

            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 2000,
                    timerProgressBar: true
                });
            @endif
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `fakultas/delete/${id}`;
                    }
                });
            });
        });
    </script>
@endsection
