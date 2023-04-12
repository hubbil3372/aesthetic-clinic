<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Jadwal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Jadwal_dokter_model', 'jadwal');
        $this->load->model('Dokter_model', 'dokter');
    }

    /**----------------------------------------------------
     * Daftar jadwal
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Jadwal Dokter/Perawat',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu,
            'dokter' => $this->dokter->get()->result()
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/jadwal/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->jadwal->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/jadwal-dokter/{$field->jdId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/jadwal-dokter/{$field->jdId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/jadwal-dokter/{$field->jdId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->dokterNama;
            $row[] = substr($field->jdJamAwal, 0, 5);
            $row[] = substr($field->jdJamAkhir, 0, 5);
            $row[] = $field->jdBatasAntrian;
            $row[] = $field->jdStatus == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->jadwal->count_all(),
            "recordsFiltered" => $this->jadwal->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah jadwal
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
                'field' => 'jdJamAwal',
                'label' => 'Jam Awal',
                'rules' => 'required'
            ],
            [
                'field' => 'jdJamAkhir',
                'label' => 'Jam Akhir',
                'rules' => 'required'
            ],
            [
                'field' => 'jdStatus',
                'label' => 'Status jadwal',
                'rules' => 'required'
            ],
            [
                'field' => 'jdBatasAntrian',
                'label' => 'Batas Antrian',
                'rules' => 'required|numeric',
                'errors' => [
                    'numeric' => '{field} harus berupa angka!'
                ]
            ],
            [
                'field' => 'jdDokterId',
                'label' => 'Dokter',
                'rules' => 'required|is_unique[jadwal_dokter.jdDokterId]',
                'errors' => [
                    'is_unique' => '{field} Sudah ditambahkan!'
                ]
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $data = [
                'title' => 'Tambah Jadwal Dokter',
                'dokter' => $this->dokter->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/jadwal/create', $data);
        } else {
            $post = $this->input->post(null, true);
            $post['jdTotalWaktuTreatment'] = $this->_get_minute_selisih($post['jdJamAwal'], $post['jdJamAkhir']);
            $this->jadwal->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('jadwal dokter', 'tambah', $post['jdDokterId']);

                $this->session->set_flashdata('success', 'Berhasil tambah jadwal!');
                return redirect(site_url('backoffice/jadwal-dokter'));
            }

            activity_log('jadwal dokter', 'gagal tambah', $post['jdDokterId']);
            $this->session->set_flashdata('error', 'Gagal tambah jadwal!');
            return redirect(site_url('backoffice/jadwal-dokter'));
        }
    }

    /**----------------------------------------------------
     * Ubah jadwal
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
                'field' => 'jdJamAwal',
                'label' => 'Jam Awal',
                'rules' => 'required'
            ],
            [
                'field' => 'jdJamAkhir',
                'label' => 'Jam Akhir',
                'rules' => 'required'
            ],
            [
                'field' => 'jdBatasAntrian',
                'label' => 'Batas Antrian',
                'rules' => 'required|numeric',
                'errors' => [
                    'numeric' => '{field} harus berupa angka!'
                ]
            ],
            [
                'field' => 'jdStatus',
                'label' => 'Status jadwal',
                'rules' => 'required'
            ],
            [
                'field' => 'jdDokterId',
                'label' => 'Dokter',
                'rules' => 'required|callback_check_dokter'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $jadwal = $this->jadwal->get(['jdId' => $id]);
        if ($jadwal->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/jadwal-dokter'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah jadwal dokter',
                'jadwal' => $jadwal->row(),
                'dokter' => $this->dokter->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/jadwal/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);
            $put['jdTotalWaktuTreatment'] = $this->_get_minute_selisih($put['jdJamAwal'], $put['jdJamAkhir']);
            unset($put['jdId']);
            $this->jadwal->update($put, ['jdId' => $jadwal->row()->jdId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('jadwal Dokter', 'ubah', "data {$put['jdDokterId']}");

                $this->session->set_flashdata('success', 'Berhasil ubah jadwal');
                return redirect(site_url('backoffice/jadwal-dokter'));
            }

            activity_log('jadwal Dokter', 'gagal ubah', "data {$put['jdDokterId']}");
            $this->session->set_flashdata('error', 'Gagal ubah jadwal');
            return redirect(site_url('backoffice/jadwal-dokter'));
        }
    }

    /**----------------------------------------------------
     * Hapus jadwal
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
        $jadwal = $this->jadwal->get(['jdId' => $id]);
        if ($jadwal->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/jadwal-dokter'));
        }

        $this->jadwal->destroy(['jdId' => $jadwal->row()->jdId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('jadwal dokter', 'hapus', $jadwal->row()->jdDokterId);

            $this->session->set_flashdata('success', 'Berhasil hapus jadwal!');
            return redirect(site_url('backoffice/jadwal-dokter'));
        }

        activity_log('jadwal dokter', 'gagal hapus', $jadwal->row()->jdDokterId);
        $this->session->set_flashdata('error', 'Gagal hapus jadwal!');
        return redirect(site_url('backoffice/jadwal-dokter'));
    }
    /**----------------------------------------------------
     * callback
  -------------------------------------------------------**/
    function check_dokter()
    {
        if ($this->db->get_where('jadwal_dokter', ['jdDokterId' => $_POST['jdDokterId'], 'jdId !=' => $_POST['jdId']])->num_rows() > 0) {
            $this->form_validation->set_message('check_dokter', '{field} sudah digunakan!');
            return false;
        }
        return true;
    }

    function _get_minute_selisih($first_time, $end_time)
    {
        $first = strtotime($first_time);
        $end = strtotime($end_time);

        $diff = $end - $first;
        return  $diff / 60;
    }
}
