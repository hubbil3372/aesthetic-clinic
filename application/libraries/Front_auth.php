<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Front_auth
{
    protected $ci;
    protected $session_login;
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model('Customer_model', 'customer');
        $this->ci->session_login = $this->ci->session->userdata('customerId');
    }

    function logged_in()
    {
        if (!$this->ci->session_login) {
            return redirect('login');
        }
    }

    function logged_out()
    {
        if ($this->ci->session_login) {
            return redirect(site_url('beranda'));
        }
    }

    function logged_data()
    {
        $user = $this->ci->customer->get(['customerId' => $this->ci->session_login]);
        if ($user->num_rows() < 1) {
            $this->ci->session->unset_userdata(['customerId']);
            $this->ci->session->set_flashdata('error', 'Sistem Sedang sibuk, silakan coba beberapa saat lagi!');
            return redirect(site_url('beranda'));
        }
        return $user->row();
    }

    function ecommerce()
    {
        $data = $this->ci->db->get_where('ecommerce', ['ecomId' => '123']);
        if ($data->num_rows() < 1) return false;
        return $data->row();
    }
}
