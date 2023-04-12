<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Konsultasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('konsultasi_model', 'konsultasi');
        $this->load->model('dokter_model', 'dokter');
        $this->load->model('pengguna_model', 'pengguna');
    }

    /**----------------------------------------------------
     * Daftar konsultasi
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'konsultasi',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/konsultasi/index', $data);
    }

    public function show($id)
    {
        if (!$this->akses->access_rights_aksi('backoffice/konsultasi/detail')) redirect('404_override', 'refresh');
        $konsultasi = $this->konsultasi->get(['konsultasiId' => $id]);
        if ($konsultasi->num_rows() < 1) {
            $this->session->set_flashdata('error', 'konsultasi tidak ditemukan!');
            return redirect(site_url('konsultasi'));
        }
        $detail = $this->db->where(['kdKonsultasiId' => $id])->join('customer', 'customer.customerId = konsultasi_detail.kdCustomerId', 'left')
            ->order_by('kdDibuatPada', 'ASC')->get('konsultasi_detail');

        $config_form = [
            [
                'field' => 'kdTeks',
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

            $dokter = $this->dokter->get(['dokterPengId' => $this->ion_auth->user()->row()->pengId]);
            if ($dokter->num_rows() < 1) {
                $this->session->set_flashdata('success', 'Anda Bukan Dokter!');
                return redirect(site_url("backoffice/konsultasi/{$id}/detail"));
            }

            $post = $this->input->post(null, TRUE);
            $post['kdKonsultasiId'] = $id;
            $post['kdDokterId']  = $dokter->row()->dokterId;

            // var_dump($post);
            // return;

            $this->konsultasi->detail_create($post);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Tanggapan Dikirim!');
                return redirect(site_url("backoffice/konsultasi/{$id}/detail"));
            }
            $this->session->set_flashdata('error', 'tanggapan Gagal Dikirim!');
            return redirect(site_url("backoffice/konsultasi/{$id}/detail"));
        }

        $data = [
            'title'     => 'Detail konsultasi dan Kritik',
            'konsultasi' => $konsultasi->row(),
            'detail' => $detail->result()
        ];
        $this->template->load('template/dasbor', 'backoffice/admin/konsultasi/show', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->konsultasi->get_datatables();
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
            if ($this->akses->access_rights_aksi("backoffice/konsultasi/detail")) $button .= "<a class='btn btn-sm btn-outline-primary me-1 waitme' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/detail") . "'>Detail</a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger me-1 destroy' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->konsultasiJudul;
            $row[] = $field->customerNama;
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->konsultasi->count_all(),
            "recordsFiltered" => $this->konsultasi->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Hapus konsultasi
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
        $konsultasi = $this->konsultasi->get(['konsultasiId' => $id]);
        if ($konsultasi->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/konsultasi'));
        }

        $this->konsultasi->destroy(['konsultasiId' => $konsultasi->row()->konsultasiId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('konsultasi', 'hapus', $konsultasi->row()->konsultasiJudul);

            $this->session->set_flashdata('success', 'Berhasil hapus konsultasi!');
            return redirect(site_url('backoffice/konsultasi'));
        }

        activity_log('konsultasi', 'gagal hapus', $konsultasi->row()->konsultasiJudul);
        $this->session->set_flashdata('error', 'Gagal hapus konsultasi!');
        return redirect(site_url('backoffice/konsultasi'));
    }


    /**----------------------------------------------------
     * Hapus konsultasi
  -------------------------------------------------------**/
    public function destroy_detail_konsul($id)
    {
        $kd = $this->db->get_where('konsultasi_detail', ['kdId' => $id]);
        if ($kd->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/konsultasi'));
        }

        $this->db->update('konsultasi_detail', ['kdTeks' => 'Anda Telah menghapus Tanggapan ini', 'kdStatusHapus' => 1], ['kdId' => $kd->row()->kdId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('detail konsultasi', 'hapus', $kd->row()->kdId);
            return redirect(site_url("backoffice/konsultasi/{$kd->row()->kdKonsultasiId}/detail"));
        }

        activity_log('detail konsultasi', 'gagal hapus', $kd->row()->kdJudul);
        $this->session->set_flashdata('error', 'Konsul Gagal Dihapus!');
        return redirect(site_url('backoffice/konsultasi'));
    }
}
