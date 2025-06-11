@extends('layouts.main')

@section('title') Skripsi @endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap4.min.css">
@endsection

@section('headcontent') Data Skripsi @endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Skripsi</h3>
                @if (!empty($permissionAdd))
                    <a href="{{ url('doc/skripsi/add') }}" class="btn btn-primary float-right btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Skripsi
                    </a>
                @endif
            </div>
            <div class="card-body">
                <table id="skripsiTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Tanggal Upload</th>
                            <th>Tahun Akademik</th>
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
        const startIndex = parseInt(localStorage.getItem("skripsiPageStart")) || 0;

        const table = $('#skripsiTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true, //Menyimpan page, search, sort, length
            ajax: {
                url: "{{ url('doc/skripsi') }}",
                type: 'GET',
                dataSrc: function (json) {
                    console.log('🔍 JSON total records:', json.recordsTotal);
                    return json.data;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'documents.title' },
                { data: 'nim', name: 'm_riwayat_pendidikan_mhs.nim' },
                { data: 'nama_mahasiswa', name: 'm_mahasiswa.nama_mahasiswa' },
                { data: 'nama_program_studi', name: 'm_riwayat_pendidikan_mhs.nama_program_studi' },
                { data: 'upload_date', name: 'documents.upload_date' },
                { data: 'tahun_akademik', name: 'documents.tahun_akademik' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            fixedHeader: true,
            autoWidth: false,
            // displayStart: startIndex
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

        // Simpan posisi halaman saat klik detail
        // $(document).on('click', '.btn-detail', function () {
        //     const pageStart = table.page.info().start;
        //     localStorage.setItem("skripsiPageStart", pageStart);
        // });

        // Hapus posisi halaman jika user me-refresh langsung (tanpa klik tombol detail)
        if (!document.referrer || !document.referrer.includes('/doc/skripsi/detail/')) {
            // localStorage.removeItem("skripsiPageStart");
            table.state.clear();
        }

        // Konfirmasi hapus dokumen skripsi
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Dokumen skripsi ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ url('doc/skripsi/delete/') }}/${id}`;
                }
            });
        });
    });
    </script>

@endsection
