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
        // cek_no_login();
        $this->load->model('Treatment_model', 'treatment');
    }

    /**----------------------------------------------------
     * Daftar treatment
  -------------------------------------------------------**/
    public function index()
    {
        $cari = $this->input->get('cari');
        $where = ['treatmentStatus' => 1];

        $like = ['treatmentNama' => strtolower($cari)];


        $this->load->library('pagination');

        $get = $this->input->get(null, true);
        unset($get['per_page']);
        $uri = http_build_query($get);

        $config['base_url'] = base_url('treatment/index/?' . $uri);
        $config['total_rows'] = $this->treatment->count_all($where, $like);
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

        $data = [
            'title' => 'Daftar Treatment',
            'treatment' => $this->treatment->get($where, ['treatmentDibuatPada' => 'DESC'], [$config['per_page'], $start], $like)
        ];
        $this->template->load('template/frontend', 'frontend/treatment/index', $data);
    }


    /**----------------------------------------------------
     * Lihat
  -------------------------------------------------------**/
    public function view($id)
    {
        $treatment = $this->treatment->get(['treatmentId' => $id, 'treatmentStatus' => 1])->row();

        if (!$treatment) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('treatment'));
        }

        $terkait = $this->db->from('treatment')->where(['treatmentId !=' => $treatment->treatmentId, 'treatmentStatus' => 1], ['treatmentDibuatPada' => 'DESC'], [10, 0])->get()->result();

        $data = [
            'title'     => $treatment->treatmentNama,
            'treatment'    => $treatment,
            'testimoni' => $this->db->get_where('testimoni_treatment', ['testiTreatmentId' => $treatment->treatmentId])->result(),
            'terkait'   => $terkait
        ];

        $this->template->load('template/frontend', 'frontend/treatment/show', $data);
    }
}
