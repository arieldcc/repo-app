@extends('layouts.main')

@section('title') Mahasiswa @endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap4.min.css">
@endsection
@section('headcontent') Data Mahasiswa @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-12">
        {{-- @include('auth._message') --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Mahasiswa</h3>
                @if (!empty($permissionAdd))
                    <a href="{{ url('master/mahasiswa/add') }}" class="btn btn-primary float-right btn-sm"><i class="fas fa-plus mr-1"></i> Add Mahasiswa</a>
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="" data-toggle="modal" data-target="#filter-mhs" class="btn btn-sm btn-info" style="width: auto; margin-bottom: 20px;"><i class="fas fa-filter"></i> Filter</a>
                <div class="clear"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="mahasiswaTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Agama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Program Studi</th>
                                    <th>Status</th>
                                    <th>Angkatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @include('Master.Mahasiswa.filter')
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
    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(document).ready(function() {
            $('#mahasiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('master/mahasiswa') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_mahasiswa', name: 'nama_mahasiswa' },
                    { data: 'nim_mahasiswa', name: 'm_riwayat_pendidikan_mhs.nim' },
                    { data: 'jenis_kelamin', name: 'jenis_kelamin' },
                    { data: 'agama_mahasiswa', name: 'm_mahasiswa.nama_agama' },
                    { data: 'tanggal_lahir', name: 'tanggal_lahir' },
                    { data: 'nama_program_studi', name: 'm_riwayat_pendidikan_mhs.nama_program_studi' },
                    { data: 'status_mahasiswa', name: 't_mahasiswa_lulus_do.nama_jenis_keluar' },
                    { data: 'angkatan', name: 'rev_semester.id_tahun_ajaran' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [[1, 'asc']],
                fixedHeader: true,
                autoWidth: false,
            });

            // SweetAlert Delete Confirmation
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `{{ url('master/mahasiswa/delete/') }}/${id}`;
                    }
                });
            });
        });
        </script>
@endsection
