<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Booking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_no_login();
        $this->load->library('uuid');
        $this->load->model('Customer_model', 'customer');
        $this->load->model('Treatment_model', 'treatment');
        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Booking_model', 'booking');
        $this->load->model('Booking_detail_model', 'bdetail');
    }

    /**----------------------------------------------------
     * booking data
  -------------------------------------------------------**/
    public function index()
    {
        $data = [
            'title' => 'Daftar Booking',
            'booking' => $this->booking->get(['bookingCustomerId' => $this->front_auth->logged_data()->customerId])->result()
        ];
        return $this->template->load('template/frontend', 'frontend/booking/index', $data);
    }

    /**----------------------------------------------------
     * Cek Voucher
  -------------------------------------------------------**/
    public function voucher($kode = null)
    {
        if ($kode == null) {
            $response = (object) [
                'status'    => false,
                'data'      => null
            ];
        }

        $voucher = $this->voucher->get(['voucherKode' => $kode, 'voucherStatus' => 1])->row();
        if (!$voucher) {
            $response = (object) [
                'status'    => false,
                'data'      => null
            ];
        } else {
            $response = (object) [
                'status'    => true,
                'data'      => $voucher
            ];
        }

        echo json_encode($response);
    }

    /**----------------------------------------------------
     * Tambah Keranjang
  -------------------------------------------------------**/
    public function create($id = null)
    {
        if (!$id || $id == null) {
            $this->session->set_flashdata('warning', 'Silakan pilih Treatment');
            return redirect(site_url('treatment'));
        }

        $treatment = $this->treatment->get(['treatmentId' => $id]);
        if ($treatment->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('treatment'));
        }

        $treatment = $treatment->row();

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'bookingJdId',
                'label' => 'Jadwal Dokter',
                'rules' => 'required|callback_check_jadwal_dokter'
            ],
            // [
            //     'field' => 'bookingEstimasiWaktu',
            //     'label' => 'Estimasi Waktu',
            //     'rules' => 'required|callback_check_estimasi_waktu'
            // ],
            [
                'field' => 'bookingTgl',
                'label' => 'Tanggal Booking',
                'rules' => 'required'
            ],
            // [
            //     'field' => 'bookingWaktu',
            //     'label' => 'Waktu Booking',
            //     'rules' => 'required|callback_check_estimasi_waktu'
            // ],
            [
                'field' => 'bookingWaktu',
                'label' => 'Waktu Booking',
                'rules' => 'required|callback_check_jadwal'
            ],
            [
                'field' => 'bookingDp',
                'label' => 'Dana Pertama',
                'rules' => 'required|callback_dana_pertama'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        $jadwal_dokter = $this->db->from('jadwal_dokter')->join('dokter', 'dokter.dokterId = jadwal_dokter.jdDokterId')->where('jdStatus', '1')->get();
        $hargaFix = $treatment->treatmentHarga - $treatment->treatmentDiskon;
        $minDp = $hargaFix * 0.3;

        $post = $this->input->post(null, true);
        if ($this->form_validation->run() == TRUE) {
            // $jd_dokter_dipilih = $this->db->get_where('jadwal_dokter', ['jdId' => $post['bookingJdId']])->row();
            $post['bookingTreatmentId'] = $post['treatmentId'];
            if ($post['bookingVoucher'] != null) {
                $voucher = $this->db->get_where('voucher', ['voucherKode' => $post['bookingVoucher']]);
                if ($voucher->num_rows() > 0) {
                    $harga_voucher = $voucher->row()->voucherPotongan;
                    $post['bookingVoucherPotongan'] = $harga_voucher;
                    $hargaFix = $hargaFix - $harga_voucher;
                }
            }
            if ($post['bookingVoucher'] == null) unset($post['bookingVoucher']);

            $waktu_booking = $this->jadwal_waktu($post['bookingJdId'], false,  $post['bookingWaktu']);
            // return print_r($waktu_booking);

            // $post['bookingJamAwal'] = $this->generateTimeBooking($post['bookingJdId'], $post['bookingTgl'], $post['bookingWaktu'])->jamAwal;
            // $post['bookingJamAkhir'] = $this->generateTimeBooking($post['bookingJdId'], $post['bookingTgl'], $post['bookingWaktu'])->jamAkhir;
            $treatment = $this->db->get_where('treatment', ['treatmentId' => $post['treatmentId']])->row();
            unset($post['treatmentId']);

            $post['bookingJamAwal'] = $waktu_booking['from'];
            $post['bookingJamAkhir'] = $waktu_booking['to'];
            $post['bookingWaktu'] = 60 * 2;

            $post['bookingDp'] = $post['bookingDp'] != null ? preg_replace("/[^0-9]/", "", $post['bookingDp']) : $minDp;
            $post['bookingKode'] = 'AST' . date('YmdHis') . rand(1000, 9999);
            $post['bookingStatus'] = 'pending';
            $post['bookingCustomerId'] = $this->front_auth->logged_data()->customerId;
            // return print_r($post);
            $booking_id = $this->booking->create($post);

            $detail_book = [
                'bdBookingId' => $booking_id,
                'bdTreatmentId' => $treatment->treatmentId,
                'bdTreatmentNama' => $treatment->treatmentNama,
                'bdTreatmentDeskripsi' => $treatment->treatmentDeskripsi,
                'bdTreatmentFoto' => $treatment->treatmentFoto,
                'bdTreatmentHarga' => $treatment->treatmentHarga,
                'bdTreatmentDiskon' => $treatment->treatmentDiskon,
            ];
            $this->bdetail->create($detail_book);

            if ($this->db->affected_rows() == 1) {
                $this->session->set_flashdata('success', 'Booking Berhasil!');
                return redirect(site_url("booking-treatment/{$booking_id}/lihat"));
            }

            $this->session->set_flashdata('error', 'Gagal Booking !');
            return redirect(site_url('booking-treatment'));
        }

        $data = [
            'treatment' => $treatment,
            'title' => 'Booking Treatment',
            'jadwal' => $jadwal_dokter->result(),
            'bookingDp' => $minDp,
        ];
        return $this->template->load('template/frontend', 'frontend/booking/create', $data);
    }

    public function show($booking_id)
    {
        $booking = $this->booking->get(['bookingId' => $booking_id]);
        if ($booking->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'data tidak ditemukan');
            return redirect(site_url('booking-treatment'));
        }

        $config_form = [
            [
                'field' => 'bookingBuktiBayar',
                'label' => 'Bukti Bayar',
                'rules' => 'callback_check_files'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');
        if ($this->form_validation->run() == TRUE) {

            $post = $this->input->post(null, true);

            $path = './_uploads/bukti_bayar';
            $type = 'pdf|jpg|jpeg|png';
            $size = 2048;
            $file_name = "BOOKING_";
            $file_name_old = $booking->row()->bookingBuktiBayar != NULL ? $booking->row()->bookingBuktiBayar : null;
            $upload = $this->_uploadFile($path, $type, $size, $file_name, 'bookingBuktiBayar', $file_name_old, null, 'Bukti Bayar');
            $this->booking->update(['bookingBuktiBayar' => $upload, 'bookingStatus' => 'konfirmasi', 'bookingStatusBayar' => 'pending'], ['bookingId' => $booking->row()->bookingId]);
            if ($this->db->affected_rows() == 1) {
                $this->session->set_flashdata('success', 'Bukti Booking Berhasil diupload!');
                return redirect(site_url("booking-treatment/{$booking_id}/lihat"));
            }

            $this->session->set_flashdata('error', 'Gagal upload bukti Booking !');
            return redirect(site_url('booking-treatment'));
        }


        $testimoni = $this->db->from('testimoni_treatment')
            ->join('booking_detail', 'booking_detail.bdbookingId = testimoni_treatment.testiBookingId')
            ->join('customer', 'customer.customerId = testimoni_treatment.testiCustomerId', 'left')
            ->where(['testiTreatmentId' => $booking->row()->bookingTreatmentId])
            ->get();

        // return print_r($booking->row());
        $data = [
            'title' => 'Booking Treatment',
            'booking' => $booking->row(),
            'testimoni' => $testimoni->result(),
            'treatment' => $this->db->get_where('booking_detail', ['bdTreatmentId' => $booking->row()->bookingTreatmentId])->row()
        ];
        return $this->template->load('template/frontend', 'frontend/booking/show', $data);
    }

    public function check_jadwal_dokter()
    {
        $id_jadwal = $_POST['bookingJdId'];
        $booking_antrean = $this->db->get_where('booking', ['bookingJdId' => $id_jadwal, 'bookingTgl' => $_POST['bookingTgl']])->num_rows();
        $booking_kuota = $this->db->get_where('jadwal_dokter', ['jdId' => $id_jadwal])->row()->jdBatasAntrian;
        $booking_total = $booking_antrean > 0 ? $booking_antrean : 0;
        $booking_sisa = $booking_kuota - $booking_total;
        if ($booking_sisa < 1) {
            $this->form_validation->set_message('check_jadwal_dokter', '{field} untuk hari ini sudah penuh!');
            return false;
        }
        // $this->form_validation->set_message('check_jadwal_dokter', 'Kuota untuk {field} ini sudah penuh!' . $booking_antrean);
        return true;
        // if ($id_jadwal != null) {
        // }
        // return true;
    }

    public function check_jam_awal()
    {
        $jam = $_POST['bookingJamAwal'];
        if ($jam != null) {
            if (empty($_POST['bookingJdId'])) return true;
            $jadwal_data = $this->db->get_where('jadwal_dokter', ['jdId' => $_POST['bookingJdId']]);
            if ($jadwal_data->num_rows() < 1) return false;
            $jadwal_data = $jadwal_data->row();
            if ($jam < substr($jadwal_data->jdJamAwal, 0, 5) && $jam < substr($jadwal_data->jdJamAkhir, 0, 5)) {
                $this->form_validation->set_message('check_jam_awal', '{field} Pilih jam Awal Sesuai jadwal dokter!');
                return false;
            }
            $booking = $this->db->get_where('booking', ['bookingJdId' => $_POST['bookingJdId'], 'bookingJamAwal' => $jam]);
            if ($booking->num_rows() > 0) {
                $this->form_validation->set_message('check_jam_awal', '{field} Jam Sudah Dipesan!');
                return false;
            }
            return true;
        }
    }

    public function check_jam_akhir()
    {
        $jam = $_POST['bookingJamAkhir'];
        if ($jam != null) {
            if (empty($_POST['bookingJdId'])) return true;
            $jadwal_data = $this->db->get_where('jadwal_dokter', ['jdId' => $_POST['bookingJdId']]);

            if ($jadwal_data->num_rows() < 1) return false;
            $jadwal_data = $jadwal_data->row();
            if ($jam > substr($jadwal_data->jdJamAkhir, 0, 5) && $jam > substr($jadwal_data->jdJamAwal, 0, 5)) {
                $this->form_validation->set_message('check_jam_akhir', '{field} Pilih jam Akhir Sesuai jadwal dokter!');
                return false;
            }
            $booking = $this->db->get_where('booking', ['bookingJdId' => $_POST['bookingJdId'], 'bookingJamAkhir' => $jam]);
            if ($booking->num_rows() > 0) {
                $this->form_validation->set_message('check_jam_akhir', '{field} Jam Sudah Dipesan!');
                return false;
            }
            return true;
        }
    }

    public function dana_pertama()
    {
        if ($_POST['bookingDp'] == null) return true;
        $dp = preg_replace("/[^0-9]/", "", $_POST['bookingDp']);
        $id_treatment = $_POST['treatmentId'];
        $treatment = $this->db->get_where('treatment', ['treatmentId' => $id_treatment]);
        if ($treatment->num_rows() < 1) return false;
        $treatment = $treatment->row();
        $hargaFix = $treatment->treatmentHarga - $treatment->treatmentDiskon;
        $dpMain =  $hargaFix * 0.3;

        if ($dp < $dpMain) {
            $this->form_validation->set_message('dana_pertama', '{field} tidak boleh kurang dari 30%!');
            return false;
        }

        if ($dp > $hargaFix) {
            $this->form_validation->set_message('dana_pertama', '{field} melebihi harga treatment!');
            return false;
        }
        return true;
    }

    public function check_estimasi_waktu()
    {
        $estimasi_waktu = $_POST['bookingWaktu'];
        if ($estimasi_waktu != null) {
            $estimasi_waktu == preg_replace("/[^0-9]/", "", $estimasi_waktu);
            $batas_waktu = $this->db->get_where('jadwal_dokter', ['jdId' => $_POST['bookingJdId']])->row()->jdTotalWaktuTreatment;
            $batas_waktu_treatment = $this->db->get_where('booking', ['bookingJdId' => $_POST['bookingJdId'], 'bookingTgl' => $_POST['bookingTgl']])->result();
            $batas_waktu_digunakan = 0;
            if ($batas_waktu_treatment) {
                foreach ($batas_waktu_treatment as $waktu) {
                    $batas_waktu_digunakan = $batas_waktu_digunakan + $waktu->bookingWaktu;
                }
            }
            $sisa_waktu = $batas_waktu - $batas_waktu_digunakan;
            $waktu_estimasi_tersedia = $sisa_waktu - $estimasi_waktu;
            if ($waktu_estimasi_tersedia >= 0) {
                return true;
            }
            $this->form_validation->set_message('check_estimasi_waktu', '{field} melebihi batas jadwal dokter, waktu tersisa ' . $sisa_waktu . ' menit untuk dokter ini');
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

    public function check_jadwal()
    {
        $jadwal = $_POST['bookingWaktu'];
        $booking = $this->db->get_where('booking', ['bookingTgl' => $_POST['bookingTgl'], 'bookingJamAwal' => $jadwal]);
        if ($booking->num_rows() > 0) {
            $this->form_validation->set_message('check_jadwal', '{field} Sudah dipesan silakan pilih waktu lain');
            return false;
        }
        return true;
    }

    public function antrian($tgl, $jdId)
    {
        $antrian_terkini = $this->db->from('booking')->where(['bookingJdId' => $jdId, 'bookingTgl' => $tgl])->count_all_results();
        if ($antrian_terkini < 1) {
            return "A" . sprintf("%03d", 1);
        }
        return "A" . sprintf("%03d", $antrian_terkini + 1);
    }

    public function generateTimeBooking($jdId, $tgl, $waktu)
    {
        $jam_booking = $this->db->from('booking')->where(['bookingJdId' => $jdId, 'bookingTgl' => $tgl])->get();
        $total_estimasi_digunakan = 0;
        if ($jam_booking->num_rows() > 0) {
            foreach ($jam_booking->result() as $jam) {
                $total_estimasi_digunakan = $total_estimasi_digunakan + $jam->bookingWaktu;
            }
        }
        $jadwal = $this->db->from('jadwal_dokter')->where(['jdid' => $jdId])->get()->row();
        $kuota_waktu = $jadwal->jdTotalWaktuTreatment;
        $sisa_waktu = $kuota_waktu - $total_estimasi_digunakan;
        $akhir_treatment = $sisa_waktu - $waktu;

        $jam_awal = new DateTime($jadwal->jdJamAwal);
        $jam_akhir = new DateTime($jadwal->jdJamAkhir);
        $jam_awal_new = $jam_awal->modify("+{$total_estimasi_digunakan} minute")->format('H:i');
        $jam_akhir_new = $jam_akhir->modify("-{$akhir_treatment} minute")->format('H:i');


        $return = new stdClass();
        $return->jamAwal = $jam_awal_new;
        $return->jamAkhir = $jam_akhir_new;

        return $return;
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


    public function jadwal_waktu($jdId = null, $html = false, $where = null)
    {
        $jadwal  = $this->db->get_where('jadwal_dokter', ['jdId' => $jdId])->row();
        $open_time = strtotime($jadwal->jdJamAwal);
        $close_time = strtotime($jadwal->jdJamAkhir);
        $output = [];
        for (
            $i = $open_time;
            $i < $close_time;
            $i += 3600 * 2
        ) {
            if ($i < $open_time) continue;
            $from = new DateTime(date("H:i", $i));
            $output[] = [
                "from" => date("H:i", $i),
                "to" => $from->modify("+ 2 Hour")->format("H:i")
            ];
        }
        if ($where != null) {
            foreach ($output as $key => $value) {
                if ($value["from"] == $where)
                    return $output[$key];
            }
        }
        if ($html) {
            echo json_encode($output);
            return;
        }
        return json_decode(json_encode($output));
    }
}
