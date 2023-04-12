<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Spesialis extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Spesialis_model', 'spesialis');
    }

    /**----------------------------------------------------
     * Daftar spesialis
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Spesialis',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/spesialis/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->spesialis->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/spesialis/{$field->spesialisId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/spesialis/{$field->spesialisId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/spesialis/{$field->spesialisId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->spesialisNama;
            $row[] = $field->spesialisDeskripsi;
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spesialis->count_all(),
            "recordsFiltered" => $this->spesialis->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah spesialis
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
                'field' => 'spesialisNama',
                'label' => 'Nama Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'spesialisDeskripsi',
                'label' => 'Kode',
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
                'title' => 'Tambah spesialis'
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/spesialis/create', $data);
        } else {
            $post = $this->input->post(null, true);

            $this->spesialis->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('spesialis', 'tambah', $post['spesialisNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah spesialis!');
                return redirect(site_url('backoffice/spesialis'));
            }

            activity_log('spesialis', 'gagal tambah', $post['spesialisNama']);
            $this->session->set_flashdata('error', 'Gagal tambah spesialis!');
            return redirect(site_url('backoffice/spesialis'));
        }
    }

    /**----------------------------------------------------
     * Ubah spesialis
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
                'field' => 'spesialisNama',
                'label' => 'Nama Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'spesialisDeskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $spesialis = $this->spesialis->get(['spesialisId' => $id]);
        if ($spesialis->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/spesialis'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah spesialis',
                'spesialis' => $spesialis->row()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/spesialis/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            $this->spesialis->update($put, ['spesialisId' => $spesialis->row()->spesialisId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('spesialis', 'ubah', "data {$put['spesialisNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah spesialis');
                return redirect(site_url('backoffice/spesialis'));
            }

            activity_log('spesialis', 'gagal ubah', "data {$put['spesialisNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah spesialis');
            return redirect(site_url('backoffice/spesialis'));
        }
    }

    /**----------------------------------------------------
     * Hapus spesialis
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
        $spesialis = $this->spesialis->get(['spesialisId' => $id]);
        if ($spesialis->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/spesialis'));
        }

        $this->spesialis->destroy(['spesialisId' => $spesialis->row()->spesialisId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('spesialis', 'hapus', $spesialis->row()->spesialisNama);

            $this->session->set_flashdata('success', 'Berhasil hapus spesialis!');
            return redirect(site_url('backoffice/spesialis'));
        }

        activity_log('spesialis', 'gagal hapus', $spesialis->row()->spesialisNama);
        $this->session->set_flashdata('error', 'Gagal hapus spesialis!');
        return redirect(site_url('backoffice/spesialis'));
    }
}
