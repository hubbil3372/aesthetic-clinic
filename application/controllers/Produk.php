<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model', 'produk');
        $this->load->model('Kategori_model', 'kategori');
        $this->load->model('Testimoni_model', 'testimoni');
    }

    /**----------------------------------------------------
     * Produk
  -------------------------------------------------------**/
    public function index()
    {
        $kategori = $this->input->get('kategori');
        $cari = $this->input->get('cari');

        $where = ['produkStatus' => 1];

        if ($kategori) {
            $where = array_merge(['produkKategoriId'  => $kategori], $where);
        }

        $like = ['produkNama' => strtolower($cari)];

        $this->load->library('pagination');

        $get = $this->input->get(null, true);
        unset($get['per_page']);
        $uri = http_build_query($get);

        $config['base_url'] = base_url('produk/index/?' . $uri);
        $config['total_rows'] = $this->produk->count_all($where, $like);
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
            'title'     => 'Beranda',
            'kategori'  => $this->kategori->get(null, 'kategoriNama Asc')->result(),
            'produk'    => $this->produk->get($where, 'produkDibuatPada DESC', [$config['per_page'], $start], $like)->result(),
        ];

        $this->template->load('template/frontend', 'frontend/produk/index', $data);
    }

    /**----------------------------------------------------
     * Lihat
  -------------------------------------------------------**/
    public function view($id)
    {
        $produk = $this->produk->get(['produkId' => $id, 'produkStatus' => 1])->row();

        if (!$produk) {
            $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
            return redirect(site_url('produk'));
        }

        $terkait = $this->produk->get(['produkId !=' => $produk->produkId, 'produkKategoriId' => $produk->produkKategoriId, 'produkStatus' => 1], 'produkDibuatPada DESC', [10, 0])->result();

        $data = [
            'title'     => $produk->produkNama,
            'produk'    => $produk,
            'testimoni' => $this->testimoni->get(['testimoniProdukId' => $produk->produkId])->result(),
            'terkait'   => $terkait
        ];

        $this->template->load('template/frontend', 'frontend/produk/view', $data);
    }
}
