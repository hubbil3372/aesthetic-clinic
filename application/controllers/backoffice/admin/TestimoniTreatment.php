<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class TestimoniTreatment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Testimoni_treatment_model', 'testi');
    }

    /**----------------------------------------------------
     * Daftar testi
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'testimoni treatment',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/testimoni-treatment/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->testi->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/testimoni-treatment/{$field->testiId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/testimoni-treatment/{$field->testiId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/testimoni-treatment/{$field->testiId}/hapus") . "'></i></a>";
            if ($this->akses->access_rights_aksi("backoffice/testimoni-treatment/detail")) $button .= "<a class='btn btn-sm btn-outline-primary me-1 waitme' href='" . site_url("backoffice/testimoni-treatment/{$field->testiId}/lihat") . "'>Detail</a>";
            if ($button == '') $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->customerNama;
            $row[] = $field->bdTreatmentNama;
            $row[] = $field->testiJudul;
            $row[] = $field->testiTeks;
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->testi->count_all(),
            "recordsFiltered" => $this->testi->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function show($testi_id)
    {
        if (!$this->akses->access_rights_aksi("backoffice/testimoni-treatment/detail")) redirect('404_override', 'refresh');

        $testi = $this->testi->get(['testiId' => $testi_id]);
        if ($testi->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('backoffice/testimoni-treatment'));
        }
        $testi = $testi->row();

        $config_form = [
            [
                'field' => 'testiBalasan',
                'label' => 'Balasan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == true) {
            $user = $this->ion_auth->user()->row();
            $post = $this->input->post(null, true);
            $post['testiAdminId'] = $user->pengId;

            $testimoni_admin_check = $this->testi->get(['testiAdminId' => $user->pengId, 'testiTreatmentId' => $testi->testiTreatmentId, 'testiBookingId' => $testi->testiBookingId])->num_rows();
            if ($testimoni_admin_check > 0) {
                $this->session->set_flashdata('success', 'Anda Sudah Memberikan balasan Ulasan!');
                return redirect(site_url('backoffice/testimoni-treatment/' . $testi->testiId . '/lihat'));
            }
            $this->testi->update($post, ['testiId' => $testi->testiId]);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Berhasil tambah ulasan!');
                return redirect(site_url('backoffice/testimoni-treatment/' . $testi->testiId . '/lihat'));
            }
            $this->session->set_flashdata('error', 'Gagal tambah ulasan!');
            return redirect(site_url('backoffice/testimoni-treatment/' . $testi->testiId . '/lihat'));
        }

        $data = [
            'title' => 'detail testimoni treatment',
            'testimoni' => $testi
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/testimoni-treatment/show', $data);
    }

    /**----------------------------------------------------
     * Tambah testi
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
                'field' => 'testiNama',
                'label' => 'Nama testi',
                'rules' => 'required'
            ],
            [
                'field' => 'testiSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'testiPengId',
                'label' => 'Pengguna',
                'rules' => 'required|is_unique[testi.testiPengId]',
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
        if ($this->form_validation->run() == false) {
            $data = [
                'title' => 'Tambah testi',
                'spesialis' => $this->db->get('testi_spesialis')->result(),
                'pengguna' => $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/testimoni-treatment/create', $data);
        } else {
            $post = $this->input->post(null, true);

            $this->testi->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('testi', 'tambah', $post['testiNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah testi!');
                return redirect(site_url('backoffice/testi'));
            }

            activity_log('testi', 'gagal tambah', $post['testiNama']);
            $this->session->set_flashdata('error', 'Gagal tambah testi!');
            return redirect(site_url('backoffice/testi'));
        }
    }

    /**----------------------------------------------------
     * Ubah testi
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
                'field' => 'testiNama',
                'label' => 'Nama testi',
                'rules' => 'required'
            ],
            [
                'field' => 'testiSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'testiPengId',
                'label' => 'Pengguna',
                'rules' => 'required|callback_check_testi_update'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $testi = $this->testi->get(['testiId' => $id]);
        if ($testi->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/testi'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah testi',
                'testi' => $testi->row(),
                'spesialis' => $this->db->get('testi_spesialis')->result(),
                'pengguna' => $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/testimoni-treatment/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);
            unset($put['testiId']);
            $this->testi->update($put, ['testiId' => $testi->row()->testiId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('testi', 'ubah', "data {$put['testiNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah testi');
                return redirect(site_url('backoffice/testi'));
            }

            activity_log('testi', 'gagal ubah', "data {$put['testiNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah testi');
            return redirect(site_url('backoffice/testi'));
        }
    }

    /**----------------------------------------------------
     * Hapus testi
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
        $testi = $this->testi->get(['testiId' => $id]);
        if ($testi->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/testi'));
        }

        $this->testi->destroy(['testiId' => $testi->row()->testiId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('testi', 'hapus', $testi->row()->testiNama);

            $this->session->set_flashdata('success', 'Berhasil hapus testi!');
            return redirect(site_url('backoffice/testi'));
        }

        activity_log('testi', 'gagal hapus', $testi->row()->testiNama);
        $this->session->set_flashdata('error', 'Gagal hapus testi!');
        return redirect(site_url('backoffice/testi'));
    }

    public function check_testi_update()
    {
        $testi = $this->db->get_where('testi', ['testiPengId' => $_POST['testiPengId'], 'testiId !=' => $_POST['testiId']]);
        if ($testi->num_rows() > 0) {
            $this->form_validation->set_message('check_testi_update', '{field} Sudah digunakan!');
            return false;
        }
        return true;
    }
}
