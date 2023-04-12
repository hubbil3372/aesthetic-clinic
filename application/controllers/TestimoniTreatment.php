<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class TestimoniTreatment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_no_login();
        $this->load->library('uuid');

        $this->load->model('Booking_model', 'booking');
        $this->load->model('Testimoni_treatment_model', 'testi');
    }

    /**----------------------------------------------------
     * Tambah Testimoni
  -------------------------------------------------------**/
    public function create($booking_id)
    {
        $booking = $this->booking->get(['bookingId' => $booking_id]);
        if ($booking->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('booking-treatment'));
        }

        $booking = $booking->row();
        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'testiJudul',
                'label' => 'Judul',
                'rules' => 'required'
            ],
            [
                'field' => 'testiTeks',
                'label' => 'testimoni',
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
            $post['testiCustomerId'] = $this->front_auth->logged_data()->customerId;
            $post['testiTreatmentId'] = $booking->bookingTreatmentId;
            $post['testiBookingId'] = $booking->bookingId;

            // print_r($post);
            // return;

            $testimoni_check = $this->db->get_where('testimoni_treatment', ['testiCustomerId' => $this->front_auth->logged_data()->customerId, 'testiTreatmentId' => $booking->bookingTreatmentId, 'testiBookingId' => $booking->bookingId])->num_rows();
            if ($testimoni_check > 0) {
                $this->session->set_flashdata('success', 'Anda Sudah Memberikan Ulasan!');
                return redirect(site_url('booking-treatment/' . $booking->bookingId . '/lihat'));
            }
            $this->testi->create($post);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Berhasil tambah ulasan!');
                return redirect(site_url('booking-treatment/' . $booking->bookingId . '/lihat'));
            }
            $this->session->set_flashdata('error', 'Gagal tambah ulasan!');
            return redirect(site_url('testimoni/' . $booking->bookingId . '/create'));
        }

        $data = [
            'title'     => 'Tambah Ulasan Treatment',
            'booking' => $booking
        ];

        $this->template->load('template/frontend', 'frontend/testimoni-treatment/create', $data);
    }
}
