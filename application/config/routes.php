<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'Auth/login';
$route['default_controller'] = 'Beranda';
$route['404_override'] = 'Page/not_found_404';
$route['translate_uri_dashes'] = FALSE;

/* 
| -------------------------------------------------------------------------
| CUSTOM ROUTES
| -------------------------------------------------------------------------
*/

/**----------------------------------------------------
 * Auth
-------------------------------------------------------**/
$route['daftar'] = 'Auth/register';
$route['lupa-password'] = 'Auth/forgot_password';
$route['masuk'] = 'Auth/login';
$route['keluar'] = 'Auth/logout';
$route['reset-kata-sandi/(:any)'] = 'Auth/reset_password/$1';

$route['backoffice/data-diri'] = 'Auth/profile';
$route['backoffice/ganti-password'] = 'Auth/change_password';


/**----------------------------------------------------
 * Dasbor
-------------------------------------------------------**/
$route['backoffice/dasbor'] = 'backoffice/admin/Dasbor';
$route['backoffice/dasbor/cetak-laporan'] = 'backoffice/admin/Dasbor/cetak_laporan';

/**----------------------------------------------------
 * Menu Menejmen
-------------------------------------------------------**/
$route['backoffice/menu-manajemen'] = 'backoffice/admin/Menu';

/**----------------------------------------------------
 * Pengguna
-------------------------------------------------------**/
$route['backoffice/pengguna/tambah'] = 'Auth/create';
$route['backoffice/pengguna'] = 'Auth';
$route['backoffice/pengguna/(:any)/ubah'] = 'Auth/update/$1';
$route['backoffice/pengguna/(:any)/hapus'] = 'Auth/destroy/$1';

/**----------------------------------------------------
 * Grup
-------------------------------------------------------**/
$route['backoffice/grup/tambah'] = 'backoffice/admin/Grup/create';
$route['backoffice/grup'] = 'backoffice/admin/Grup';
$route['backoffice/grup/(:any)/ubah'] = 'backoffice/admin/Grup/update/$1';
$route['backoffice/grup/(:any)/hapus'] = 'backoffice/admin/Grup/destroy/$1';

// Contoh penambahan unit kerja
$route['backoffice/grup/example/(:any)'] = 'backoffice/admin/Grup/example/$1';
// Contoh penambahan unit kerja


/**----------------------------------------------------
 * Hak Akses
-------------------------------------------------------**/
$route['backoffice/hak-akses/tambah'] = 'backoffice/admin/HakAkses/create';
$route['backoffice/hak-akses'] = 'backoffice/admin/HakAkses';
$route['backoffice/hak-akses/(:any)/grup'] = 'backoffice/admin/HakAkses/show/$1';
$route['backoffice/hak-akses/(:any)/ubah'] = 'backoffice/admin/HakAkses/update/$1';
$route['backoffice/hak-akses/(:any)/hapus'] = 'backoffice/admin/HakAkses/destroy/$1';

/**----------------------------------------------------
 * Aksi
-------------------------------------------------------**/
$route['backoffice/hak-akses/(:any)/grup/(:any)/menu/tambah'] = 'backoffice/admin/Aksi/create/$1/$2';
$route['backoffice/hak-akses/(:any)/grup/(:any)/menu/(:any)/menu-grup/ubah'] = 'backoffice/admin/Aksi/update/$1/$2/$3';
$route['backoffice/hak-akses/(:any)/grup/(:any)/menu-grup/hapus'] = 'backoffice/admin/Aksi/destroy/$1/$2';

/**----------------------------------------------------
 * Unit Kerja
-------------------------------------------------------**/
$route['backoffice/unit-kerja/tambah'] = 'backoffice/admin/UnitKerja/create';
$route['backoffice/unit-kerja'] = 'backoffice/admin/UnitKerja';
$route['backoffice/unit-kerja/(:any)/ubah'] = 'backoffice/admin/UnitKerja/update/$1';
$route['backoffice/unit-kerja/(:any)/hapus'] = 'backoffice/admin/UnitKerja/destroy/$1';
$route['backoffice/hak-akses/(:any)/grup/(:any)/pengguna'] = 'backoffice/admin/UnitKerja/access/$1/$2';
$route['backoffice/hak-akses/(:any)/grup/(:any)/pengguna/(:any)/unit-kerja'] = 'backoffice/admin/UnitKerja/create_access/$1/$2/$3';

