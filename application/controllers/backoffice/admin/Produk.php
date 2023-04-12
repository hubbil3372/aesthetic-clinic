<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Produk_model', 'produk');
        $this->load->model('Kategori_model', 'kategori');
    }

    /**----------------------------------------------------
     * Daftar Produk
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Produk',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/produk/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->produk->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/produk/{$field->produkId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/produk/{$field->produkId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/produk/{$field->produkId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->produkId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = '<img class="img img-fluid w-100 mb-2" src="' . base_url('_uploads/produk/' . $field->produkGambar1) . '">';
            $row[] = $field->produkNama;
            $row[] = $field->kategoriNama;
            $row[] = '<b>Rp' . $field->produkHarga . '</b><br>' . 'Rp' . $field->produkDiskon;
            $row[] = $field->produkStok;
            $row[] = $field->produkBerat;
            $row[] = $field->produkStatus == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->produk->count_all(),
            "recordsFiltered" => $this->produk->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah Produk
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
                'field' => 'produkNama',
                'label' => 'Produk',
                'rules' => 'required'
            ],
            [
                'field' => 'produkKategoriId',
                'label' => 'Kategori',
                'rules' => 'required'
            ],
            [
                'field' => 'produkDeskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ],
            [
                'field' => 'produkHarga',
                'label' => 'Harga',
                'rules' => 'required'
            ],
            [
                'field' => 'produkDiskon',
                'label' => 'Diskon',
                'rules' => 'required'
            ],
            [
                'field' => 'produkStok',
                'label' => 'Stok',
                'rules' => 'required'
            ],
            [
                'field' => 'produkBerat',
                'label' => 'Berat',
                'rules' => 'required'
            ],
            [
                'field' => 'produkGambar1',
                'label' => 'Gambar 1',
                'rules' => 'callback_check_file'
            ],
            [
                'field' => 'produkStatus',
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
                'title'     => 'Tambah Produk',
                'kategori'  => $this->kategori->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/produk/create', $data);
        } else {
            $post = $this->input->post(null, true);

            if (@$_FILES['produkGambar1']['name'] != "") {
                $post['produkGambar1'] = $this->_uploadFile('./_uploads/produk/', 'png|jpg|jpeg', 2048, 'PRODUK_', 'produkGambar1');
            }

            if (@$_FILES['produkGambar2']['name'] != "") {
                $post['produkGambar2'] = $this->_uploadFile('./_uploads/produk/', 'png|jpg|jpeg', 2048, 'PRODUK_', 'produkGambar2');
            }

            if (@$_FILES['produkGambar3']['name'] != "") {
                $post['produkGambar3'] = $this->_uploadFile('./_uploads/produk/', 'png|jpg|jpeg', 2048, 'PRODUK_', 'produkGambar3');
            }

            $this->produk->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('produk', 'tambah', $post['produkNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah produk!');
                return redirect(site_url('backoffice/produk'));
            }

            activity_log('produk', 'gagal tambah', $post['produkNama']);
            $this->session->set_flashdata('error', 'Gagal tambah produk!');
            return redirect(site_url('backoffice/produk'));
        }
    }

    /**----------------------------------------------------
     * Ubah Produk
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
                'field' => 'produkNama',
                'label' => 'Produk',
                'rules' => 'required'
            ],
            [
                'field' => 'produkKategoriId',
                'label' => 'Kategori',
                'rules' => 'required'
            ],
            [
                'field' => 'produkDeskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ],
            [
                'field' => 'produkHarga',
                'label' => 'Harga',
                'rules' => 'required'
            ],
            [
                'field' => 'produkDiskon',
                'label' => 'Diskon',
                'rules' => 'required'
            ],
            [
                'field' => 'produkStok',
                'label' => 'Stok',
                'rules' => 'required'
            ],
            [
                'field' => 'produkBerat',
                'label' => 'Berat',
                'rules' => 'required'
            ],
            [
                'field' => 'produkStatus',
                'label' => 'Status',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $produk = $this->produk->get(['produkId' => $id]);
        if ($produk->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/produk'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah Produk',
                'produk' => $produk->row(),
                'kategori'  => $this->kategori->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/produk/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            if (@$_FILES['produkGambar1']['name'] != "") {
                $put['produkGambar1'] = $this->_uploadFile('./_uploads/produk/', 'png|jpg|jpeg', 2048, 'PRODUK_', 'produkGambar1', $produk->row()->produkGambar1);
            }

            if (@$_FILES['produkGambar2']['name'] != "") {
                $put['produkGambar2'] = $this->_uploadFile('./_uploads/produk/', 'png|jpg|jpeg', 2048, 'PRODUK_','produkGambar2', $produk->row()->produkGambar2);
            }

            if (@$_FILES['produkGambar3']['name'] != "") {
                $put['produkGambar3'] = $this->_uploadFile('./_uploads/produk/', 'png|jpg|jpeg', 2048, 'PRODUK_','produkGambar3', $produk->row()->produkGambar3);
            }

            $this->produk->update($put, ['produkId' => $produk->row()->produkId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('produk', 'ubah', "data {$put['produkNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah produk');
                return redirect(site_url('backoffice/produk'));
            }

            activity_log('produk', 'gagal ubah', "data {$put['produkNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah produk');
            return redirect(site_url('backoffice/produk'));
        }
    }

    /**----------------------------------------------------
     * Hapus Produk
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
        $produk = $this->produk->get(['produkId' => $id]);
        if ($produk->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/produk'));
        }

        $this->produk->destroy(['produkId' => $produk->row()->produkId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('produk', 'hapus', $produk->row()->produkNama);

            $file_gambar = $produk->row()->produkGambar1;
            if ($file_gambar != 'default.png') {
                $dir_image = './_uploads/produk/' . $file_gambar;
                if (file_exists($dir_image)) {
                    unlink($dir_image);
                }
            }

            $file_gambar = $produk->row()->produkGambar2;
            if ($file_gambar != 'default.png') {
                $dir_image = './_uploads/produk/' . $file_gambar;
                if (file_exists($dir_image)) {
                    unlink($dir_image);
                }
            }

            $file_gambar = $produk->row()->produkGambar3;
            if ($file_gambar != 'default.png') {
                $dir_image = './_uploads/produk/' . $file_gambar;
                if (file_exists($dir_image)) {
                    unlink($dir_image);
                }
            }

            $this->session->set_flashdata('success', 'Berhasil hapus produk!');
            return redirect(site_url('backoffice/produk'));
        }

        activity_log('produk', 'gagal hapus', $produk->row()->produkNama);
        $this->session->set_flashdata('error', 'Gagal hapus produk!');
        return redirect(site_url('backoffice/produk'));
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
            if ($link != null) return redirect(site_url('backoffice/produk'));

            return redirect(site_url('backoffice/produk'));
        }
    }

    function check_file()
    {
        $file = @$_FILES['produkGambar1']['name'];
        if (!$file) {
            $this->form_validation->set_message('check_file', '{field} Tidak boleh kosong!');
            return false;
        }
        return true;
    }

}
