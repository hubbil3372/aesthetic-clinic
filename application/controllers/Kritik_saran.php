<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Kritik_saran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->front_auth->logged_in();
        $this->load->library('uuid');
        $this->load->model('Kritik_saran_model', 'saran');
    }

    /**----------------------------------------------------
     * Beranda
  -------------------------------------------------------**/
    public function index()
    {
        $saran  = $this->db->get_where('saran', ['saranCustomerId' => $this->front_auth->logged_data()->customerId]);

        $saran_data = $saran->result();
        foreach ($saran_data as $key => $value) {
            $saran_data[$key]->tanggapan = $this->db->from('saran_detail')->where(['sdSaranId' => $value->saranId])->count_all_results();
        }
        $data = [
            'title'     => 'Saran dan Kritik',
            'saran' => $saran_data
        ];

        $this->template->load('template/frontend', 'frontend/kritik-saran/index', $data);
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
            $post['sdCustomerId']  = $this->front_auth->logged_data()->customerId;
            $post['sdDibuatPada'] = date('Y-m-d H:i:s');

            $this->db->insert('saran_detail', $post);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Tanggapan Dikirim!');
                return redirect(site_url("kritik-saran/{$id}/detail"));
            }
            $this->session->set_flashdata('error', 'tanggapan Gagal Dikirim!');
            return redirect(site_url("kritik-saran/{$id}/detail"));
        }

        $data = [
            'title' => 'Detail Saran dan Kritik',
            'saran' => $saran->row(),
            'detail' => $detail->result()
        ];
        $this->template->load('template/frontend', 'frontend/kritik-saran/show', $data);
    }
}
