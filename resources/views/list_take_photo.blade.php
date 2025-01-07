@extends('layouts.app')

{{-- Customize layout sections --}}
@section('plugins.Datatables', true)
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}
@section('content_body')
    <div class="container">
        <h1>Ambil Foto Karyawan</h1>

        <x-karyawan-dropdown />
        <x-adminlte-input name="iNum" label="Number" id="no_foto" placeholder="number" type="number" igroup-size="lg" min=1 max=10>
            <x-slot name="appendSlot">
                <div class="input-group-text bg-dark">
                    <i class="fas fa-hashtag"></i>
                </div>
            </x-slot>
        </x-adminlte-input>

        <div class="mt-3" d-flex>
            <div id="my_camera" style="margin-right: 20px;"></div>
            <div id="result" class="mt-3"></div>
        </div>

        <button id="toggleWebcamBtn" class="btn btn-primary">Nyalakan Kamera</button>
        <button id="captureBtn" class="btn btn-success">Ambil Gambar</button>
        <button id="saveBtn" class="btn btn-info mt-3">Simpan Foto</button>

    </div>
@stop

@push('css')
@endpush

@push('js')
    <script langguage="Javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script>
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        let toggleWebcamBtn = document.getElementById('toggleWebcamBtn');
        let captureBtn = document.getElementById('captureBtn');
        let saveBtn = document.getElementById('saveBtn');
        let webcamActive = false;
        let dataUri = '';

        // Event Listener untuk Tombol On/Off Webcam
        toggleWebcamBtn.addEventListener('click', () => {
            if (webcamActive) {
                Webcam.reset();
                toggleWebcamBtn.textContent = 'Turn On Webcam';
                captureBtn.style.display = 'none';
                webcamActive = false;
            } else {
                Webcam.attach('#my_camera');
                toggleWebcamBtn.textContent = 'Turn Off Webcam';
                captureBtn.style.display = 'block';
                webcamActive = true;
            }
        });

        // Event Listener untuk Tombol Capture
        captureBtn.addEventListener('click', () => {
            Webcam.snap(function(uri) {
                dataUri = uri;
                // Tampilkan Hasil Gambar
                document.getElementById('result').innerHTML = `<img src="${dataUri}" />`;
                saveBtn.style.display = 'block';
            });
        });

        // Event Listener untuk Tombol Simpan
        saveBtn.addEventListener('click', () => {
            let karyawan_id = document.getElementById('karyawanSelect').value;
            let no_foto = document.getElementById('no_foto').value;


            // Kirim ke Server
            fetch("{{ route('api.karyawan.foto.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        karyawan_id: parseInt(karyawan_id),
                        no_foto: parseInt(no_foto),
                        image: dataUri,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => console.error(error));
        });
    </script>
@endpush
