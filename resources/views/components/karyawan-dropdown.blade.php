<div>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
    <select id="karyawanSelect" class="form-control">
        <option value="">Pilih Karyawan</option>
        @foreach ($karyawans as $karyawan)
            <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
        @endforeach
    </select>
</div>
