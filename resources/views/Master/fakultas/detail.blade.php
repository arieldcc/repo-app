@extends('layouts.main')

@section('title') Fakultas @endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('headcontent') Detail Fakultas @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="detail-fakultas-tab" data-toggle="pill" href="#detail-fakultas" role="tab" aria-controls="detail-fakultas" aria-selected="true">Fakultas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pimpinan-fakultas-tab" data-toggle="pill" href="#pimpinan-fakultas" role="tab" aria-controls="pimpinan-fakultas" aria-selected="false">Pejabat Fakultas</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="program-studi-tab" data-toggle="pill" href="#program-studi" role="tab" aria-controls="program-studi" aria-selected="false">Program Studi</a>
                    </li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="detail-fakultas" role="tabpanel" aria-labelledby="detail-fakultas-tab">

                        @include('Master.fakultas.detailfakultas')

                    </div>
                    <div class="tab-pane fade" id="pimpinan-fakultas" role="tabpanel" aria-labelledby="pimpinan-fakultas-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                @include('Master.fakultas.listpimpinan')
                            </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="program-studi" role="tabpanel" aria-labelledby="program-studi-tab">

                        @include('master.fakultas.listprodi')

                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

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
            $("#pimpinanFakultas").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#pimpinanFakultas_wrapper .col-md-6:eq(0)');

            $("#prodi").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#prodi_wrapper .col-md-6:eq(0)');

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

        document.querySelectorAll('.delete-pejabat').forEach(button => {
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
                        window.location.href = `deletepejabat/${id}`;
                    }
                });
            });
        });

        $(document).ready(function () {
            // Mengatur tab yang aktif berdasarkan URL hash
            if (window.location.hash) {
                var activeTab = window.location.hash;
                $('#custom-tabs-one-tab a[href="' + activeTab + '"]').tab('show');
            }

            // Menambahkan listener untuk menangani perubahan tab
            $('#custom-tabs-one-tab a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        });

    </script>
@endsection
