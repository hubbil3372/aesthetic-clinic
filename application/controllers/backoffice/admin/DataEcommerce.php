<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class DataEcommerce extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Data_ecommerce_model', 'ecom');
    }

    /**----------------------------------------------------
     * Ubah Data Ecommerce
  -------------------------------------------------------**/
    public function index($id = 123)
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'ecomNama',
                'label' => 'Nama Ecommerce',
                'rules' => 'required'
            ],
            [
                'field' => 'ecomNoHandphone',
                'label' => 'Nomor Handphone',
                'rules' => 'required'
            ],
            [
                'field' => 'ecomAlamatKotkabId',
                'label' => 'Kota/Kabupaten',
                'rules' => 'required'
            ],
            [
                'field' => 'ecomAlamatLengkap',
                'label' => 'Alamat Lengkap',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $ecom = $this->ecom->get(['ecomId' => $id]);
        if ($ecom->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/data-ecommerce'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title'     => 'Ubah Data Ecommerce',
                'ecom'      => $ecom->row(),
                'cities'    => $this->ecom->get_cities()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/data-ecommerce/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            $this->ecom->update($put, ['ecomId' => $ecom->row()->ecomId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('ecommerce', 'ubah', "data {$put['ecomNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah data ecommerce');
                return redirect(site_url('backoffice/data-ecommerce'));
            }

            activity_log('ecommerce', 'gagal ubah', "data {$put['ecomNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah data ecommerce');
            return redirect(site_url('backoffice/data-ecommerce'));
        }
    }
}