/**----------------------------------------------------
 * Log
-------------------------------------------------------**/
$route['backoffice/log'] = 'backoffice/admin/Log';

/**----------------------------------------------------
 * Load Time
-------------------------------------------------------**/
$route['backoffice/load-time'] = 'backoffice/admin/LoadTime';

/**----------------------------------------------------
 * Dokumentasi
-------------------------------------------------------**/
$route['backoffice/dokumentasi'] = 'backoffice/admin/Dokumentasi';

/**----------------------------------------------------
 * Kurir
-------------------------------------------------------**/
$route['backoffice/kurir/tambah'] = 'backoffice/admin/Kurir/create';
$route['backoffice/kurir'] = 'backoffice/admin/Kurir';
$route['backoffice/kurir/(:any)/ubah'] = 'backoffice/admin/Kurir/update/$1';
$route['backoffice/kurir/(:any)/hapus'] = 'backoffice/admin/Kurir/destroy/$1';

/**----------------------------------------------------
 * Kategori
-------------------------------------------------------**/
$route['backoffice/kategori-produk/tambah'] = 'backoffice/admin/Kategori/create';
$route['backoffice/kategori-produk'] = 'backoffice/admin/Kategori';
$route['backoffice/kategori-produk/(:any)/ubah'] = 'backoffice/admin/Kategori/update/$1';
$route['backoffice/kategori-produk/(:any)/hapus'] = 'backoffice/admin/Kategori/destroy/$1';

/**----------------------------------------------------
 * Referensi Spesialis
-------------------------------------------------------**/
$route['backoffice/spesialis/tambah'] = 'backoffice/admin/spesialis/create';
$route['backoffice/spesialis'] = 'backoffice/admin/spesialis';
$route['backoffice/spesialis/(:any)/ubah'] = 'backoffice/admin/spesialis/update/$1';
$route['backoffice/spesialis/(:any)/hapus'] = 'backoffice/admin/spesialis/destroy/$1';

/**----------------------------------------------------
 * Dokter
-------------------------------------------------------**/
$route['backoffice/dokter/tambah'] = 'backoffice/admin/dokter/create';
$route['backoffice/dokter'] = 'backoffice/admin/dokter';
$route['backoffice/dokter/(:any)/ubah'] = 'backoffice/admin/dokter/update/$1';
$route['backoffice/dokter/(:any)/hapus'] = 'backoffice/admin/dokter/destroy/$1';

/**----------------------------------------------------
 * Data Eccomerce
-------------------------------------------------------**/
$route['backoffice/data-ecommerce'] = 'backoffice/admin/DataEcommerce';

/**----------------------------------------------------
 * Voucher
-------------------------------------------------------**/
$route['backoffice/voucher/tambah'] = 'backoffice/admin/Voucher/create';
$route['backoffice/voucher'] = 'backoffice/admin/Voucher';
$route['backoffice/voucher/(:any)/ubah'] = 'backoffice/admin/Voucher/update/$1';
$route['backoffice/voucher/(:any)/hapus'] = 'backoffice/admin/Voucher/destroy/$1';

$route['voucher/(:any)/lihat'] = 'voucher/show/$1';


/**----------------------------------------------------
 * Produk admin
-------------------------------------------------------**/
$route['backoffice/produk/tambah'] = 'backoffice/admin/Produk/create';
$route['backoffice/produk'] = 'backoffice/admin/Produk';
$route['backoffice/produk/(:any)/ubah'] = 'backoffice/admin/Produk/update/$1';
$route['backoffice/produk/(:any)/hapus'] = 'backoffice/admin/Produk/destroy/$1';

/**----------------------------------------------------
 * Customer admin
-------------------------------------------------------**/
$route['backoffice/customer'] = 'backoffice/admin/Customer';
$route['backoffice/customer/(:any)/ubah'] = 'backoffice/admin/Customer/update/$1';

