<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Fakultas</h3>
        @if (!empty($permissionAdd))
            <a href="{{ url('master/fakultas/addpejabat/'.$getRecord->id_fakultas) }}" class="btn btn-primary float-right btn-sm"><i class="fas fa-plus mr-1"></i> Add Pejabat</a>
        @endif
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <table id="pimpinanFakultas" class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th style="width: 50px; text-center">#</th>
            <th>Jabatan</th>
            <th>Nama Pejabat</th>
            <th>Periode Jabatan</th>
            <th>SK Jabatan</th>
            <th>Status</th>
            @if (!empty($permissionEdit) || !empty($permissionDelete) || !empty($permissionDetail))
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
            @foreach ($getPejabatFakultas as $valuepejabatFakultas)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $valuepejabatFakultas->nama_jenis_jabatan }}</td>
                    <td>{{ $valuepejabatFakultas->nama_dosen }}</td>
                    <td>{{ $valuepejabatFakultas->tanggal_mulai }} s/d {{ $valuepejabatFakultas->tanggal_selesai }}</td>
                    <td>{{ $valuepejabatFakultas->no_sk }}</td>
                    <td>{{ $valuepejabatFakultas->status }}</td>
                    @if (!empty($permissionEdit) || !empty($permissionDelete) || !empty($permissionDetail))
                    <td>
                        @if (!empty($permissionEdit))
                            {{-- <a href="{{ url('master/fakultas/detail/'.$valuepejabatFakultas->id_pejabat_fakultas) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Detail</a> --}}
                        @endif
                        @if (!empty($permissionEdit))
                            <a href="{{ url('master/fakultas/editpejabat/'.$valuepejabatFakultas->id_pejabat_fakultas) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        @endif
                        @if (!empty($permissionDelete))
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-pejabat" data-id="{{ $valuepejabatFakultas->id_pejabat_fakultas }}">
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
