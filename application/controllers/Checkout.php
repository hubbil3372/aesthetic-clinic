<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Checkout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_no_login();
        $this->load->library('uuid');

        $this->load->model('Customer_model', 'customer');
        $this->load->model('Produk_model', 'produk');
        $this->load->model('Keranjang_model', 'keranjang');
        $this->load->model('Kurir_model', 'kurir');
        $this->load->model('Data_ecommerce_model', 'ecom');
        $this->load->model('raja_ongkir_model', 'rajaongkir');
        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Checkout_model', 'checkout');
    }

    /**----------------------------------------------------
     * Checkout
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek Keranjang
    -------------------------------------------------------**/
        $keranjang = $this->keranjang->get(['keranjangCustomerId' => $this->session->userdata('customerId')])->result();

        if (!$keranjang) {
            $this->session->set_flashdata('error', 'Tidak ada produk dalam keranjang!');
            return redirect(site_url('keranjang'));
        }

        $user = $this->customer->get(['customerId' => $this->session->userdata('customerId')])->row();

        // cek alamat pengiriman
        if (!$user->customerAlamatLengkap) {
            $this->session->set_flashdata('error', 'Silahkan lengkapi alamat pengiriman terlebih dahulu!');
            return redirect(site_url('profil'));
        }

        $checkoutId = $this->uuid->v4();
        $total_berat = 0;
        $total_tagihan = 0;

        foreach ($keranjang as $key => $v) {
            $produk[$key] = $this->produk->get(['produkId' => $v->produkId])->row();
            // cek produk
            if (!$produk[$key]) {
                $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
                return redirect(site_url('keranjang'));
            }
            // cek stok
            if ($produk[$key]->produkStok < $v->keranjangKuantitas) {
                $this->session->set_flashdata('error', 'Stok produk ' . $produk[$key]->produkNama . ' tidak cukup!');
                return redirect(site_url('keranjang'));
            }

            $harga = $v->produkHarga * $v->keranjangKuantitas;
            $diskon = $v->produkDiskon * $v->keranjangKuantitas;
            $berat = $v->produkBerat * $v->keranjangKuantitas;
            $total_harga = $harga - $diskon;

            $total_berat = $total_berat + $berat;
            $total_tagihan = $total_tagihan + $total_harga;

            $c_detail[] = (object) [
                'detailId'                  => $this->uuid->v4(),
                'detailCheckoutId'          => $checkoutId,
                'detailProdukId'            => $v->produkId,
                'detailProdukNama'          => $v->produkNama,
                'detailProdukGambar'        => $v->produkGambar1,
                'detailProdukKategoriId'    => $v->produkKategoriId,
                'detailProdukDeskripsi'     => $v->produkDeskripsi,
                'detailProdukHarga'         => $harga,
                'detailProdukDiskon'        => $diskon,
                'detailKuantitas'           => $v->keranjangKuantitas,
                'detailBerat'               => $berat,
                'detailTotalHarga'          => $total_harga,
            ];
        }

        $origin = $this->ecom->get(['ecomId' => 123])->row();
        $kurir = $this->kurir->get(['kurirStatus' => 1])->result();
        $ongkir = json_decode($this->rajaongkir->cost($kurir, $total_berat, $origin->ecomAlamatKotkabId, $origin->ecomAlamatJenis, $user->customerAlamatKecamatanId))->rajaongkir->results;

        // Jika tidak ada POST
        $post = $this->input->post(null, true);
        if (!$post) {
            $data = [
                'title'         => 'Lanjut Pembayaran',
                'c_detail'      => $c_detail,
                'total_tagihan' => $total_tagihan,
                'alamat'        => $user,
                'ongkir'        => $ongkir
            ];

            return $this->template->load('template/frontend', 'frontend/checkout/index', $data);
        }


        if (!isset($post['ongkoskirim'])) {
            $this->session->set_flashdata('warning', 'Silakan Pilih Kurir Pengiriman Paket');
            return redirect(site_url('checkout'));
        }

        $explode = explode('-', $post['ongkoskirim']);
        $checkoutKurirNama = $explode[0] == 'J&T' ? 'jnt' : $explode[0];
        $checkoutKurirPaket = $explode[1];
        $checkoutOngkir = $explode[2];
        $voucher = 0;
        if ($post['checkoutvoucherpotongan']) {
            $voucher = $this->voucher->get(['voucherKode' => $post['checkoutvoucherpotongan']])->row()->voucherPotongan;
        }

        $total_tagihan += $checkoutOngkir;
        $total_tagihan -= $voucher;

        $checkout = [
            'checkoutId'                => $checkoutId,
            'checkoutKode'              => 'AEST' . date('ymdHis'),
            'checkoutCustomerId'        => $this->session->userdata('customerId'),
            'checkoutVoucherPotongan'   => $voucher,
            'checkoutKurirNama'         => $checkoutKurirNama,
            'checkoutKurirPaket'        => $checkoutKurirPaket,
            'checkoutOngkir'            => $checkoutOngkir,
            'checkoutAlamatLengkap'     => $user->customerAlamatLengkap,
            'checkoutAlamatProvinsiId'  => $user->customerAlamatProvinsiId,
            'checkoutAlamatKotkabId'    => $user->customerAlamatKotkabId,
            'checkoutAlamatKecamatanId' => $user->customerAlamatKecamatanId,
            'checkoutAlamatPenerima'    => $user->customerAlamatPenerima,
            'checkoutAlamatNoHp'        => $user->customerAlamatNoHp,
            'checkoutCatatan'           => $post['checkoutCatatan'],
            'checkoutTotalTagihan'      => $total_tagihan,
        ];

        $this->checkout->create($checkout);
        if ($this->db->affected_rows() == 1) {

            $this->checkout->create_detail($c_detail);
            if ($this->db->affected_rows() > 0) {
                // hapus keranjang
                $this->keranjang->destroy(['keranjangCustomerId' => $this->session->userdata('customerId')]);
                // kurangi stok
                foreach ($produk as $key => $v) {
                    $data_stok = ['produkStok' => ($v->produkStok - $c_detail[$key]->detailKuantitas)];
                    $this->produk->update($data_stok, ['produkId' => $v->produkId]);
                }

                $this->session->set_flashdata('success', 'Berhasil membuat transaksi! Silahkan melanjutkan pembayaran');
                return redirect(site_url('transaksi/' . $checkoutId . '/detail'));
            }

            $this->checkout->destroy(['checkoutId' => $checkoutId]);
        }

        $this->session->set_flashdata('error', 'Gagal membuat transaksi!');
        return redirect(site_url('checkout'));
    }

    /**----------------------------------------------------
     * Cek Voucher
  -------------------------------------------------------**/
    public function voucher($kode)
    {
        $voucher = $this->voucher->get(['voucherKode' => $kode, 'voucherStatus' => 1])->row();
        if (!$voucher) {
            $response = (object) [
                'status'    => false,
                'data'      => null
            ];
        } else {
            $response = (object) [
                'status'    => true,
                'data'      => $voucher
            ];
        }

        echo json_encode($response);
    }

    /**----------------------------------------------------
     * Tambah Keranjang
  -------------------------------------------------------**/
    public function create()
    {
        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'keranjangProdukId',
                'label' => 'Produk',
                'rules' => 'required'
            ],
            [
                'field' => 'keranjangKuantitas',
                'label' => 'Jumlah',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {

            $this->session->set_flashdata('error', 'Gagal tambah keranjang!');
            return redirect(site_url('keranjang'));
        } else {
            $post = $this->input->post(null, true);
            $post['keranjangCustomerId'] = 123;

            echo '<pre>';
            print_r($post);
            echo '</pre>';
            return;

            $this->keranjang->create($post);
            if ($this->db->affected_rows() == 1) {

                $this->session->set_flashdata('success', 'Berhasil tambah keranjang!');
                return redirect(site_url('keranjang'));
            }

            $this->session->set_flashdata('error', 'Gagal tambah keranjang!');
            return redirect(site_url('keranjang'));
        }
    }
}
