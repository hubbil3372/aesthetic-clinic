<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Testimoni extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_no_login();
        $this->load->library('uuid');

        $this->load->model('Checkout_model', 'checkout');
        $this->load->model('Testimoni_model', 'testimoni');
    }

    /**----------------------------------------------------
     * Tambah Testimoni
  -------------------------------------------------------**/
    public function create($checkoutId)
    {
        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'testimoniProdukId[]',
                'label' => 'Id Produk',
                'rules' => 'required'
            ],
            [
                'field' => 'testimoniCheckoutId[]',
                'label' => 'Id Checkout',
                'rules' => 'required'
            ],
            [
                'field' => 'testimoniTeks[]',
                'label' => 'Teks Ulasan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $testimoni  = $this->testimoni->get(['testimoniCheckoutId' => $checkoutId])->result();
            $produk     = $this->checkout->get_detail(['detailCheckoutId' => $checkoutId])->result();

            if (count($testimoni) >= count($produk)) {
                $this->session->set_flashdata('error', 'Kamu sudah menambah ulasan sebelumnya!');
                return redirect(site_url('transaksi/' . $checkoutId . '/detail'));
            }
            
            $data = [
                'title'     => 'Ulasan Kamu',
                'produk'    => $produk
            ];

            $this->template->load('template/frontend', 'frontend/testimoni/index', $data);
        } else {
            $post = $this->input->post(null, true);

            foreach ($post['testimoniProdukId'] as $key => $v) {
                $data[$key]['testimoniId'] = $this->uuid->v4();
                $data[$key]['testimoniCustomerId'] = $this->session->userdata('customerId');
                $data[$key]['testimoniProdukId'] = $v;
            }

            foreach ($post['testimoniCheckoutId'] as $key => $v) {
                $data[$key]['testimoniCheckoutId'] = $v;
            }

            foreach ($post['testimoniTeks'] as $key => $v) {
                $data[$key]['testimoniTeks'] = $v;
            }

            $this->testimoni->create($data);
            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('success', 'Berhasil tambah ulasan!');
                return redirect(site_url('transaksi/' . $checkoutId . '/detail'));
            }
            
            $this->session->set_flashdata('error', 'Gagal tambah ulasan!');
            return redirect(site_url('testimoni/' . $checkoutId . '/create'));
        }
    }
}
