<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Treatment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');
        $this->load->model('Treatment_model', 'treatment');
    }

    /**----------------------------------------------------
     * Daftar treatment
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Treatment/Perawatan',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/treatment/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->treatment->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/treatment/{$field->treatmentId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/treatment/{$field->treatmentId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/treatment/{$field->treatmentId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->treatmentNama;
            $row[] = indo_currency($field->treatmentHarga);
            $row[] = indo_currency($field->treatmentDiskon);
            $row[] = $field->treatmentStatus == '1' ? 'Aktif' : 'Tidak Aktif';
            $row[] = '<img class="img-fluid" style="width:100px" src="' . base_url('_uploads/treatment/') . $field->treatmentFoto . '">';
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->treatment->count_all(),
            "recordsFiltered" => $this->treatment->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah treatment
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
                'field' => 'treatmentNama',
                'label' => 'Nama treatment',
                'rules' => 'required'
            ],
            [
                'field' => 'treatmentHarga',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'treatmentDiskon',
                'label' => 'Diskon',
                'rules' => 'numeric',
                'errors' => [
                    'numeric' => '{field} Gunakan Angka!'
                ]
            ],
            [
                'field' => 'treatmentStatus',
                'label' => 'Status',
                'rules' => 'required|numeric',
                'errors' => [
                    'numeric' => '{field} Gunakan Angka!'
                ]
            ],
            [
                'field' => 'treatmentDeskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ],
            [
                'field' => 'treatmentFoto',
                'label' => 'Foto Treatment',
                'rules' => 'callback_check_files'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $data = [
                'title' => 'Tambah treatment'
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/treatment/create', $data);
        } else {
            $post = $this->input->post(null, true);

            $config['upload_path']          = './_uploads/treatment';
            $config['allowed_types']        = 'png|jpg|jpeg';
            $config['max_size']             = 2048;
            $config['file_name']            = 'TREATMENT_' . date('YmdHis') . '_' . rand(1000, 9999);
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('treatmentFoto')) {
                $post['treatmentFoto'] = $this->upload->data('file_name');
            } else {
                $error_file = $this->upload->display_errors();
                $this->session->set_flashdata('error', strip_tags($error_file));
                return redirect(site_url("backoffice/treatment/tambah"));
            }

            $post['treatmentDiskon'] = empty($post['treatmentDiskon']) ? 0 : $post['treatmentDiskon'];
            $this->treatment->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('treatment', 'tambah', $post['treatmentNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah Treatment!');
                return redirect(site_url('backoffice/treatment'));
            }

            activity_log('treatment', 'gagal tambah', $post['treatmentNama']);
            $this->session->set_flashdata('error', 'Gagal tambah treatment!');
            return redirect(site_url('backoffice/treatment'));
        }
    }

    /**----------------------------------------------------
     * Ubah treatment
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
                'field' => 'treatmentNama',
                'label' => 'Nama treatment',
                'rules' => 'required'
            ],
            [
                'field' => 'treatmentHarga',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'treatmentDiskon',
                'label' => 'Diskon',
                'rules' => 'numeric',
                'errors' => [
                    'numeric' => '{field} Gunakan Angka!'
                ]
            ],
            [
                'field' => 'treatmentStatus',
                'label' => 'Status',
                'rules' => 'required|numeric',
                'errors' => [
                    'numeric' => '{field} Gunakan Angka!'
                ]
            ],
            [
                'field' => 'treatmentDeskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $treatment = $this->treatment->get(['treatmentId' => $id]);
        if ($treatment->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/treatment'));
        }
        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
         -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah Treatment',
                'treatment' => $treatment->row()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/treatment/update', $data);
        } else {
            $treatment = $treatment->row();
            $put = $this->input->post(null, TRUE);
            // print_r($put);
            // print_r($_FILES);
            // return;
            unset($put['treatmentId']);
            if ($_FILES['treatmentFoto']['name'] != null) {
                $config['upload_path']          = './_uploads/treatment';
                $config['allowed_types']        = 'png|jpg|jpeg';
                $config['max_size']             = 2048;
                $config['file_name']            = 'TREATMENT_' . date('YmdHis') . '_' . rand(1000, 9999);
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('treatmentFoto')) {
                    $image_file = $treatment->treatmentFoto;
                    if ($image_file != null) {
                        $path = $config['upload_path'] . '/' . $image_file;
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $put['treatmentFoto'] = $this->upload->data('file_name');
                } else {
                    $error_file = $this->upload->display_errors();
                    $this->session->set_flashdata('error', strip_tags($error_file));
                    return redirect(site_url("backoffice/treatment/tambah"));
                }
            }


            $this->treatment->update($put, ['treatmentId' => $treatment->treatmentId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('treatment', 'ubah', "data {$put['treatmentNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah treatment');
                return redirect(site_url('backoffice/treatment'));
            }

            activity_log('treatment', 'gagal ubah', "data {$put['treatmentNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah treatment');
            return redirect(site_url('backoffice/treatment'));
        }
    }

    /**----------------------------------------------------
     * Hapus treatment
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
        $treatment = $this->treatment->get(['treatmentId' => $id]);
        if ($treatment->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/treatment'));
        }

        $this->treatment->destroy(['treatmentId' => $treatment->row()->treatmentId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('treatment', 'hapus', $treatment->row()->treatmentNama);

            $this->session->set_flashdata('success', 'Berhasil hapus treatment!');
            return redirect(site_url('backoffice/treatment'));
        }

        activity_log('treatment', 'gagal hapus', $treatment->row()->treatmentNama);
        $this->session->set_flashdata('error', 'Gagal hapus treatment!');
        return redirect(site_url('backoffice/treatment'));
    }

    public function check_treatment_update()
    {
        $treatment = $this->db->get_where('treatment', ['treatmentPengId' => $_POST['treatmentPengId'], 'treatmentId !=' => $_POST['treatmentId']]);
        if ($treatment->num_rows() > 0) {
            $this->form_validation->set_message('check_treatment_update', '{field} Sudah digunakan!');
            return false;
        }
        return true;
    }

    public function check_files()
    {
        $key = key($_FILES);
        if (empty($_FILES[$key]['name'])) {
            $this->form_validation->set_message('check_files', '{field} file belum dipilih, silakan pilih foto');
            return false;
        }
        return true;
    }

    public function _uploadFile($path, $type, $size, $file_name, $name, $file_name_old = null, $link = null, $name_label = null)
    {
        // config image
        $config['upload_path']          = $path;
        $config['allowed_types']        = $type;
        $config['max_size']             = $size;
        $config['file_name']            = $file_name . date('YmdHis') . '_' . rand(1000, 9999);

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            if ($file_name_old != null) {
                $file_gambar = $file_name_old;
                if ($file_gambar != null) {
                    $dir_image = $path . $file_gambar;
                    if (file_exists($dir_image)) {
                        unlink($dir_image);
                    }
                }
            }
            return $this->upload->data('file_name');
        } else {
            $error_file = $this->upload->display_errors();
            $this->session->set_flashdata('error', strip_tags($error_file) . ' ' . ($name_label != null ? $name_label : null) .  ' ' . $type);
            if ($link != null) return redirect(site_url("backoffice/{$link}"));
            return redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
