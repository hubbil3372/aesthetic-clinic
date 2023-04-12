<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Booking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /**----------------------------------------------------
         * Cek apakah sudah login
    -------------------------------------------------------**/
        if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

        $this->load->model('Booking_model', 'booking');
    }

    /**----------------------------------------------------
     * Daftar booking
  -------------------------------------------------------**/
    public function index()
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
        if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

        $data = [
            'title' => 'booking',
            /**----------------------------------------------------
             * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
            'menu_id' => $menu
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/booking/index', $data);
    }

    /**----------------------------------------------------
     * Datatable
  -------------------------------------------------------**/
    public function get_json()
    {
        $list = $this->booking->get_datatables();
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
            if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/booking/{$field->bookingId}/ubah") . "'><i class='fas fa-edit'></i></a>";
            if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/booking/{$field->bookingId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/booking/{$field->bookingId}/hapus") . "'></i></a>";

            if ($this->akses->access_rights_aksi('backoffice/booking/detail')) $button .= "<a class='btn btn-sm btn-outline-primary ms-1' href='" . site_url("backoffice/booking/{$field->bookingId}/detail") . "'>Detail</a>";
            if ($button == '') $button = '-';

            /**----------------------------------------------------
             * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
            if ($field->bookingId == 1) $button = '-';

            $no++;
            $row = array();
            $row[] = "<div class='text-center'>{$no}</div>";
            $row[] = $field->bookingKode;
            $row[] = $field->bookingAntrian;
            $row[] = $field->bdTreatmentNama;
            $row[] = $field->dokterNama;
            $row[] = indo_date($field->bookingTgl);
            $row[] = status_booking($field->bookingStatus);
            $row[] = "<div class='text-center'>{$button}</div>";

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->booking->count_all(),
            "recordsFiltered" => $this->booking->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**----------------------------------------------------
     * Tambah booking
  -------------------------------------------------------**/
    public function show($booking_id)
    {
        /**----------------------------------------------------
         * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
        if (!$this->akses->access_rights_aksi('backoffice/booking/detail'))  redirect('404_override', 'refresh');

        $booking = $this->booking->get(['bookingId' => $booking_id]);
        if ($booking->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('backoffice/booking'));
        }

        $config_form = [
            [
                'field' => 'bookingTunai',
                'label' => 'Status',
                'rules' => 'required'
            ],
            [
                'field' => 'bookingKembali',
                'label' => 'bookingId',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        $post = $this->input->post(null, true);
        if ($this->form_validation->run() == TRUE) {
            $post['bookingStatusBayar'] = 'lunas';
            $post['bookingStatus'] = 'selesai';

            $this->booking->update($post, ['bookingId' => $booking->row()->bookingId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('booking', 'ubah', "data booking di proses");
                $this->session->set_flashdata('success', 'Transaksi Di Proses');
                return redirect(site_url("backoffice/booking/{$booking->row()->bookingId}/detail"));
            }
            activity_log('booking', 'gagal ubah', "data booking di proses");
            $this->session->set_flashdata('error', 'Transaksi Di Proses Gagal');
            return redirect(site_url("backoffice/booking/{$booking->row()->bookingId}/detail"));
        }

        $data = [
            'title' => 'Detail booking',
            'booking' => $booking->row()
        ];

        $this->template->load('template/dasbor', 'backoffice/admin/booking/show', $data);
    }

    public function cancel($booking_id)
    {
        $booking = $this->booking->get(['bookingId' => $booking_id]);
        if ($booking->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('backoffice/booking'));
        }

        $data = [
            'bookingStatusBayar' => 'dp',
            'bookingStatus' => 'diproses',
            'bookingTunai' => 0,
            'bookingKembali' => 0
        ];
        $this->booking->update($data, ['bookingId' => $booking->row()->bookingId]);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Berhasil Cancel Pembayaran');
            return redirect(site_url("backoffice/booking/{$booking->row()->bookingId}/detail"));
        }
        $this->session->set_flashdata('error', 'Gagal Cancel Pembayaran');
        return redirect(site_url("backoffice/booking/{$booking->row()->bookingId}/detail"));
    }

    /**----------------------------------------------------
     * Status Bayar
  -------------------------------------------------------**/
    public function status_bayar()
    {
        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'bookingStatusBayar',
                'label' => 'Status',
                'rules' => 'required'
            ],
            [
                'field' => 'bookingId',
                'label' => 'bookingId',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        $post = $this->input->post(null, true);
        if ($this->form_validation->run() == TRUE) {

            $booking = $this->booking->get(['bookingId' => $post['bookingId']]);
            if ($booking->num_rows() < 1) {
                $this->session->set_flashdata('warning', 'data tidak ditemukan');
                return redirect(site_url('backoffice/booking'));
            }

            $booking = $booking->row();
            // if ($booking->bookingStatusBayar == $post['bookingStatusBayar']) {
            //     $this->session->set_flashdata('warning', 'sudah sesuai!');
            //     return redirect(site_url('backoffice/booking'));
            // }
            $save['bookingStatusBayar'] = $post['bookingStatusBayar'];
            if ($post['bookingStatusBayar'] == 'tolak') {
                $save['bookingStatus'] = 'pending';
                $save['bookingBuktiBayar'] = null;
                $path = './_uploads/bukti_bayar/' . $booking->bookingBuktiBayar;
                $this->_deleteFile($path);
            }

            if ($post['bookingStatusBayar'] == 'dp') {
                $save['bookingStatus'] = 'diproses';
                $save['bookingAntrian'] = $this->antrian($booking->bookingJdId, $booking->bookingTgl);
            }
            // print_r($booking);
            // print_r($save);
            // return;
            $this->booking->update($save, ['bookingId' => $booking->bookingId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('booking', 'ubah', "data status bayar");
                $this->session->set_flashdata('success', 'Berhasil ubah status bayar booking');
                return redirect(site_url("backoffice/booking/{$post['bookingId']}/detail"));
            }
            activity_log('booking', 'gagal ubah', "data status bayar");
            $this->session->set_flashdata('error', 'Gagal ubah status bayar booking');
            return redirect(site_url("backoffice/booking/{$post['bookingId']}/detail"));
        }

        if (form_error('bookingStatusBayar')) {
            $this->session->set_flashdata('error', strip_tags(form_error('bookingStatusBayar')));
            return redirect(site_url("backoffice/booking/{$post['bookingId']}/detail"));
        }

        if (form_error('bookingId')) {
            $this->session->set_flashdata('error', strip_tags(form_error('bookingId')));
            return redirect(site_url('backoffice/booking'));
        }
    }

    /**----------------------------------------------------
     * Tambah booking
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
                'field' => 'bookingNama',
                'label' => 'Nama booking',
                'rules' => 'required'
            ],
            [
                'field' => 'bookingSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'bookingPengId',
                'label' => 'Pengguna',
                'rules' => 'required|is_unique[booking.bookingPengId]',
                'errors' => [
                    'is_unique' => '{field} sudah digunakan!'
                ]
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $data = [
                'title' => 'Tambah booking',
                'spesialis' => $this->db->get('booking_spesialis')->result(),
                'pengguna' => $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/booking/create', $data);
        } else {
            $post = $this->input->post(null, true);

            $this->booking->create($post);
            if ($this->db->affected_rows() == 1) {
                activity_log('booking', 'tambah', $post['bookingNama']);

                $this->session->set_flashdata('success', 'Berhasil tambah booking!');
                return redirect(site_url('backoffice/booking'));
            }

            activity_log('booking', 'gagal tambah', $post['bookingNama']);
            $this->session->set_flashdata('error', 'Gagal tambah booking!');
            return redirect(site_url('backoffice/booking'));
        }
    }

    /**----------------------------------------------------
     * Ubah booking
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
                'field' => 'bookingNama',
                'label' => 'Nama booking',
                'rules' => 'required'
            ],
            [
                'field' => 'bookingSpesialisId',
                'label' => 'Spesialis',
                'rules' => 'required'
            ],
            [
                'field' => 'bookingPengId',
                'label' => 'Pengguna',
                'rules' => 'required|callback_check_booking_update'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $booking = $this->booking->get(['bookingId' => $id]);
        if ($booking->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/booking'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title' => 'Ubah booking',
                'booking' => $booking->row(),
                'spesialis' => $this->db->get('booking_spesialis')->result(),
                'pengguna' => $this->db->from('pengguna')->join('pengguna_grup', 'pengguna_grup.pgrupPengId = pengguna.pengId')->where(['pgrupGrupId' => '5'])->get()->result()
            ];

            $this->template->load('template/dasbor', 'backoffice/admin/booking/update', $data);
        } else {
            $put = $this->input->post(null, TRUE);
            unset($put['bookingId']);
            $this->booking->update($put, ['bookingId' => $booking->row()->bookingId]);
            if ($this->db->affected_rows() > 0) {
                activity_log('booking', 'ubah', "data {$put['bookingNama']}");

                $this->session->set_flashdata('success', 'Berhasil ubah booking');
                return redirect(site_url('backoffice/booking'));
            }

            activity_log('booking', 'gagal ubah', "data {$put['bookingNama']}");
            $this->session->set_flashdata('error', 'Gagal ubah booking');
            return redirect(site_url('backoffice/booking'));
        }
    }

    /**----------------------------------------------------
     * Hapus booking
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
        $booking = $this->booking->get(['bookingId' => $id]);
        if ($booking->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('backoffice/booking'));
        }

        $this->booking->destroy(['bookingId' => $booking->row()->bookingId]);
        if ($this->db->affected_rows() > 0) {
            activity_log('booking', 'hapus', $booking->row()->bdTreatmentNama);

            $this->session->set_flashdata('success', 'Berhasil hapus booking!');
            return redirect(site_url('backoffice/booking'));
        }

        activity_log('booking', 'gagal hapus', $booking->row()->bdTreatmentNama);
        $this->session->set_flashdata('error', 'Gagal hapus booking!');
        return redirect(site_url('backoffice/booking'));
    }

    /**----------------------------------------------------
     * Hapus booking
  -------------------------------------------------------**/

    public function check_booking_update()
    {
        $booking = $this->db->get_where('booking', ['bookingPengId' => $_POST['bookingPengId'], 'bookingId !=' => $_POST['bookingId']]);
        if ($booking->num_rows() > 0) {
            $this->form_validation->set_message('check_booking_update', '{field} Sudah digunakan!');
            return false;
        }
        return true;
    }


    public function _uploadFile($path, $type, $size, $file_name, $name, $file_name_old = null, $link = null, $name_label = null)
    {
        if (!file_exists($path)) {
            $this->load->library('ftp');
            $this->ftp->connect(['hostname' => 'aesthetic-clinic.digitaline.site', 'username' => 'aesthetic@aesthetic-clinic.digitaline.site', 'password' => 'aesthetic-clinic123', 'port' => 21]);
            $this->ftp->mkdir($path, 0755);
        }
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

    public function _deleteFile($path)
    {
        $dir_file = $path;
        if (file_exists($dir_file)) {
            unlink($dir_file);
        }
    }

    public function antrian($jdId, $tgl)
    {
        $antrian_terkini = $this->db->from('booking')->where(['bookingJdId' => $jdId, 'bookingTgl' => $tgl])->where_in('bookingStatus', ['diproses', 'selesai'])->count_all_results();
        if ($antrian_terkini > 0) {
            return "A" . sprintf("%03d", $antrian_terkini + 1);
        }
        return "A" . sprintf("%03d", 1);
    }
}