/**----------------------------------------------------
 * Transaksi admin
-------------------------------------------------------**/
$route['backoffice/transaksi'] = 'backoffice/admin/Transaksi';
$route['backoffice/transaksi/(:any)/ubah'] = 'backoffice/admin/Transaksi/update/$1';
$route['backoffice/transaksi/status-bayar/(:any)'] = 'backoffice/admin/Transaksi/status_bayar/$1';
$route['backoffice/transaksi/update-resi/(:any)'] = 'backoffice/admin/Transaksi/update_resi/$1';
$route['backoffice/transaksi/(:any)/testimoni'] = 'backoffice/admin/Transaksi/testimoni/$1';

/**----------------------------------------------------
 * Produk
-------------------------------------------------------**/
$route['produk'] = 'Produk';
$route['produk/(:any)/lihat'] = 'Produk/view/$1';

/**----------------------------------------------------
 * Customer
-------------------------------------------------------**/
$route['login'] = 'Customer/login';
$route['logout'] = 'Customer/logout';
$route['registrasi'] = 'Customer/register';
$route['profil'] = 'Customer/profil';
$route['transaksi'] = 'Customer/transaksi';
$route['transaksi/(:any)/detail'] = 'Customer/transaksi_detail/$1';
$route['transaksi/(:any)/konfirmasi'] = 'Customer/transaksi_konfirmasi/$1';
$route['keranjang'] = 'Customer/keranjang';
$route['keranjang/(:any)/update'] = 'Customer/keranjang_update/$1';
$route['keranjang/(:any)/hapus'] = 'Customer/keranjang_hapus/$1';

/**----------------------------------------------------
 * Checkout
-------------------------------------------------------**/
$route['checkout'] = 'Checkout/index';

/**----------------------------------------------------
 * Testimoni
-------------------------------------------------------**/
$route['testimoni/(:any)/create'] = 'Testimoni/create/$1';

/**----------------------------------------------------
 * Treatment
-------------------------------------------------------**/
$route['backoffice/treatment/tambah'] = 'backoffice/admin/treatment/create';
$route['backoffice/treatment'] = 'backoffice/admin/treatment';
$route['backoffice/treatment/(:any)/ubah'] = 'backoffice/admin/treatment/update/$1';
$route['backoffice/treatment/(:any)/hapus'] = 'backoffice/admin/treatment/destroy/$1';
$route['treatment/(:any)/lihat'] = 'treatment/view/$1';

/**----------------------------------------------------
 * Saran dan kritik
-------------------------------------------------------**/
$route['kritik-saran'] = 'kritik_saran';
$route['kritik-saran/buat'] = 'kritik_saran/create';
$route['kritik-saran/(:any)/detail'] = 'kritik_saran/show/$1';

$route['backoffice/kritik-saran'] = 'backoffice/admin/kritik_saran';
$route['backoffice/kritik-saran/(:any)/hapus-tanggapan'] = 'backoffice/admin/kritik_saran/destroy_detail/$1';
$route['backoffice/kritik-saran/(:any)/hapus'] = 'backoffice/admin/kritik_saran/destroy/$1';

$route['backoffice/kritik-saran/tambah'] = 'backoffice/admin/kritik_saran/create';
$route['backoffice/kritik-saran/(:any)/ubah'] = 'backoffice/admin/kritik_saran/update/$1';
$route['backoffice/kritik-saran/(:any)/detail'] = 'backoffice/admin/kritik_saran/show/$1';
$route['backoffice/kritik-saran/get_json'] = 'backoffice/admin/kritik_saran/get_json';


/**----------------------------------------------------
 * Jadwal Dokter
-------------------------------------------------------**/
$route['backoffice/jadwal-dokter'] = 'backoffice/admin/jadwal';
$route['backoffice/jadwal-dokter/tambah'] = 'backoffice/admin/jadwal/create';
$route['backoffice/jadwal-dokter/(:any)/ubah'] = 'backoffice/admin/jadwal/update/$1';
$route['backoffice/jadwal-dokter/(:any)/hapus'] = 'backoffice/admin/jadwal/destroy/$1';
$route['backoffice/jadwal-dokter/get_json'] = 'backoffice/admin/jadwal/get_json';
$route['backoffice/jadwal-dokter/(:any)/detail'] = 'backoffice/admin/jadwal/show/$1';

