<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Kritik_saran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
        $this->load->model('Kritik_saran_model', 'saran');
        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');
    }

    /**----------------------------------------------------
     * Beranda
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $saran  = $this->db->get_where('saran');
        $data = [
            'title'     => 'Saran dan Kritik',
            'menu_id' => $menu,
            'saran' => $saran
        ];
        $this->template->load('template/dasbor', 'backoffice/admin/kritik-saran/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->saran->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/kritik-saran/{$field->saranId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/kritik-saran/{$field->saranId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/kritik-saran/{$field->saranId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';
            if ($this->akses->access_rights_aksi('backoffice/kritik-saran/detail')) $button .= "<a class='btn btn-sm btn-outline-primary ms-1' href='" . site_url("backoffice/kritik-saran/{$field->saranId}/detail") . "'>Detail</a>";
            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->saranId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->saranJudul;
            $row[] = $field->customerNama;
            $row[] = indo_date($field->saranDibuatPada);
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->saran->count_all(),
            "recordsFiltered" => $this->saran->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function create()
    {
        $config_form = [
            [
                'field' => 'saranJudul',
                'label' => 'Judul',
                'rules' => 'required'
            ],
            [
                'field' => 'saranText',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');
        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == TRUE) {

            $post = $this->input->post(null, TRUE);
            $post['saranId']  = $this->uuid->v4();
            $post['saranCustomerId']  = $this->front_auth->logged_data()->customerId;
            $post['saranDibuatPada'] = date('Y-m-d');

            $this->db->insert('saran', $post);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Saran Dikirim!');
                return redirect(site_url('kritik-saran'));
            }
            $this->session->set_flashdata('error', 'Saran Gagal Dikirim!');
            return redirect(site_url('kritik-saran'));
        }

        $data = [
            'title' => 'Buat Saran dan Kritik'
        ];
        $this->template->load('template/frontend', 'frontend/kritik-saran/create', $data);
    }

    public function show($id)
    {
        if (!$this->akses->access_rights_aksi('backoffice/kritik-saran/detail')) redirect('404_override', 'refresh');
        $saran = $this->saran->get(['saranId' => $id]);
        if ($saran->num_rows() < 1) {
            $this->session->set_flashdata('error', 'Saran tidak ditemukan!');
            return redirect(site_url('kritik-saran'));
        }
        $detail = $this->saran->get_detail(['sdSaranId' => $id], ['sdDibuatPada' => 'ASC']);

        $config_form = [
            [
                'field' => 'sdText',
                'label' => 'Tanggapan',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');
        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == TRUE) {

            $post = $this->input->post(null, TRUE);
            $post['sdId']  = $this->uuid->v4();
            $post['sdSaranId'] = $id;
            $post['sdAdminId']  = $this->ion_auth->user()->row()->pengId;
            $post['sdDibuatPada'] = date('Y-m-d H:i:s');

            $this->db->insert('saran_detail', $post);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Tanggapan Dikirim!');
                return redirect(site_url("backoffice/kritik-saran/{$id}/detail"));
            }
            $this->session->set_flashdata('error', 'tanggapan Gagal Dikirim!');
            return redirect(site_url("backoffice/kritik-saran/{$id}/detail"));
        }

        $data = [
            'title'     => 'Detail Saran dan Kritik',
            'saran' => $saran->row(),
            'detail' => $detail->result()
        ];
        $this->template->load('template/dasbor', 'backoffice/admin/kritik-saran/show', $data);
    }

    public function destroy($id)
    {
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

        $saran = $this->saran->get(['saranId' => $id]);
        if ($saran->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/kritik-saran'));
        }

        $this->saran->destroy(['saranId' => $saran->row()->saranId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('saran', 'hapus', $saran->row()->saranJudul);
            $this->session->set_flashdata('success', 'Berhasil hapus saran!');
            return redirect(site_url('backoffice/kritik-saran'));
        }

        activity_log('saran', 'gagal hapus', $saran->row()->saranJudul);
        $this->session->set_flashdata('error', 'Gagal hapus saran!');
        return redirect(site_url('backoffice/saran'));
    }

    public function destroy_detail($id)
    {
        $detail = $this->saran->get_detail(['sdId' => $id]);
        if ($detail->num_rows() < 1) {
            $this->session->set_flashdata('error', 'Saran tidak ditemukan!');
            return redirect($_SERVER['HTTP_REFERER']);
        }

        $saran_id = $detail->row()->sdSaranId;

        $post['sdText'] = "<p class=\"fst-italic\">Tanggapan telah Dihapus...</p>";
        $post['sdStatusHapus'] = 1;
        $this->db->update('saran_detail', $post, ['sdId' => $id]);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Tanggapan Dihapus!');
            return redirect(site_url("backoffice/kritik-saran/{$saran_id}/detail"));
        }
        $this->session->set_flashdata('error', 'tanggapan Gagal Dihapus!');
        return redirect(site_url("backoffice/kritik-saran/{$saran_id}/detail"));
    }
}
