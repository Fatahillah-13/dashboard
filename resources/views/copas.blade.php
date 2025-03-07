<html>

<head>
    <title>Print ID Card</title>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .it-parent {
                width: 627px;
                position: relative;
                background-color: #fff;
                height: 1005px;
                overflow: hidden;
                text-align: center;
                font-size: 40px;
                color: #000;
                font-family: Roboto;
            }

            .photo-parent {
                position: absolute;
                top: 212px;
                left: calc(50% - 215.5px);
                width: 431px;
                height: 631px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                gap: 36px;
            }

            .photo-icon {
                width: 277px;
                position: relative;
                height: 355px;
                object-fit: cover;
            }

            .fullname-parent {
                align-self: stretch;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                gap: 21px;
            }

            .fullname {
                align-self: stretch;
                position: relative;
                letter-spacing: 3px;
                display: inline-block;
                height: 47px;
                flex-shrink: 0;
            }

            .department,
            .joblevel,
            .nikid {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>' +
    printContents + '</body>

</html>

<script>
    data_uri = "";
    // Configure the webcam
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    // Function to take a snapshot
    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            // Show the preview
            document.getElementById('preview').src = data_uri;
            document.getElementById('preview').style.display = 'block';
            document.getElementById('preview').innerHTML = '<img src="' + data_uri + '"/>';

            // Simpan data URI ke dalam input hidden
            document.getElementById('imagePath').value = data_uri; // Menyimpan data URI ke input hidden

        });
    }


    $(document).ready(function() {
        const checkbox = document.getElementById('myCheckbox');
        const myDiv = document.getElementById('myDiv');
        const shuterBtn = document.getElementById('captureBtn');

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                myDiv.classList.remove('hidden');
                shuterBtn.classList.remove('hidden');
                // Attach the webcam to the div
                Webcam.attach('#my_camera');
            } else {
                myDiv.classList.add('hidden');
                shuterBtn.classList.add('hidden');
                Webcam.reset('#my_camera');
            }
        });

        // Create form submit
        $('#karyawanForm').on('submit', function(event) {
            event.preventDefault(); // Mencegah form dari submit biasa  
            // Prepare form data
            var imageData = $('#imagePath').val(); // Ambil data URI dari input hidden

            // Buat payload JSON
            var payload = {
                nik: $('#nik').val(),
                nama: $('#nama').val(),
                level: $('#level').val(),
                workplace: $('#workplace').val(),
                tempat_lahir: $('#tempat_lahir').val(),
                tgl_lahir: $('#tgl_lahir').val(),
                tgl_masuk: $('#tgl_masuk').val(),
                no_foto: $('#no_foto').val(),
                foto: imageData // Tambahkan data gambar
            };

            $.ajax({
                url: '{{ route('karyawan-baru.store') }}',
                type: 'POST',
                data: payload,
                success: function(response) {
                    // Handle success
                    toastr.success('Data Kandidat telah disimpan');
                    // Anda bisa mereset form atau melakukan redirect  
                    $('#karyawanForm')[0].reset();
                    table.ajax.reload()
                },
                error: function(xhr, status, error) {
                    // Handle error  
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            alert(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        });

        // Update button click
        // $('#users-table').on('click', '.edit', function() {
        //     var id = $(this).data('id');
        //     $.get('/api/karyawan/' + id, function(data) {
        //         $('#userId').val(data.id);
        //         $('#nik').val(data.nik);
        //         $('#nama').val(data.nama);
        //         $('#level').val(data.level);
        //         $('#workplace').val(data.workplace);
        //         $('#tempat_lahir').val(data.tempat_lahir);
        //         $('#tgl_lahir').val(data.tgl_lahir);
        //         $('#tgl_masuk').val(data.tgl_masuk);
        //         // $('#editModal').modal('show');
        //     });
        // });

        // Delete button click
        $('#karyawanTable').on('click', '.delete', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '/api/karyawan/delete/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        toastr.success('Data berhasil dihapus.');
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        $('#nama_edit, #tgl_lahir_edit').on('input change', function() {
            var nama = $('#nama_edit').val();
            var tgl_lahir = $('#tgl_lahir_edit').val();

            if (nama && tgl_lahir) {
                $.ajax({
                    url: '{{ route('autocomplete') }}', // Ganti dengan URL endpoint Anda
                    type: 'GET',
                    data: {
                        nama: nama,
                        tgl_lahir: tgl_lahir
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(index, karyawan) {
                                $('#id_candidate').val(karyawan.id);
                                $('#tempat_lahir_edit').val(karyawan.tempat_lahir);
                                $('#no_foto_edit').val(karyawan.gambarkaryawan
                                    .no_foto);
                                $('#level_edit').val(karyawan.posisi.id).trigger(
                                    'change');
                                $('#workplace_edit').val(karyawan.departemen.id)
                                    .trigger('change');
                                $('#tgl_masuk_edit').val(karyawan.tgl_masuk)
                                    .trigger('change');
                                if (karyawan.gambarkaryawan && karyawan
                                    .gambarkaryawan.foto) {
                                    $('#preview_edit').html(
                                        '<img src="{{ asset('storage/') }}' +
                                        '/' + karyawan.gambarkaryawan.foto +
                                        '" alt="Foto" width="150" height="150">'
                                    );
                                } else {
                                    $('#preview_edit').html(
                                        '<img src="{{ asset('assets/img/picture_icon.png') }}" alt="picture" width="150" height="150">'
                                    );
                                }


                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mengambil data:', error);
                    }
                });
            }
        });
    });
</script>
<script>
    // print pdf
    const promises = employees.map((employee, index) => {
        // Clone the ID card template
        const idCard = $('#idCardTemplate').clone().removeAttr('id').css('display', 'block');
        idCard.find('.photo-icon').attr('src', employee.photoSrc || '');
        idCard.find('.fullname').text(employee.name);
        idCard.find('.department').text(employee.department);
        idCard.find('.joblevel').text(employee.position);
        idCard.find('.nikid').text(employee.nik);

        var templateLevel = employee.position;
        var templateDepartment = employee.department;
        var templateCtpat = employee.ctpat;

        // Set the background template based on the CTPAT
        if (templateCtpat == false && templateLevel !== 'Operator') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/img/template_idcard_staffup.png') }}' +
                '" alt="">'
            );
            console.log(index, employee.position, employee.department, employee.ctpat);
        } else if (templateCtpat == true && templateDepartment === 'HRD') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}' +
                '" alt="">'
            );
            // console.log('CTPAT HRD ');
            console.log(index, employee.position, employee.department, employee.ctpat);

        } else if (templateCtpat == true && templateDepartment === 'SEA') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}' +
                '" alt="">'
            );
            // console.log('CTPAT SEA');
            console.log(index, employee.position, employee.department, employee.ctpat);
        } else if (templateCtpat == true && templateDepartment === 'IT') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/ctpat/it.jpg') }}' +
                '" alt="">'
            );
            // console.log('CTPAT IT');
            console.log(index, employee.position, employee.department, employee.ctpat);

        } else if (templateCtpat == true && templateDepartment === 'QIP') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/ctpat/qip.jpg') }}' +
                '" alt="">'
            );
            // console.log('CTPAT QIP');
            console.log(index, employee.position, employee.department, employee.ctpat);

        } else if (templateCtpat == true && templateLevel === 'Operator') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/ctpat/production.jpg') }}' +
                '" alt="">'
            );
            // console.log('CTPAT Production');
            console.log(index, employee.position, employee.department, employee.ctpat);

        } else if (templateCtpat == false && templateLevel === 'Operator') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}' +
                '" alt="">'
            );
            // console.log('Operator');
            console.log(index, employee.position, employee.department, employee.ctpat);

        } else {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}' +
                '" alt="">'
            );
            console.log(index, employee.position, employee.department, employee.ctpat);
        }

        // Ensure the ID card is in the DOM
        $('body').append(idCard);

        // Use html2canvas to capture the ID card
        return html2canvas(idCard[0]).then(canvas => {
            const imgData = canvas.toDataURL('image/png');

            console.log(imgData);

            // Add the image to the PDF at position (0, 0)
            pdf.addImage(imgData, 'PNG', 0, 0, 55, 85); // Custom size for ID card

            idCard.remove(); // Clean up the DOM after capturing

            // Add a new page if there are more employees
            if (index < employees.length - 1) {
                pdf.addPage(); // Add a new page for the next ID card
            }

        });
    });

    // After all ID cards are generated, save the PDF
    Promise.all(promises).then(() => {
        pdf.save('employee_id_cards.pdf');
    });

    // Set the background template based on the CTPAT
    if (!employee.ctpat && employee.position !== 'Operator') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/img/template_idcard_staffup.png') }}" alt="">'
        );
        checkCheck = 'staffup';
    } else if (employee.ctpat && employee.department === 'HRD') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}" alt="">'
        );
        checkCheck = 'HRD CTPAT';
    } else if (employee.ctpat && employee.department === 'SEA') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}" alt="">'
        );
        checkCheck = 'SEA CTPAT';
    } else if (employee.ctpat && employee.department === 'IT') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/ctpat/it.jpg') }}" alt="">'
        );
        checkCheck = 'IT CTPAT';
    } else if (employee.ctpat && employee.department === 'QIP') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/ctpat/qip.jpg') }}" alt="">'
        );
        checkCheck = 'QIP CTPAT';
    } else if (employee.ctpat && employee.position === 'Operator') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/ctpat/production.jpg') }}" alt="">'
        );
        checkCheck = 'Opt CTPAT';
    } else if (!employee.ctpat && employee.position === 'Operator') {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}" alt="">'
        );
        checkCheck = 'Opt';
    } else {
        $('#bg-template').html(
            '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}" alt="">'
        );
        checkCheck = 'Opt 2';
    }