/**----------------------------------------------------
 * Booking treatment Admin
-------------------------------------------------------**/
$route['backoffice/booking'] = 'backoffice/admin/booking';
$route['backoffice/booking/tambah'] = 'backoffice/admin/booking/create';
$route['backoffice/booking/(:any)/ubah'] = 'backoffice/admin/booking/update/$1';
$route['backoffice/booking/(:any)/hapus'] = 'backoffice/admin/booking/destroy/$1';
$route['backoffice/booking/(:any)/detail'] = 'backoffice/admin/booking/show/$1';
$route['backoffice/booking/status-bayar'] = 'backoffice/admin/booking/status_bayar';
$route['backoffice/booking/(:any)/batalkan'] = 'backoffice/admin/booking/cancel/$1';

/**----------------------------------------------------
 * Booking treatment
-------------------------------------------------------**/
$route['booking-treatment'] = 'booking';
$route['booking-treatment/(:any)/tambah'] = 'booking/create/$1';
$route['booking-treatment/(:any)/lihat'] = 'booking/show/$1';

/**----------------------------------------------------
 * testimoni treatment
-------------------------------------------------------**/
$route['backoffice/testimoni-treatment'] = 'backoffice/admin/testimoniTreatment';
$route['backoffice/testimoni-treatment/tambah'] = 'backoffice/admin/testimoniTreatment/create';
$route['backoffice/testimoni-treatment/(:any)/ubah'] = 'backoffice/admin/testimoniTreatment/update/$1';
$route['backoffice/testimoni-treatment/(:any)/hapus'] = 'backoffice/admin/testimoniTreatment/destroy/$1';
$route['backoffice/testimoni-treatment/(:any)/lihat'] = 'backoffice/admin/testimoniTreatment/show/$1';


/* front */
$route['testimoni-treatment/(:any)/tambah'] = 'testimoniTreatment/create/$1';
$route['testimoni-treatment/(:any)/(:any)/ubah'] = 'testimoniTreatment/update/$1/$2';

/**----------------------------------------------------
 * Konsultasi
-------------------------------------------------------**/
$route['backoffice/konsultasi'] = 'backoffice/admin/konsultasi';
$route['backoffice/konsultasi/tambah'] = 'backoffice/admin/konsultasi/create';
$route['backoffice/konsultasi/(:any)/ubah'] = 'backoffice/admin/konsultasi/update/$1';

$route['backoffice/konsultasi/(:any)/hapus'] = 'backoffice/admin/konsultasi/destroy/$1';
$route['backoffice/konsultasi/(:any)/detail'] = 'backoffice/admin/konsultasi/show/$1';
$route['backoffice/konsultasi/(:any)/hapus-tanggapan'] = 'backoffice/admin/konsultasi/destroy_detail_konsul/$1';

/* front */
$route['konsultasi'] = 'konsultasi';
$route['konsultasi/tambah'] = 'konsultasi/create';
$route['konsultasi/(:any)/ubah'] = 'konsultasi/update/$1';
$route['konsultasi/(:any)/hapus'] = 'konsultasi/destroy/$1';
$route['konsultasi/(:any)/detail'] = 'konsultasi/show/$1';

/**----------------------------------------------------
 * Laporan
-------------------------------------------------------**/
$route['backoffice/laporan'] = 'backoffice/admin/laporan';
$route['backoffice/laporan/tambah'] = 'backoffice/admin/laporan/create';
$route['backoffice/laporan/(:any)/ubah'] = 'backoffice/admin/laporan/update/$1';
$route['backoffice/laporan/(:any)/hapus'] = 'backoffice/admin/laporan/destroy/$1';
$route['backoffice/laporan/(:any)/detail'] = 'backoffice/admin/laporan/show/$1';

$route['backoffice/laporan/cetak-laporan-treatment'] = 'backoffice/admin/laporan/cetak_laporan_treatment';
