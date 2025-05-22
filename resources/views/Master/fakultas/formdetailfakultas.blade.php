<div class="form-group">
    <label for="nama_fakultas">Nama Fakultas</label>
    <input type="text" name="nama_fakultas" value="{{ $getRecord->nama_fakultas }}" class="form-control" readonly id="nama_fakultas" placeholder="Input Nama Fakultas">
</div>

<div class="form-group clearfix">
    <label>Status Fakultas</label><br>

    <!-- Aktif Radio Button -->
    <div class="icheck-success d-inline">
        <input disabled type="radio" name="status" value="A" id="radioSuccess1"
            {{ $getRecord->status == 'A' ? 'checked' : '' }}>
        <label for="radioSuccess1">Aktif</label>
    </div>

    <!-- Non-Aktif Radio Button -->
    <div class="icheck-success d-inline">
        <input disabled type="radio" name="status" value="N" id="radioSuccess2"
            {{ $getRecord->status == 'N' ? 'checked' : '' }}>
        <label for="radioSuccess2">Non-Aktif</label>
    </div>
</div>

<div class="form-group">
    <label for="singkatan">Singkatan</label>
    <input readonly type="text" name="singkatan" value="{{ $getRecord->singkatan }}" class="form-control" required id="singkatan" placeholder="Input Nama Jenis Jabatan">
</div>

<div class="form-group">
    @if (!empty($getRecord->logo))
        <div class="mt-3">
            <p>Logo Saat Ini:</p>
            <img src="{{ url('/public/storage') . '/' . $getRecord->logo }}" alt="Logo Fakultas" class="img-thumbnail" style="max-width: 150px;">
        </div>
    @endif
</div>
