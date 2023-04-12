<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Beranda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Produk_model', 'produk');
    }

    /**----------------------------------------------------
     * Beranda
  -------------------------------------------------------**/
    public function index()
    {
        $data = [
            'title'     => 'Beranda',
            'voucher'   => $this->voucher->get(['voucherStatus' => 1], 'voucherDibuatPada DESC')->result(),
            'produk'    => $this->produk->get(['produkStatus' => 1], 'produkDibuatPada DESC', [8, 0])->result()
        ];

        $this->template->load('template/frontend', 'frontend/beranda/index', $data);
    }
}
