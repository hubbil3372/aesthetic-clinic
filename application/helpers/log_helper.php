<?php

function activity_log($database, $aksi, $keterangan)
{
  $ci = &get_instance();
  $ci->load->model('log_model');
  $ci->load->library('ion_auth');

  $user = $ci->ion_auth->user()->row();

  // $data['logWaktu'] = date('d-m-y H:i:s');
  $data['logPengguna'] = $user->pengNama;
  $data['logTlp'] = $user->pengTlp;
  $data['logDatabase'] = $database;
  $data['logAksi'] = $aksi;
  $data['logKeterangan'] = $keterangan;

  $ci->log_model->create($data);
}

function indo_currency($str)
{
  return 'Rp.' . number_format($str, 0, ',', '.');
}

function cek_already_login()
{
  $ci = &get_instance();
  $user_session = $ci->session->userdata('customerId');
  if ($user_session) {
    redirect('beranda');
  }
}

function cek_no_login()
{
  $ci = &get_instance();
  $user_session = $ci->session->userdata('customerId');
  if (!$user_session) {
    $ci->session->set_flashdata('error', 'Silahkan login terlebih dahulu!');
    redirect('login');
  }
}

function indo_date($date)
{
  $d = substr($date, 8, 2);
  $b = substr($date, 5, 2);
  $th = substr($date, 0, 4);

  return $d . '/' . $b . '/' . $th;
}


function status_booking($status)
{
  $return = '';
  if ($status == 'pending') {
    $return .= '<small class="fw-bold mb-3 text-warning">Menunggu pembayaran</small>';
  }
  if ($status == 'konfirmasi') {
    $return .= '<small class="fw-bold mb-3 text-dark">Menunggu konfirmasi</small>';
  }
  if ($status == 'diproses') {
    $return .= '<small class="fw-bold mb-3 text-primary">Di Prosess</small>';
  }
  if ($status == 'selesai') {
    $return .= '<small class="fw-bold mb-3 text-success">Treatment Selesai</small>';
  }

  return $return;
}


function status_bayar($status)
{
  $return = '';
  if ($status == 'pending') {
    $return .= '<small class="fw-bold mb-3 text-warning">Menunggu pembayaran</small>';
  }
  if ($status == 'dp') {
    $return .= '<small class="fw-bold mb-3 text-primary">DP Dibayar</small>';
  }
  if ($status == 'lunas') {
    $return .= '<small class="fw-bold mb-3 text-info">Lunas</small>';
  }
  if ($status == 'tolak') {
    $return .= '<small class="fw-bold mb-3 text-danger">Pembayaran Ditolak</small>';
  }
  return $return;
}
