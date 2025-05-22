<div class="row">
    <div class="col-lg-6">
        <div class="card card-info">
            <div class="card-header">
            <h3 class="card-title">Detail Fakultas</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form>
            <div class="card-body">

                @include('Master.fakultas.formdetailfakultas')

            </div>
            <!-- /.card-body -->
            </form>
        </div>
        <!-- /.card -->
    </div>

    <div class="col-lg-6">
        <div class="card card-info">
            <div class="card-header">
            <h3 class="card-title">Pejabat Fakultas</h3>
            </div>
            <div class="card-body">
                @foreach ($getPejabatPerFakultas as $value)
                    <div class="form-group">
                        <label for="{{ $value->jenis_jabatan_id }}">{{ $value->nama_jenis_jabatan }}</label>
                        <input type="text" name="{{ $value->jenis_jabatan_id }}" value="{{ $value->nama_dosen }}" class="form-control" readonly id="nama_fakultas" placeholder="Input Nama Fakultas">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('Master.fakultas.listprodi')
