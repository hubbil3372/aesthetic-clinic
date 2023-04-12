<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Dokter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('dokter_model', 'dokter');
        $this->load->model('pengguna_model', 'pengguna');
    }

    /**----------------------------------------------------
     * Daftar dokter
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Dokter',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/dokter/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->dokter->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/dokter/{$field->dokterId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/dokter/{$field->dokterId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/dokter/{$field->dokterId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->dokterId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->dokterNama;
            $row[] = $field->spesialisNama;
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->dokter->count_all(),
            "recordsFiltered" => $this->dokter->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah dokter
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
                'field' => 'dokterSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'dokterPengId',
                'label' => 'Pengguna',
                'rules' => 'required|is_unique[dokter.dokterPengId]',
                'errors' => [
                    'is_unique' => '{field} sudah digunakan!'
                ]
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        $pengguna = $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get();
        if ($this->form_validation->run() == false) {
            $data = [
                'title' => 'Tambah dokter',
                'spesialis' => $this->db->get('dokter_spesialis')->result(),
                'pengguna' => $pengguna->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/dokter/create', $data);
        } else {
            $post = $this->input->post(null, true);
            $post['dokterNama'] = $this->pengguna->get(['pengId' => $post['dokterPengId']])->row()->pengNama;
            $this->dokter->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('dokter', 'tambah', $post['dokterNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah dokter!');
                return redirect(site_url('backoffice/dokter'));
            }

            activity_log('dokter', 'gagal tambah', $post['dokterNama']);
            $this->session->set_flashdata('error', 'Gagal tambah dokter!');
            return redirect(site_url('backoffice/dokter'));
        }
    }

    /**----------------------------------------------------
     * Ubah dokter
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
                'field' => 'dokterNama',
                'label' => 'Nama dokter',
                'rules' => 'required'
            ],
            [
                'field' => 'dokterSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'dokterPengId',
                'label' => 'Pengguna',
                'rules' => 'required|callback_check_dokter_update'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $dokter = $this->dokter->get(['dokterId' => $id]);
        if ($dokter->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/dokter'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah dokter',
                'dokter' => $dokter->row(),
                'spesialis' => $this->db->get('dokter_spesialis')->result(),
                'pengguna' => $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/dokter/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);
            unset($put['dokterId']);
            $this->dokter->update($put, ['dokterId' => $dokter->row()->dokterId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('dokter', 'ubah', "data {$put['dokterNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah dokter');
                return redirect(site_url('backoffice/dokter'));
            }

            activity_log('dokter', 'gagal ubah', "data {$put['dokterNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah dokter');
            return redirect(site_url('backoffice/dokter'));
        }
    }

    /**----------------------------------------------------
     * Hapus dokter
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
        $dokter = $this->dokter->get(['dokterId' => $id]);
        if ($dokter->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/dokter'));
        }

        $this->dokter->destroy(['dokterId' => $dokter->row()->dokterId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('dokter', 'hapus', $dokter->row()->dokterNama);

            $this->session->set_flashdata('success', 'Berhasil hapus dokter!');
            return redirect(site_url('backoffice/dokter'));
        }

        activity_log('dokter', 'gagal hapus', $dokter->row()->dokterNama);
        $this->session->set_flashdata('error', 'Gagal hapus dokter!');
        return redirect(site_url('backoffice/dokter'));
    }

    public function check_dokter_update()
    {
        $dokter = $this->db->get_where('dokter', ['dokterPengId' => $_POST['dokterPengId'], 'dokterId !=' => $_POST['dokterId']]);
        if ($dokter->num_rows() > 0) {
            $this->form_validation->set_message('check_dokter_update', '{field} Sudah digunakan!');
            return false;
        }
        return true;
    }
}
