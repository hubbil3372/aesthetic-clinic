<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Kategori extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Kategori_model', 'kategori');
    }

    /**----------------------------------------------------
     * Daftar Kategori
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Kategori',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu,
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/kategori/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->kategori->get_datatables();
        /**----------------------------------------------------
         * Ambil id menu untuk cek akses Update dan Destroy
    -------------------------------------------------------**/
        $menu_id = $this->menus->get_menu_id("backoffice/{$this->input->get('tautan')}");

        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $field) {
            /**----------------------------------------------------
             * Cek apakah role yang sedang login dapat melakukan Update dan Destroy
      -------------------------------------------------------**/
            $button = '';
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/kategori-produk/{$field->kategoriId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/kategori-produk/{$field->kategoriId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/kategori-produk/{$field->kategoriId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->kategoriId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->kategoriNama;
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->kategori->count_all(),
            "recordsFiltered" => $this->kategori->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah Kategori
  -------------------------------------------------------**/
    public function create()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuTambah')) redirect('404_override', 'refresh');

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'kategoriNama',
                'label' => 'Kategori',
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
                'title' => 'Tambah Kategori'
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/kategori/create', $data);
        } else {
            $post = $this->input->post(null, true);

            $this->kategori->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('produk_kategori', 'tambah', $post['kategoriNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah kategori!');
                return redirect(site_url('backoffice/kategori-produk'));
            }

            activity_log('produk_kategori', 'gagal tambah', $post['kategoriNama']);
            $this->session->set_flashdata('error', 'Gagal tambah kategori!');
            return redirect(site_url('backoffice/kategori-produk'));
        }
    }

    /**----------------------------------------------------
     * Ubah Kategori
  -------------------------------------------------------**/
    public function update($id)
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
                'field' => 'kategoriNama',
                'label' => 'Kategori',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $kategori = $this->kategori->get(['kategoriId' => $id]);
        if ($kategori->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/kategori-produk'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah Kategori',
                'kategori' => $kategori->row()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/kategori/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            $this->kategori->update($put, ['kategoriId' => $kategori->row()->kategoriId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('produk_kategori', 'ubah', "data {$put['kategoriNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah kategori');
                return redirect(site_url('backoffice/kategori-produk'));
            }

            activity_log('produk_kategori', 'gagal ubah', "data {$put['kategoriNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah kategori');
            return redirect(site_url('backoffice/kategori-produk'));
        }
    }

    /**----------------------------------------------------
     * Hapus Kategori
  -------------------------------------------------------**/
    public function destroy($id)
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

        /**----------------------------------------------------
         * Cek apakah data yang di hapus ada dalam database
    -------------------------------------------------------**/
        $kategori = $this->kategori->get(['kategoriId' => $id]);
        if ($kategori->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/kategori-produk'));
        }

        $this->kategori->destroy(['kategoriId' => $kategori->row()->kategoriId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('produk_kategori', 'hapus', $kategori->row()->kategoriNama);

            $this->session->set_flashdata('success', 'Berhasil hapus kategori!');
            return redirect(site_url('backoffice/kategori-produk'));
        }

        activity_log('produk_kategori', 'gagal hapus', $kategori->row()->kategoriNama);
        $this->session->set_flashdata('error', 'Gagal hapus kategori!');
        return redirect(site_url('backoffice/kategori-produk'));
    }

}
