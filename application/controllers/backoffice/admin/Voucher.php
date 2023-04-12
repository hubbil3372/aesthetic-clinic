<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Voucher extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Voucher_model', 'voucher');
    }

    /**----------------------------------------------------
     * Daftar Voucher
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Voucher',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu,
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/voucher/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->voucher->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/voucher/{$field->voucherId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/voucher/{$field->voucherId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/voucher/{$field->voucherId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->voucherId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = '<img class="img img-fluid w-100" src="' . base_url('_uploads/voucher/' . $field->voucherGambar) . '">';
            $row[] = $field->voucherNama;
            $row[] = $field->voucherKode;
            $row[] = 'Rp' . $field->voucherPotongan;
            $row[] = $field->voucherStatus == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->voucher->count_all(),
            "recordsFiltered" => $this->voucher->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah Voucher
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
                'field' => 'voucherNama',
                'label' => 'Voucher',
                'rules' => 'required'
            ],
            [
                'field' => 'voucherKode',
                'label' => 'Kode',
                'rules' => 'required'
            ],
            [
                'field' => 'voucherPotongan',
                'label' => 'Potongan',
                'rules' => 'required'
            ],
            [
                'field' => 'voucherGambar',
                'label' => 'Gambar',
                'rules' => 'callback_check_file'
            ],
            [
                'field' => 'voucherStatus',
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
                'title' => 'Tambah Voucher'
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/voucher/create', $data);
        } else {
            $post = $this->input->post(null, true);

            if (@$_FILES['voucherGambar']['name'] != "") {
                $post['voucherGambar'] = $this->_uploadFile('./_uploads/voucher/', 'png|jpg|jpeg', 2048, 'VOUCHER_', 'voucherGambar');
            }

            $this->voucher->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('voucher', 'tambah', $post['voucherNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah voucher!');
                return redirect(site_url('backoffice/voucher'));
            }

            activity_log('voucher', 'gagal tambah', $post['voucherNama']);
            $this->session->set_flashdata('error', 'Gagal tambah voucher!');
            return redirect(site_url('backoffice/voucher'));
        }
    }

    /**----------------------------------------------------
     * Ubah Voucher
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
                'field' => 'voucherNama',
                'label' => 'Voucher',
                'rules' => 'required'
            ],
            [
                'field' => 'voucherKode',
                'label' => 'Kode',
                'rules' => 'required'
            ],
            [
                'field' => 'voucherPotongan',
                'label' => 'Potongan',
                'rules' => 'required'
            ],
            [
                'field' => 'voucherStatus',
                'label' => 'Status',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $voucher = $this->voucher->get(['voucherId' => $id]);
        if ($voucher->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/voucher'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah Voucher',
                'voucher' => $voucher->row()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/voucher/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            if (@$_FILES['voucherGambar']['name'] != "") {
                $put['voucherGambar'] = $this->_uploadFile('./_uploads/voucher/', 'png|jpg|jpeg', 2048, 'VOUCHER_', 'voucherGambar', $voucher->row()->voucherGambar);
            }

            $this->voucher->update($put, ['voucherId' => $voucher->row()->voucherId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('voucher', 'ubah', "data {$put['voucherNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah voucher');
                return redirect(site_url('backoffice/voucher'));
            }

            activity_log('voucher', 'gagal ubah', "data {$put['voucherNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah voucher');
            return redirect(site_url('backoffice/voucher'));
        }
    }

    /**----------------------------------------------------
     * Hapus Voucher
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
        $voucher = $this->voucher->get(['voucherId' => $id]);
        if ($voucher->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/voucher'));
        }

        $this->voucher->destroy(['voucherId' => $voucher->row()->voucherId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('voucher', 'hapus', $voucher->row()->voucherNama);

            $file_gambar = $voucher->row()->voucherGambar;
            if ($file_gambar != 'default.png') {
                $dir_image = './_uploads/voucher/' . $file_gambar;
                if (file_exists($dir_image)) {
                    unlink($dir_image);
                }
            }

            $this->session->set_flashdata('success', 'Berhasil hapus voucher!');
            return redirect(site_url('backoffice/voucher'));
        }

        activity_log('voucher', 'gagal hapus', $voucher->row()->voucherNama);
        $this->session->set_flashdata('error', 'Gagal hapus voucher!');
        return redirect(site_url('backoffice/voucher'));
    }

    public function _uploadFile($url, $type, $size, $file_name, $name, $old = null, $link = null)
    {
        // config image
        $config['upload_path']          = $url;
        $config['allowed_types']        = $type;
        $config['max_size']             = $size;
        $config['file_name']            = $file_name . date('YmdHis') . '_' . rand(1000, 9999);

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            if ($old != null) {
                $file_gambar = $old;
                if ($file_gambar != 'default.png') {
                    $dir_image = $url . $file_gambar;
                    if (file_exists($dir_image)) {
                        unlink($dir_image);
                    }
                }
            }
            return $this->upload->data('file_name');
        } else {
            $error_file = $this->upload->display_errors();
            $this->session->set_flashdata('error', strip_tags($error_file) . $name .  ' ' . $type);
            if ($link != null) return redirect(site_url('backoffice/voucher'));

            return redirect(site_url('backoffice/voucher'));
        }
    }

    function check_file()
    {
        $file = @$_FILES['voucherGambar']['name'];
        if (!$file) {
            $this->form_validation->set_message('check_file', '{field} Tidak boleh kosong!');
            return false;
        }
        return true;
    }

}