</script>

<script>
    $(document).ready(function() {
        $('#printIdCardsButton').on('click', async function() {
            const {
                jsPDF
            } = window.jspdf;

            // Create a new PDF document with custom size (width: 55 mm, height: 85 mm)
            const pdf = new jsPDF('p', 'mm', [55, 85]);

            // Get all employee data from the table
            const employees = [];
            $('#employeePrintTable tbody tr').each(function() {
                const row = $(this);
                const checkbox = row.find('.rowPrintCheckbox');
                const ctpatcheckbox = row.find('.rowCtpatCheckbox').is(':checked');
                if (checkbox.is(':checked')) {
                    const nik = row.find('td:nth-child(3)').text();
                    const name = row.find('td:nth-child(4)').text();
                    const position = row.find('td:nth-child(5)').text();
                    const department = row.find('td:nth-child(6)').text();
                    const photoSrc = row.find('img').attr('src');
                    const ctpat = ctpatcheckbox;
                    employees.push({
                        photoSrc,
                        name,
                        department,
                        position,
                        nik,
                        ctpat,
                    });
                }
            });

            console.log(employees);

            // Create ID cards for each employee
            await generateIDCards(employees, pdf);

            // After all ID cards are generated, save the PDF
            pdf.save('employee_id_cards.pdf');
        });
    });

    async function generateIDCards(employees, pdf) {
        for (const [index, employee] of employees.entries()) {
            // Clone the ID card template
            const idCard = $('#idCardTemplate').clone().removeAttr('id').css('display', 'block');
            idCard.find('.photo-icon').attr('src', employee.photoSrc || '');
            idCard.find('.fullname').text(employee.name);
            idCard.find('.department').text(employee.department);
            idCard.find('.joblevel').text(employee.position);
            idCard.find('.nikid').text(employee.nik);

            // Set the background template based on the CTPAT
            setBackgroundTemplate(employee);

            // Ensure the ID card is in the DOM
            $('body').append(idCard);

            try {
                await captureAndAddToPDF(idCard, employee, index, employees.length, pdf);
            } catch (error) {
                console.error(`Error processing ID card for ${employee.name}:`, error);
            } finally {
                idCard.remove(); // Clean up the DOM after processing
            }
        }
    }

    async function captureAndAddToPDF(idCard, employee, index, totalEmployees, pdf) {
        const canvas = await html2canvas(idCard[0]);
        const imgData = canvas.toDataURL('image/png');

        console.log(`Captured image data for ${employee.name}:`, imgData);

        // Add the image to the PDF at position (0, 0)
        pdf.addImage(imgData, 'PNG', 0, 0, 55, 85); // Custom size for ID card

        // Add a new page if there are more employees
        if (index < totalEmployees - 1) {
            pdf.addPage(); // Add a new page for the next ID card
        }
    }

    function setBackgroundTemplate(employee) {
        $('#bg-template').empty(); // Clear previous background

        if (!employee.ctpat && employee.position !== 'Operator') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/img/template_idcard_staffup.png') }}" alt="">');
        } else if (employee.ctpat && employee.department === 'HRD') {
            $('#bg-template').html('<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}" alt="">');
        } else if (employee.ctpat && employee.department === 'SEA') {
            $('#bg-template').html('<img class="it-icon" src="{{ asset('assets/ctpat/sea_hrd.jpg') }}" alt="">');
        } else if (employee.ctpat && employee.department === 'IT') {
            $('#bg-template').html('<img class="it-icon" src="{{ asset('assets/ctpat/it.jpg') }}" alt="">');
        } else if (employee.ctpat && employee.department === 'QIP') {
            $('#bg-template').html('<img class="it-icon" src="{{ asset('assets/ctpat/qip.jpg') }}" alt="">');
        } else if (employee.ctpat && employee.position === 'Operator') {
            $('#bg-template').html('<img class="it-icon" src="{{ asset('assets/ctpat/production.jpg') }}" alt="">');
        } else if (!employee.ctpat && employee.position === 'Operator') {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}" alt="">');
        } else {
            $('#bg-template').html(
                '<img class="it-icon" src="{{ asset('assets/img/Template ID Card Operator Hitam.png') }}" alt="">');
        }
    }
</script>
