const flashData = $('.flash-data').data('flashdata');
const flashDataError = $('.flash-data2').data('flashdata2');

// Notifikasi Sukses
if (flashData) {
    Swal.fire({
        title: 'Berhasil!',
        text: flashData,
        icon: 'success',
        width: '340px',              // Ukuran kompak khusus layar mobile
        padding: '1.25em',
        confirmButtonColor: '#123e87', // Warna Biru Muhammadiyah
        confirmButtonText: '<i class="fa fa-check"></i> Mengerti',
        customClass: {
            popup: 'rounded-4 shadow-lg',
            confirmButton: 'btn btn-primary px-4 py-2 rounded-pill fw-bold'
        },
        buttonsStyling: false       // Matikan style bawaan agar class Bootstrap (btn btn-primary) jalan
    });
}

// Notifikasi Error / Peringatan
if (flashDataError) {
    Swal.fire({
        title: 'Perhatian!',        // Jauh lebih elegan daripada "Error 404"
        text: flashDataError,
        icon: 'warning',            // Pakai ikon warning (kuning) agar tidak terlalu intimidatif
        width: '340px',             // Ukuran kompak khusus layar mobile
        padding: '1.25em',
        confirmButtonColor: '#d33', 
        confirmButtonText: '<i class="fa fa-times"></i> Tutup',
        customClass: {
            popup: 'rounded-4 shadow-lg',
            confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-bold'
        },
        buttonsStyling: false
    });
}