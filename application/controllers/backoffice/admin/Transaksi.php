<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Checkout_model', 'checkout');
        $this->load->model('Raja_ongkir_model', 'rajaongkir');
        $this->load->model('Testimoni_model', 'testimoni');
    }

    /**----------------------------------------------------
     * Daftar Transaksi
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'Transaksi',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/transaksi/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->checkout->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/transaksi/{$field->checkoutId}/ubah") . "'><i class='fas fa-eye'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/transaksi/{$field->checkoutId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/transaksi/{$field->checkoutId}/hapus") . "'></i></a>";

            if ($button == '') $button = '-';

            if ($field->checkoutStatusBayar == 0) {
                $status_bayar = '<span class="badge bg-warning">Menunggu pembayaran</span>';
            }
            if ($field->checkoutStatusBayar == 1) {
                $status_bayar = '<span class="badge bg-success">Sudah dibayar</span>';
            }
            if ($field->checkoutStatusBayar == 2) {
                $status_bayar = '<span class="badge bg-danger">Bukti pembayaran ditolak</span>';
            }

            if ($field->checkoutStatusPengiriman == 0) {
                $status_kirim = '<span class="badge bg-warning">Belum dikirim</span>';
            }
            if ($field->checkoutStatusPengiriman == 1) {
                $status_kirim = '<span class="badge bg-info">Sedang dikirim</span>';
            }
            if ($field->checkoutStatusPengiriman == 2) {
                $status_kirim = '<span class="badge bg-success">Pesanan sudah sampai</span>';
            }

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->checkoutKode;
            $row[] = $field->customerNama;
            $row[] = $status_bayar;
            $row[] = $status_kirim;
            $row[] = 'Rp' . $field->checkoutTotalTagihan;
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->checkout->count_all(),
            "recordsFiltered" => $this->checkout->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Ubah Transaksi
  -------------------------------------------------------**/
    public function update($id)
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

        $transaksi = $this->checkout->get(['checkoutId' => $id])->row();

        $pengiriman = json_decode($this->rajaongkir->waybill($transaksi->checkoutNoResi, $transaksi->checkoutKurirNama))->rajaongkir;
        if (isset($pengiriman->result)) $pengiriman = $pengiriman->result;

        $data = [
            'title' => 'Detail Transaksi',
            'transaksi' => $transaksi,
            'transaksi_det' => $this->checkout->get_detail(['detailCheckoutId' => $id])->result(),
            'pengiriman'    => $pengiriman,
            'testimoni' => $this->testimoni->get(['testimoniCheckoutId' => $id])->result()
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/transaksi/update', $data);
    }

    public function status_bayar($id)
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

        $status = 2; //ditolak
        if ($this->input->get('status') == 'terima')  $status = 1; //diterima

        $put['checkoutStatusBayar'] = $status;

        $this->checkout->update($put, ['checkoutId' => $id]);
        if ($this->db->affected_rows() > 0) {
            activity_log('checkout', 'ubah', "data {$id}");

            $this->session->set_flashdata('success', 'Berhasil ubah ststus bayar');
            return redirect(site_url('backoffice/transaksi/' . $id . '/ubah'));
        }

        activity_log('checkout', 'gagal ubah', "data {$id}");
        $this->session->set_flashdata('error', 'Gagal ubah status bayar');
        return redirect(site_url('backoffice/transaksi/' . $id . '/ubah'));
    }

    public function update_resi($id)
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

        $put = $this->input->post(null, true);
        $put['checkoutStatusPengiriman'] = 1;

        $this->checkout->update($put, ['checkoutId' => $id]);
        if ($this->db->affected_rows() > 0) {
            activity_log('checkout', 'ubah', "data {$id}");

            $this->session->set_flashdata('success', 'Berhasil ubah ststus pengiriman dan nomor resi');
            return redirect(site_url('backoffice/transaksi/' . $id . '/ubah'));
        }

        activity_log('checkout', 'gagal ubah', "data {$id}");
        $this->session->set_flashdata('error', 'Gagal ubah ststus pengiriman dan nomor resi');
        return redirect(site_url('backoffice/transaksi/' . $id . '/ubah'));
    }

    public function testimoni($checkoutId)
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
                'field' => 'testimoniId[]',
                'label' => 'Id testimoni',
                'rules' => 'required'
            ],
            [
                'field' => 'testimoniBalasan[]',
                'label' => 'Balasan',
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
                'title' => 'Balas Testimoni',
                /**----------------------------------------------------
                 * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
                'menu_id' => $menu,
                'produk'    => $this->checkout->get_detail(['detailCheckoutId' => $checkoutId])->result(),
                'testimoni' => $this->testimoni->get(['testimoniCheckoutId' => $checkoutId])->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/transaksi/testimoni', $data);
        } else {
            $post = $this->input->post(null, true);

            foreach ($post['testimoniId'] as $key => $v) {
                $data[$key]['testimoniId'] = $v;
            }

            foreach ($post['testimoniBalasan'] as $key => $v) {
                $data[$key]['testimoniBalasan'] = $v;
            }

            foreach ($data as $key => $v) {
                $this->testimoni->update(['testimoniBalasan' => $v['testimoniBalasan']], ['testimoniId' => $v['testimoniId']]);
            }

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('success', 'Berhasil tambah ulasan!');
                return redirect(site_url('backoffice/transaksi/' . $checkoutId . '/ubah'));
            }

            $this->session->set_flashdata('error', 'Gagal tambah ulasan!');
            return redirect(site_url('backoffice/transaksi/' . $checkoutId . '/testimoni'));
        }
    }
}
