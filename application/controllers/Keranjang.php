<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Keranjang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model', 'produk');
        $this->load->model('Keranjang_model', 'keranjang');
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
