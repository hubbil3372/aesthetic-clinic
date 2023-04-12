<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Konsultasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->front_auth->logged_in();
        $this->load->model('konsultasi_model', 'konsultasi');
        $this->load->model('pengguna_model', 'pengguna');
    }

    /**----------------------------------------------------
     * Daftar konsultasi
  -------------------------------------------------------**/
    public function index()
    {
        $customer_id = $this->front_auth->logged_data()->customerId;
        $cari = $this->input->get('cari');
        $where = ['konsultasiCustomerId' => $customer_id];
        $like = [];
        if ($cari) {
            $like = ['konsultasiJudul' => strtolower($cari)];
        }

        $this->load->library('pagination');

        $get = $this->input->get(null, true);
        unset($get['per_page']);
        $uri = http_build_query($get);

        $config['base_url'] = base_url('konsultasi/index/?' . $uri);
        $config['total_rows'] = $this->konsultasi->count_all_results($where, $like);
        $config['per_page'] = 8;
        $config['page_query_string'] = TRUE;

        $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = 'Awal';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = '';

        $config['last_link'] = 'Akhir';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'page-link');

        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $this->pagination->initialize($config);

        $konsultasi_data = $this->konsultasi->get($where, ['konsultasiDibuatPada' => 'DESC'], [$config['per_page'], $start], $like);
        $data = [
            'title' => 'konsultasi anda',
            'konsultasi' => $konsultasi_data->result(),
            'tanggapan' => 0
        ];

        if ($konsultasi_data->num_rows() > 0) {
            $data['tanggapan'] = $this->db->from('konsultasi_detail')->where(['kdKonsultasiId' => $konsultasi_data->row()->konsultasiId])->count_all_results();
        }

        $this->template->load('template/frontend', 'frontend/konsultasi/index', $data);
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/hapus") . "'></i></a>";
            if ($this->akses->access_rights_aksi("backoffice/konsultasi/detail")) $button .= "<a class='btn btn-sm btn-outline-primary me-1 waitme' href='" . site_url("backoffice/konsultasi/{$field->konsultasiId}/lihat") . "'>Detail</a>";

            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->konsultasiId == 1) $button = '-';

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
     * Tambah konsultasi
  -------------------------------------------------------**/
    public function show($konsultasi_id)
    {
        $konsultasi = $this->konsultasi->get(['konsultasiId' => $konsultasi_id]);
        if ($konsultasi->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('konsultasi'));
        }

        $config_form = [
            [
                'field' => 'kdTeks',
                'label' => 'Tanggapan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == true) {
            $post = $this->input->post(null, true);
            $post['kdKonsultasiId'] = $konsultasi_id;
            $post['kdCustomerId'] = $this->front_auth->logged_data()->customerId;
            $this->konsultasi->detail_create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('konsultasi', 'tambah', $post['kdTeks']);
                return redirect(site_url("konsultasi/{$konsultasi_id}/detail"));
            }
            activity_log('konsultasi', 'gagal tambah', $post['kdTeks']);
            $this->session->set_flashdata('error', 'Gagal tambah konsultasi!');
            return redirect(site_url("konsultasi/{$konsultasi_id}/detail"));
        } else {

            $data = [
                'title' => 'Detail konsultasi',
                'konsultasi' => $konsultasi->row(),
                'detail' => $this->db->from('konsultasi_detail')
                    ->join('dokter', 'dokter.dokterId = konsultasi_detail.kdDokterId', 'left')
                    ->join('customer', 'customer.customerId = konsultasi_detail.kdCustomerId', 'left')
                    ->where(['kdKonsultasiId' => $konsultasi->row()->konsultasiId])
                    ->order_by('kdDibuatPada', 'ASC')
                    ->get()->result()
            ];
            $this->template->load('template/frontend', 'frontend/konsultasi/show', $data);
        }
    }

    /**----------------------------------------------------
     * Tambah konsultasi
  -------------------------------------------------------**/
    public function create()
    {
        $config_form = [
            [
                'field' => 'konsultasiJudul',
                'label' => 'Judul',
                'rules' => 'required'
            ],
            [
                'field' => 'konsultasiTeks',
                'label' => 'Konsultasi',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == true) {
            $post = $this->input->post(null, true);
            $post['konsultasiCustomerId'] = $this->front_auth->logged_data()->customerId;

            $this->konsultasi->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('konsultasi', 'tambah', $post['konsultasiJudul']);

                $this->session->set_flashdata('success', 'Berhasil tambah konsultasi!');
                return redirect(site_url('konsultasi'));
            }

            activity_log('konsultasi', 'gagal tambah', $post['konsultasiJudul']);
            $this->session->set_flashdata('error', 'Gagal tambah konsultasi!');
            return redirect(site_url('konsultasi'));
        } else {

            $data = [
                'title' => 'Tambah konsultasi',
            ];
            $this->template->load('template/frontend', 'frontend/konsultasi/create', $data);
        }
    }

    /**----------------------------------------------------
     * Ubah konsultasi
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
                'field' => 'konsultasiJudul',
                'label' => 'Nama konsultasi',
                'rules' => 'required'
            ],
            [
                'field' => 'konsultasiSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'konsultasiPengId',
                'label' => 'Pengguna',
                'rules' => 'required|callback_check_konsultasi_update'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $konsultasi = $this->konsultasi->get(['konsultasiId' => $id]);
        if ($konsultasi->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/konsultasi'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah konsultasi',
                'konsultasi' => $konsultasi->row(),
                'spesialis' => $this->db->get('konsultasi_spesialis')->result(),
                'pengguna' => $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/konsultasi/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);
            unset($put['konsultasiId']);
            $this->konsultasi->update($put, ['konsultasiId' => $konsultasi->row()->konsultasiId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('konsultasi', 'ubah', "data {$put['konsultasiJudul']}");

                $this->session->set_flashdata('success', 'Berhasil ubah konsultasi');
                return redirect(site_url('backoffice/konsultasi'));
            }

            activity_log('konsultasi', 'gagal ubah', "data {$put['konsultasiJudul']}");
            $this->session->set_flashdata('error', 'Gagal ubah konsultasi');
            return redirect(site_url('backoffice/konsultasi'));
        }
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
}
