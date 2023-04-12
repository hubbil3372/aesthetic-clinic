<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Kurir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Kurir_model', 'kurir');
    }

    /**----------------------------------------------------
     * Daftar Kurir
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Kurir',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/kurir/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->kurir->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/kurir/{$field->kurirId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/kurir/{$field->kurirId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/kurir/{$field->kurirId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->kurirId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->kurirNama;
            $row[] = $field->kurirKode;
            $row[] = $field->kurirStatus == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->kurir->count_all(),
            "recordsFiltered" => $this->kurir->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah Kurir
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
                'field' => 'kurirNama',
                'label' => 'Kurir',
                'rules' => 'required'
            ],
            [
                'field' => 'kurirKode',
                'label' => 'Kode',
                'rules' => 'required'
            ],
            [
                'field' => 'kurirStatus',
                'label' => 'Status',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $data = [
                'title' => 'Tambah Kurir'
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/kurir/create', $data);
        } else {
            $post = $this->input->post(null, true);

            $this->kurir->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('kurir', 'tambah', $post['kurirNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah kurir!');
                return redirect(site_url('backoffice/kurir'));
            }

            activity_log('kurir', 'gagal tambah', $post['kurirNama']);
            $this->session->set_flashdata('error', 'Gagal tambah kurir!');
            return redirect(site_url('backoffice/kurir'));
        }
    }

    /**----------------------------------------------------
     * Ubah Kurir
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
                'field' => 'kurirNama',
                'label' => 'Kurir',
                'rules' => 'required'
            ],
            [
                'field' => 'kurirKode',
                'label' => 'Kode',
                'rules' => 'required'
            ],
            [
                'field' => 'kurirStatus',
                'label' => 'Status',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $kurir = $this->kurir->get(['kurirId' => $id]);
        if ($kurir->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/kurir'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah Kurir',
                'kurir' => $kurir->row()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/kurir/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            $this->kurir->update($put, ['kurirId' => $kurir->row()->kurirId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('kurir', 'ubah', "data {$put['kurirNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah kurir');
                return redirect(site_url('backoffice/kurir'));
            }

            activity_log('kurir', 'gagal ubah', "data {$put['kurirNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah kurir');
            return redirect(site_url('backoffice/kurir'));
        }
    }

    /**----------------------------------------------------
     * Hapus Kurir
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
        $kurir = $this->kurir->get(['kurirId' => $id]);
        if ($kurir->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/kurir'));
        }

        $this->kurir->destroy(['kurirId' => $kurir->row()->kurirId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('kurir', 'hapus', $kurir->row()->kurirNama);

            $this->session->set_flashdata('success', 'Berhasil hapus kurir!');
            return redirect(site_url('backoffice/kurir'));
        }

        activity_log('kurir', 'gagal hapus', $kurir->row()->kurirNama);
        $this->session->set_flashdata('error', 'Gagal hapus kurir!');
        return redirect(site_url('backoffice/kurir'));
    }

}
