@extends('layouts.main')

@section('title') Role @endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('headcontent') Data Role @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('auth._message')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Role</h3>
                @if (!empty($permissionAdd))
                    <a href="{{ url('panel/role/add') }}" class="btn btn-primary float-right btn-sm">Add</a>
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th style="width: 50px; text-center">#</th>
                    <th>Name</th>
                    <th>Date</th>
                    @if (!empty($permissionEdit) || !empty($permissionDelete))
                        <th>Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach ($getRecord as $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->name }}</td>
                            <td>{{ $value->created_at }}</td>
                            <td>
                                @if (!empty($permissionEdit))
                                    <a href="{{ url('panel/role/edit/'.$value->id) }}" class="btn btn-info btn-sm">Edit</a>
                                @endif
                                @if (!empty($permissionDelete))
                                    <a href="{{ url('panel/role/delete/'.$value->id) }}" class="btn btn-danger btn-sm">Delete</a>
                                @endif
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

    <!-- Page specific script -->
    <script>
        $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        });
    </script>
@endsection
