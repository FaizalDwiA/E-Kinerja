// Custom scriptsfor all pages
import "../js/sb-admin-2.min.js";

$(document).ready(function () {
    // Fungsi untuk menambahkan atau menghapus kelas berdasarkan lebar layar
    function tambahkanKelasBerdasarkanLebarLayar() {
        var elemen = $('#accordionSidebar');
        if ($(window).width() <= 600) {
            elemen.addClass('toggled');
        } else {
            elemen.removeClass('toggled');
        }
    }

    // Panggil fungsi saat halaman dimuat dan saat ukuran layar berubah
    tambahkanKelasBerdasarkanLebarLayar(); // Panggil sekali saat halaman dimuat
    $(window).resize(tambahkanKelasBerdasarkanLebarLayar); // Panggil saat ukuran layar berubah
});