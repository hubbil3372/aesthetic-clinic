<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class UnitKerja extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Pengguna_unit_kerja_model', 'pengguna_unit_kerja');
    $this->load->model('Unit_kerja_model', 'unit_kerja');
    $this->load->model('Grup_model', 'grup');
  }

  /**----------------------------------------------------
   * Daftar Unit Kerja
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $data = [
      'title' => 'Unit Kerja',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/unit-kerja/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->unit_kerja->get_datatables();
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
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/unit-kerja/{$field->ukId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/unit-kerja/{$field->ukId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/unit-kerja/{$field->ukId}/hapus") . "'></i></a>";
      if ($button == '') $button = '-';

      /**----------------------------------------------------
       * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
      if ($field->ukId == 1) $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->ukNama;
      $row[] = $field->ukDeskripsi;
      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->unit_kerja->count_all(),
      "recordsFiltered" => $this->unit_kerja->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Tambah Grup
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
        'field' => 'ukNama',
        'label' => 'Unit Kerja',
        'rules' => 'required|is_unique[unit_kerja.ukNama]',
        'errors' => [
          'is_unique' => 'Unit kerja sudah ada!',
        ]
      ],
      [
        'field' => 'ukDeskripsi',
        'label' => 'Deskripsi',
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
        'title' => 'Tambah Unit Kerja'
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/unit-kerja/create', $data);
    } else {
      $post = $this->input->post(null, true);

      $this->unit_kerja->create($post);
      if ($this->db->affected_rows() == 1) {
        activity_log('unit kerja', 'tambah', $post['ukNama']);

        $this->session->set_flashdata('success', 'Berhasil tambah grup!');
        return redirect(site_url('backoffice/unit-kerja'));
      }

      activity_log('unit kerja', 'gagal tambah', $post['ukNama']);
      $this->session->set_flashdata('error', 'Gagal tambah grup!');
      return redirect(site_url('backoffice/unit-kerja'));
    }
  }

  /**----------------------------------------------------
   * Ubah Grup
  -------------------------------------------------------**/
  public function update($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $work_unit = $this->unit_kerja->get(['ukId' => $id]);
    if ($work_unit->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/unit-kerja'));
    }


    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    if ($this->input->post('ukNama') != $work_unit->row()->ukNama) {
      $is_unique =  '|is_unique[unit_kerja.ukNama]';
    } else {
      $is_unique =  '';
    }

    $config_form = [
      [
        'field' => 'ukNama',
        'label' => 'Unit Kerja',
        'rules' => "required{$is_unique}",
        'errors' => [
          'is_unique' => 'Unit kerja sudah ada!',
        ]
      ],
      [
        'field' => 'ukDeskripsi',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah Unit Kerja',
        'work_unit' => $work_unit->row()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/unit-kerja/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);

      $this->unit_kerja->update($put, ['ukId' => $work_unit->row()->ukId]);
      if ($this->db->affected_rows() > 0) {
        activity_log('unit kerja', 'ubah', "data {$put['ukNama']}");

        $this->session->set_flashdata('success', 'Berhasil ubah grup');
        return redirect(site_url('backoffice/unit-kerja'));
      }

      activity_log('unit kerja', 'gagal ubah', "data {$put['ukNama']}");
      $this->session->set_flashdata('error', 'Gagal ubah grup');
      return redirect(site_url('backoffice/unit-kerja'));
    }
  }

  /**----------------------------------------------------
   * Hapus Grup
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
    $work_unit = $this->unit_kerja->get(['ukId' => $id]);
    if ($work_unit->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/unit-kerja'));
    }

    $this->unit_kerja->destroy(['ukId' => $work_unit->row()->ukId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('unit kerja', 'hapus', $work_unit->row()->ukNama);

      $this->session->set_flashdata('success', 'Berhasil hapus grup!');
      return redirect(site_url('backoffice/unit-kerja'));
    }
    
    activity_log('unit kerja', 'gagal hapus', $work_unit->row()->ukNama);
    $this->session->set_flashdata('error', 'Gagal hapus grup!');
    return redirect(site_url('backoffice/unit-kerja'));
  }

  /**----------------------------------------------------
   * Akses Unit Kerja
  -------------------------------------------------------**/
  public function access($grupId, $pengId)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    if (!$this->ion_auth->is_admin()) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data ada dalam database
    -------------------------------------------------------**/
    $user = $this->ion_auth->user($pengId);
    if ($user->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    $group = $this->grup->get(['grupId' => $grupId]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/hak-akses'));
    }

    $data = [
      'title' => 'Unit Kerja Pengguna',
      'user' => $user->row(),
      'group' => $group->row(),
      'work_units' => $this->unit_kerja->get()->result(),
      'user_work_units' => $this->pengguna_unit_kerja->get(['pengukPengId' => $pengId])->result(),
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/unit-kerja/access', $data);
  }

  /**----------------------------------------------------
   * Tambah akses Unit Kerja ke pengguna
  -------------------------------------------------------**/
  public function create_access($grupId, $pengId, $ukId)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    if (!$this->ion_auth->is_admin()) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data ada dalam database
    -------------------------------------------------------**/
    $user = $this->ion_auth->user($pengId);
    if ($user->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    /**----------------------------------------------------
     * Cek apakah data ada dalam database
    -------------------------------------------------------**/
    $group = $this->grup->get(['grupId' => $grupId]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/hak-akses'));
    }

    /**----------------------------------------------------
     * Cek apakah data ada dalam database
    -------------------------------------------------------**/
    $user_work_unit = $this->pengguna_unit_kerja->get(
      [
        'pengukPengId' => $user->row()->pengId,
        'pengukUkId' => $ukId
      ]
    );

    $data = [
      'pengukPengId' => $user->row()->pengId,
      'pengukUkId' => $ukId,
    ];

    if ($user_work_unit->num_rows() < 1) {
      $this->pengguna_unit_kerja->create($data);
      if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', 'Berhasil tambah unit kerja ke pengguna');
        return redirect(site_url("backoffice/hak-akses/{$grupId}/grup/{$pengId}/pengguna"));
      }

      $this->session->set_flashdata('error', 'Gagal tambah unit kerja ke pengguna');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup/{$pengId}/pengguna"));
    }

    $this->pengguna_unit_kerja->destroy($data);
    if ($this->db->affected_rows() > 0) {
      $this->session->set_flashdata('success', 'Berhasil hapus unit kerja ke pengguna');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup/{$pengId}/pengguna"));
    }

    $this->session->set_flashdata('error', 'Gagal hapus unit kerja ke pengguna');
    return redirect(site_url("backoffice/hak-akses/{$grupId}/grup/{$pengId}/pengguna"));
  }
}
