@extends('layouts.main')

@section('title') Pengabdian @endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap4.min.css">
@endsection

@section('headcontent') Data Pengabdian @endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pengabdian</h3>
                @if (!empty($permissionAdd))
                    <a href="{{ url('doc/pengabdian/add') }}" class="btn btn-primary float-right btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Pengabdian
                    </a>
                @endif
            </div>
            <div class="card-body">
                <table id="pengabdianTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Penulis</th>
                            <th>Email</th>
                            <th>Judul</th>
                            <th>Tanggal Upload</th>
                            <th>Tahun</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
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
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

<script>
    $(document).ready(function() {
        const table = $('#pengabdianTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('doc/pengabdian') }}",
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'penulis', name: 'documents.penulis' },
                { data: 'email', name: 'documents.email' },
                { data: 'title', name: 'documents.title' },
                { data: 'upload_date', name: 'documents.upload_date' },
                { data: 'tahun_akademik', name: 'documents.tahun_akademik' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            fixedHeader: true,
            autoWidth: false
        });

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

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Dokumen pengabdian ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ url('doc/pengabdian/delete/') }}/${id}`;
                }
            });
        });
    });
</script>
@endsection
