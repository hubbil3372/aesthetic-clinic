<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Checkout_model', 'checkout');
        $this->load->model('Customer_model', 'customer');
        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Produk_model', 'produk');
    }


    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'start',
                'label' => 'Tanggal Awal',
                'rules' => 'required'
            ],
            [
                'field' => 'end',
                'label' => 'Tanggal Akhir',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $data = [
                'title'         => 'Laproan',
                'tot_checkout'  => $this->checkout->count_all(),
                'tot_customer'  => $this->customer->count_all(),
                'tot_voucher'   => $this->voucher->count_all(),
                'tot_produk'    => $this->produk->count_all(),
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/laporan/index', $data);
        } else {
            $post = $this->input->post(null, true);

            $uri = site_url('backoffice/dasbor/cetak-laporan?');
            foreach ($post as $key => $v) {
                $uri .= $key . '=' . $v . '&';
            }
            return redirect($uri);
        }
    }

    public function cetak_laporan_treatment()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('warning', 'Sesi Anda Telah Habis');
            return redirect(site_url('backoffice/laporan'));
        }

        $config_form = [
            [
                'field' => 'dateStart',
                'label' => 'Tanggal Awal',
                'rules' => 'required'
            ],
            [
                'field' => 'dateEnd',
                'label' => 'Tanggal Akhir',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            if (form_error('dateStart')) {
                $this->session->set_flashdata('warning', strip_tags(form_error('dateStart')));
                return redirect(site_url('backoffice/laporan'));
            }
            if (form_error('dateEnd')) {
                $this->session->set_flashdata('warning', strip_tags(form_error('dateEnd')));
                return redirect(site_url('backoffice/laporan'));
            }
        }
        $user = $this->ion_auth->user()->row();
        $user_groups = $this->ion_auth->get_users_groups($user->id)->row();
        $where = ['bookingStatus' => 'selesai'];
        if ($user_groups->id == 5) {
            $dokter = $this->db->get_where('dokter', ['dokterPengId' => $user->pengId])->row()->dokterId;
            $where['jdDokterId'] = $dokter;
        }

        $post = $this->input->post(null, TRUE);
        $laporan = $this->db->join('customer', 'customer.customerId = booking.bookingCustomerId')
            ->join('booking_detail', 'booking_detail.bdBookingId = booking.bookingId')
            ->join('jadwal_dokter', 'jadwal_dokter.jdId = booking.bookingJdId', 'left')
            ->join('dokter', 'dokter.dokterId = jadwal_dokter.jdDokterId', 'left')
            ->where('bookingDibuatPada >=', $post['dateStart'])
            ->where('bookingDibuatPada <=', $post['dateEnd'])
            ->where($where)
            ->order_by('bookingDibuatPada', 'DESC')->get('booking')->result();

        if (!$laporan) {
            $this->session->set_flashdata('warning', 'Laporan Belum Tersedia!');
            return redirect(site_url('backoffice/laporan'));
        }

        $data = [
            'title' => 'Laporan Treatment',
            'laporan' => $laporan,
            'period' => ['start' => $post['dateStart'], 'end' => $post['dateEnd']]
        ];
        $this->load->view('backoffice/admin/laporan/laporan-treatment', $data);
    }

    public function cetak_laporan()
    {
        $get = $this->input->get(null, true);

        if (!$get) {
            $this->session->set_flashdata('error', 'Silahkan tentukan Tanggal Awal dan Tanggal Akhir terlebih dahulu!');
            return redirect(site_url('backoffice/dasbor'));
        }

        $where = [
            'checkoutDibuatPada >=' => $get['start'],
            'checkoutDibuatPada <=' => $get['end']
        ];

        $checkout = $this->checkout->get($where, 'checkoutDibuatPada DESC')->result();

        foreach ($checkout as $key => $v) {
            $checkout[$key]->detail = $this->checkout->get_detail(['detailCheckoutId' => $v->checkoutId])->result();
        }

        $data = [
            'title' => 'Cetak Laporan',
            'transaksi' => $checkout
        ];

        $this->load->view('backoffice/admin/dasbor/cetak_laporan', $data);
    }
}
