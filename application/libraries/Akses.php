<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Akses
{
  protected $ci;
  function __construct()
  {
    $this->ci = &get_instance();
  }

  /**----------------------------------------------------
   * Cek apakah HALAMAN pada menu tertentu dengan 
   * grup user yang sedang login ada dalam hak access 
  -------------------------------------------------------**/
  function access_menu($menuId)
  {
    $this->ci->load->model('Menu_model', 'menu');

    $access = $this->ci->menu->get([
      'menuId !=' => '0',
      'grupMenuGrupId' => $this->ci->ion_auth->get_group_id(),
      'grupMenuMenuId' => $menuId,
    ]);

    if ($access->num_rows() < 1) return false;
    return true;
  }

  /**----------------------------------------------------
   * Cek apakah BUTTON pada menu tertentu dengan 
   * grup user yang sedang login ada dalam hak access 
  -------------------------------------------------------**/
  function access_rights($menuId, $access)
  {
    $this->ci->load->model('Menu_model', 'menu');

    $result = $this->ci->menu->get([
      'menuId !=' => '0',
      'grupMenuGrupId' => $this->ci->ion_auth->get_group_id(),
      'grupMenuMenuId' => $menuId,
      $access => 1
    ]);

    if ($result->num_rows() < 1) return false;
    return true;
  }

  function check_menu($grupId, $menuId)
  {
    $this->ci->load->model('Hak_akses_model', 'hak_akses');

    return $this->ci->hak_akses->get([
      'grupMenuGrupId' => $grupId,
      'grupMenuMenuId' => $menuId,
    ])->row();;
  }

  function is_check($menu, $action)
  {
    return $menu && $action == 1 ? 'checked' : '';
  }

  /**----------------------------------------------------
   * Cek apakah BUTTON pada unit kerja tertentu dengan 
   * grup user yang sedang login ada dalam hak access 
  -------------------------------------------------------**/
  function access_rights_aksi($aksiTautan)
  {
    $this->ci->load->model('Aksi_grup_model', 'aksi_grup');
    $this->ci->load->model('Aksi_model', 'aksi');

    $aksi = $this->ci->aksi->get([
      'aksiTautan' => $aksiTautan,
    ])->row();

    $result = $this->ci->aksi_grup->get(
      [
        'agAksiId' => $aksi->aksiId ?? '',
        'agGrupId' => $this->ci->ion_auth->get_group_id()
      ]
    );

    if ($result->num_rows() < 1) return false;
    return true;
  }

  function is_check_aksi($agAksiId, $agGrupId)
  {
    $this->ci->load->model('Aksi_grup_model', 'aksi_grup');
    $result = $this->ci->aksi_grup->get(
      [
        'agAksiId' => $agAksiId,
        'agGrupId' => $agGrupId
      ]
    );

    if ($result->num_rows() > 0) return "checked";
  }

  /**----------------------------------------------------
   * Cek apakah konten pada menu tertentu sama dengan 
   * unit kerja user yang sedang login 
  -------------------------------------------------------**/
  function access_rights_uk($access)
  {
    $this->ci->load->model('Unit_kerja_model', 'unit_kerja');
    $this->ci->load->model('Pengguna_unit_kerja_model', 'pengguna_unit_kerja');

    $work_unit = $this->ci->unit_kerja->get([
      'ukNama' => $access,
    ])->row();

    $result = $this->ci->pengguna_unit_kerja->get([
      'pengukPengId' => $this->ci->ion_auth->user()->row()->pengId,
      'pengukUkId' => $work_unit->ukId,
    ]);

    if ($result->num_rows() < 1) return false;
    return true;
  }

  function is_check_uk($pengukPengId, $pengukUkId)
  {
    $this->ci->load->model('Pengguna_unit_kerja_model', 'pengguna_unit_kerja');
    $access = $this->ci->pengguna_unit_kerja->get(
      [
        'pengukPengId' => $pengukPengId,
        'pengukUkId' => $pengukUkId
      ]
    );

    if ($access->num_rows() < 1) return false;
    return true;
  }
}
