<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Produk_model', 'produk');
        $this->load->model('Customer_model', 'customer');
        $this->load->model('Keranjang_model', 'keranjang');
        $this->load->model('Checkout_model', 'checkout');
        $this->load->model('Raja_ongkir_model', 'rajaongkir');
        $this->load->model('Testimoni_model', 'testimoni');
    }
    /**----------------------------------------------------
     * Keluar Pengguna
     -------------------------------------------------------**/
    public function logout()
    {
        $this->session->unset_userdata('customerId');

        $this->session->set_flashdata('success', 'Berhasil logout!');
        return redirect(site_url('login'));
    }

    /**----------------------------------------------------
     * Login Customer
  -------------------------------------------------------**/
    public function login()
    {
        cek_already_login();

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'customerEmail',
                'label' => 'Email',
                'rules' => 'required'
            ],
            [
                'field' => 'customerPassword',
                'label' => 'Password',
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
                'title'     => 'Login Customer'
            ];

            $this->template->load('template/frontend', 'frontend/customer/login', $data);
        } else {
            $post = $this->input->post(null, true);
            $post['customerPassword'] = md5(sha1($post['customerPassword']));

            $customer = $this->customer->get($post)->row();
            if ($customer) {
                $this->session->set_userdata((array) $customer);

                $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $customer->customerNama);
                if (isset($_SERVER['HTTP_REFERER'])) {
                    return redirect($_SERVER['HTTP_REFERER']);
                }
                return redirect(site_url('beranda'));
            }

            $this->session->set_flashdata('error', 'Email atau password tidak sesuai!');
            return redirect(site_url('login'));
        }
    }

    /**----------------------------------------------------
     * Register
  -------------------------------------------------------**/
    public function register()
    {
        cek_already_login();

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'customerNama',
                'label' => 'Nama Lengkap',
                'rules' => 'required'
            ],
            [
                'field' => 'customerTglLahir',
                'label' => 'Tanggal Lahir',
                'rules' => 'required'
            ],
            [
                'field' => 'customerJenisKelamin',
                'label' => 'Jenis Kelamin',
                'rules' => 'required'
            ],
            [
                'field' => 'customerNoHp',
                'label' => 'Nomor Handphone',
                'rules' => 'required|is_unique[customer.customerNoHp]'
            ],
            [
                'field' => 'customerEmail',
                'label' => 'Email',
                'rules' => 'required|is_unique[customer.customerEmail]'
            ],
            [
                'field' => 'customerPassword',
                'label' => 'Password',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');
        $this->form_validation->set_message('is_unique', '{field} Sudah Digunakan!');


        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == false) {
            $data = [
                'title'     => 'Registrasi'
            ];

            $this->template->load('template/frontend', 'frontend/customer/register', $data);
        } else {
            $post = $this->input->post(null, true);
            $post['customerPassword'] = md5(sha1($post['customerPassword']));

            $this->customer->create($post);
            if ($this->db->affected_rows() == 1) {

                $this->session->set_flashdata('success', 'Registrasi berhasil! Silahkan login');
                return redirect(site_url('login'));
            }

            $this->session->set_flashdata('error', 'Registrasi gagal!');
            return redirect(site_url('registrasi'));
        }
    }

    /**----------------------------------------------------
     * Profil
  -------------------------------------------------------**/
    public function profil()
    {
        cek_no_login();

        $data = [
            'title'         => 'Profil',
            'profil'        => $this->customer->get(['customerId' => $this->session->userdata('customerId')])->row(),
            'provinces'     => $this->customer->get_provinces()->result(),
            'cities'        => $this->customer->get_cities()->result(),
            'subdistricts'  => $this->customer->get_subdistricts()->result(),
        ];

        $this->template->load('template/frontend', 'frontend/customer/profil', $data);
    }

    /**----------------------------------------------------
     * Profil Update
  -------------------------------------------------------**/
    public function profil_update()
    {
        cek_no_login();

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'customerNama',
                'label' => 'Nama Lengkap',
                'rules' => 'required'
            ],
            [
                'field' => 'customerTglLahir',
                'label' => 'Tanggal Lahir',
                'rules' => 'required'
            ],
            [
                'field' => 'customerJenisKelamin',
                'label' => 'Jenis Kelamin',
                'rules' => 'required'
            ],
            [
                'field' => 'customerNoHp',
                'label' => 'Nomor Handphone',
                'rules' => 'required'
            ],
            [
                'field' => 'customerEmail',
                'label' => 'Email',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $customer = $this->customer->get(['customerId' => $this->session->userdata('customerId')]);
        if ($customer->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('profil'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title'         => 'Profil',
                'profil'        => $customer->row(),
                'provinces'     => $this->customer->get_provinces()->result(),
                'cities'        => $this->customer->get_cities()->result(),
                'subdistricts'  => $this->customer->get_subdistricts()->result(),
            ];

            $this->template->load('template/frontend', 'frontend/customer/profil', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            if (@$_FILES['customerFoto']['name'] != "") {
                $put['customerFoto'] = $this->_uploadFile('./_uploads/profil/', 'png|jpg|jpeg', 2048, 'PROFIL_', 'customerFoto', $customer->row()->customerFoto);
            }

            $this->customer->update($put, ['customerId' => $customer->row()->customerId]);
            if ($this->db->affected_rows() > 0) {
                // update session
                $this->session->set_userdata($put);

                $this->session->set_flashdata('success', 'Berhasil perbarui data');
                return redirect(site_url('profil'));
            }

            $this->session->set_flashdata('error', 'Gagal perbarui data');
            return redirect(site_url('profil'));
        }
    }

    /**----------------------------------------------------
     * Profil Update Alamat
  -------------------------------------------------------**/
    public function alamat_update()
    {
        cek_no_login();

        /**----------------------------------------------------
         * Konfigurasi Form Validation
    -------------------------------------------------------**/
        $config_form = [
            [
                'field' => 'customerAlamatPenerima',
                'label' => 'Nama Penerima',
                'rules' => 'required'
            ],
            [
                'field' => 'customerAlamatNoHp',
                'label' => 'Nomor Handphone',
                'rules' => 'required'
            ],
            [
                'field' => 'customerAlamatProvinsiId',
                'label' => 'Provinsi',
                'rules' => 'required'
            ],
            [
                'field' => 'customerAlamatKotkabId',
                'label' => 'Kota / Kabupaten',
                'rules' => 'required'
            ],
            [
                'field' => 'customerAlamatKecamatanId',
                'label' => 'Kecamatan',
                'rules' => 'required'
            ],
            [
                'field' => 'customerAlamatLengkap',
                'label' => 'Alamat Lengkap',
                'rules' => 'required'
            ],
        ];
        $this->form_validation->set_rules($config_form);
        $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

        /**----------------------------------------------------
         * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
        $customer = $this->customer->get(['customerId' => $this->session->userdata('customerId')]);
        if ($customer->num_rows() < 1) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('profil'));
        }

        /**----------------------------------------------------
         * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title'         => 'Profil',
                'profil'        => $customer->row(),
                'provinces'     => $this->customer->get_provinces()->result(),
                'cities'        => $this->customer->get_cities()->result(),
                'subdistricts'  => $this->customer->get_subdistricts()->result(),
            ];

            $this->template->load('template/frontend', 'frontend/customer/profil', $data);
        } else {
            $put = $this->input->post(null, TRUE);

            $this->customer->update($put, ['customerId' => $customer->row()->customerId]);
            if ($this->db->affected_rows() > 0) {
                // update session
                $this->session->set_userdata($put);

                $this->session->set_flashdata('success', 'Berhasil perbarui data');
                return redirect(site_url('profil'));
            }

            $this->session->set_flashdata('error', 'Gagal perbarui data');
            return redirect(site_url('profil'));
        }
    }

    /**----------------------------------------------------
     * Transaksi
  -------------------------------------------------------**/
    public function transaksi()
    {
        cek_no_login();

        $data = [
            'title'     => 'Transaksi',
            'transaksi' => $this->checkout->get(['checkoutCustomerId' => $this->session->userdata('customerId')], 'checkoutDibuatPada DESC')->result()
        ];

        $this->template->load('template/frontend', 'frontend/customer/transaksi', $data);
    }

    /**----------------------------------------------------
     * Transaksi Detail
  -------------------------------------------------------**/
    public function transaksi_detail($id)
    {
        cek_no_login();

        $transaksi = $this->checkout->get(['checkoutId' => $id])->row();

        if (@$_FILES['checkoutBuktiBayar']['name'] != "") {
            $put['checkoutBuktiBayar'] = $this->_uploadFile('./_uploads/bukti_bayar/', 'png|jpg|jpeg', 2048, 'BUKTI_BAYAR_', 'checkoutBuktiBayar', $transaksi->checkoutBuktiBayar, "transaksi/{$id}/detail");
            $put['checkoutStatusBayar'] = 0;

            $this->checkout->update($put, ['checkoutId' => $id]);
            if ($this->db->affected_rows() == 1) {
                $this->session->set_flashdata('success', 'Berhasil mengupload bukti bayar! Kami akan segera memverifikasi pembayaran anda');
                return redirect(site_url("transaksi/{$id}/detail"));
            }

            $this->session->set_flashdata('error', 'Gagal mengupload bukti bayar!');
            return redirect(site_url("transaksi/{$id}/detail"));
        }

        $pengiriman = json_decode($this->rajaongkir->waybill($transaksi->checkoutNoResi, $transaksi->checkoutKurirNama))->rajaongkir;
        if (isset($pengiriman->result)) $pengiriman = $pengiriman->result;

        $data = [
            'title'         => 'Transaksi Detail',
            'transaksi'     => $transaksi,
            'transaksi_det' => $this->checkout->get_detail(['detailCheckoutId' => $id])->result(),
            'pengiriman'    => $pengiriman,
            'testimoni'     => $this->testimoni->get(['testimoniCheckoutId' => $id])->result()
        ];

        $this->template->load('template/frontend', 'frontend/customer/transaksi_detail', $data);
    }

    /**----------------------------------------------------
     * Transaksi Konfirmasi
  -------------------------------------------------------**/
    public function transaksi_konfirmasi($id)
    {
        cek_no_login();

        $data = ['checkoutStatusPengiriman' => 2];

        $this->checkout->update($data, ['checkoutId' => $id]);
        if ($this->db->affected_rows() == 1) {
            $this->session->set_flashdata('success', 'Berhasil konfirmasi terima barang!');
            return redirect(site_url('transaksi/' . $id . '/detail'));
        }

        $this->session->set_flashdata('error', 'Gagal konfirmasi terima barang!');
        return redirect(site_url('transaksi/' . $id . '/detail'));
    }

    /**----------------------------------------------------
     * Keranjang
  -------------------------------------------------------**/
    public function keranjang()
    {
        cek_no_login();

        $id = $this->input->get('id');
        $qty = $this->input->get('qty');

        if ($id == null) {
            $data = [
                'title'     => 'Keranjang',
                'keranjang' => $this->keranjang->get(['keranjangCustomerId'])->result()
            ];

            $this->template->load('template/frontend', 'frontend/customer/keranjang', $data);
            return;
        }

        $produk = $this->produk->get(['produkId' => $id])->row();

        if (!$produk) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
            return redirect(site_url('keranjang'));
        }

        if ($qty > $produk->produkStok) {
            $this->session->set_flashdata('error', 'Stok produk tidak cukup!');
            return redirect(site_url('keranjang'));
        }

        $data = [
            'keranjangCustomerId'   => $this->session->userdata('customerId'),
            'keranjangProdukId'     => $produk->produkId,
            'keranjangKuantitas'    => $qty,
        ];

        $this->keranjang->create($data);
        if ($this->db->affected_rows() == 1) {

            $this->session->set_flashdata('success', 'Berhasil menambahkan produk ke keranjang!');
            return redirect(site_url('keranjang'));
        }

        $this->session->set_flashdata('error', 'Gagal menambahkan produk ke keranjang!');
        return redirect(site_url('keranjang'));
    }

    /**----------------------------------------------------
     * Keranjang Hapus
  -------------------------------------------------------**/
    public function keranjang_hapus($id)
    {
        cek_no_login();

        $this->keranjang->destroy(['keranjangId' => $id]);
        if ($this->db->affected_rows() == 1) {

            $this->session->set_flashdata('success', 'Berhasil hapus produk dari keranjang!');
            return redirect(site_url('keranjang'));
        }

        $this->session->set_flashdata('error', 'Gagal hapus produk dari keranjang!');
        return redirect(site_url('keranjang'));
    }

    /**----------------------------------------------------
     * Keranjang
  -------------------------------------------------------**/
    public function keranjang_update($id)
    {
        cek_no_login();

        $qty = $this->input->get('qty');

        $data = ['keranjangKuantitas'    => $qty,];

        $this->keranjang->update($data, ['keranjangId' => $id]);
        if ($this->db->affected_rows() == 1) {
            return redirect(site_url('keranjang'));
        }

        $this->session->set_flashdata('error', 'Gagal menambahkan kuantitas!');
        return redirect(site_url('keranjang'));
    }

    /**----------------------------------------------------
     * json_cities
     -------------------------------------------------------**/
    public function json_cities()
    {
        $cities = $this->keranjang->get_cities(['province_id' => $this->input->get('province_id')])->result();
        echo json_encode($cities);
    }

    /**----------------------------------------------------
     * json_subdistricts
     -------------------------------------------------------**/
    public function json_subdistricts()
    {
        $subdistricts = $this->keranjang->get_subdistricts(['city_id' => $this->input->get('city_id')])->result();
        echo json_encode($subdistricts);
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
            if ($link != null) return redirect(site_url($link));

            return redirect(site_url('profil'));
        }
    }
}
