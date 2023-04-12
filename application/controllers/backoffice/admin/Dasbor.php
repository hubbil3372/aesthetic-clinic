<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Dasbor extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Checkout_model', 'checkout');
    $this->load->model('Customer_model', 'customer');
    $this->load->model('Voucher_model', 'voucher');
    $this->load->model('Produk_model', 'produk');

  }

  /**----------------------------------------------------
   * Tampilan Dasbor
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'start',
        'label' => 'Tanggal Awal',
        'rules' => 'required'
      ],
      [
        'field' => 'end',
        'label' => 'Tanggal Akhir',
        'rules' => 'required'
      ]
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == false) {
      $data = [
        'title'         => 'Dasbor',
        'tot_checkout'  => $this->checkout->count_all(),
        'tot_customer'  => $this->customer->count_all(),
        'tot_voucher'   => $this->voucher->count_all(),
        'tot_produk'    => $this->produk->count_all(),
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/dasbor/index', $data);
    } else {
      $post = $this->input->post(null, true);

      $uri = site_url('backoffice/dasbor/cetak-laporan?');
      foreach ($post as $key => $v) {
        $uri .= $key . '=' . $v . '&';
      }

      return redirect($uri);
    }
    
  }

  public function cetak_laporan()
  {
    $get = $this->input->get(null, true);

    if(!$get) {
      $this->session->set_flashdata('error', 'Silahkan tentukan Tanggal Awal dan Tanggal Akhir terlebih dahulu!');
      return redirect(site_url('backoffice/dasbor'));
    }

    $where = [
      'checkoutDibuatPada >=' => $get['start'],
      'checkoutDibuatPada <=' => $get['end']
    ];

    $checkout = $this->checkout->get($where, 'checkoutDibuatPada DESC')->result();

    foreach ($checkout as $key => $v) {
      $checkout[$key]->detail = $this->checkout->get_detail(['detailCheckoutId' => $v->checkoutId])->result();
    }

    $data = [
      'title'         => 'Cetak Laporan',
      'transaksi'     => $checkout
    ];

    $this->load->view('backoffice/admin/dasbor/cetak_laporan', $data);
  }
}
