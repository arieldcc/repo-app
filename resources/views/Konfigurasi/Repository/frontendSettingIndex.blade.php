@extends('layouts.main')

@section('title') FrontEnd Setting @endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('headcontent') Konfigurasi FrontEnd Setting @endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar FrontEnd Setting</h3>
                @if (!empty($permissionAdd))
                    <a href="{{ url('conf-repo/frontend-setting/add') }}" class="btn btn-primary float-right btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Setting
                    </a>
                @endif
            </div>
            <div class="card-body">
                <table id="frontendSettingTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Halaman</th>
                            <th>Tag Line</th>
                            <th>Footer Text</th>
                            <th>Versi</th>
                            <th>Status</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#frontendSettingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('conf-repo/frontend-setting') }}",
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'site_name', name: 'site_name' },
                { data: 'site_tagline', name: 'site_tagline' },
                { data: 'footer_text', name: 'footer_text' },
                { data: 'version', name: 'version' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
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

        // Konfirmasi hapus dokumen tesis
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Dokumen tesis ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ url('conf-repo/frontend-setting/delete/') }}/${id}`;
                }
            });
        });

    });

    $(document).on('change', '.toggle-status', function () {
        const id = $(this).data('id');
        const status = $(this).is(':checked');

        $.ajax({
            url: "{{ url('conf-repo/frontend-setting/update-status') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                status: status
            },
            success: function (response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#frontendSettingTable').DataTable().ajax.reload(null, false);
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memperbarui status.',
                });
            }
        });
    });

</script>
@endsection
