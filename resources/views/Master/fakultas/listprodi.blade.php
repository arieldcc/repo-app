<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Program Studi</h3>
                @if (!empty($permissionAddProdi))
                    <a href="{{ url('master/fakultas/addpeprodi/'.$getRecord->id_fakultas) }}" class="btn btn-primary float-right btn-sm"><i class="fas fa-plus mr-1"></i> Add Prodi</a>
                @endif
            </div>
            <div class="card-body">
                <table id="prodi" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th style="width: 50px; text-center">#</th>
                        <th>Kode Program Studi </th>
                        <th>Nama Program Studi</th>
                        <th>Status</th>
                        <th>Jenjang Pendidikan</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($getProdiFakultas as $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->kode_program_studi }}</td>
                            <td>{{ $value->nama_program_studi }}</td>
                            <td>{{ $value->status }}</td>
                            <td>{{ $value->nama_jenjang_pendidikan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
