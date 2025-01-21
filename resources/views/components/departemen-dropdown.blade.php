<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    <select name="workplace" id="workplace" class="form-control" required>
        <option value="">Pilih Departemen</option>
        @foreach ($departemen as $departemen)
            <option value="{{ $departemen->id }}">{{ $departemen->workplace }}</option>
        @endforeach
    </select>

</div>
