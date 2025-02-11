<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
    <select name="level" id="level" class="select-level form-control" required>
        <option value="">Pilih Posisi</option>
        @foreach ($posisi as $posisi)
            <option value="{{ $posisi->id }}">{{ $posisi->level }}</option>
        @endforeach
    </select>

</div>
