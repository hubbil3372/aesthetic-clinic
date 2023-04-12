<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testimoni_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
    }

    function get($where = null, $order = null)
    {
        $this->db->from('checkout_detail');
        $this->db->join('testimoni', 'testimoni.testimoniProdukId = checkout_detail.detailProdukId');
        $this->db->group_by('testimoniId');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $this->db->insert_batch('testimoni', $data);
    }

    function update($data, $where)
    {
        $this->db->update('testimoni', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('testimoni', $where);
    }
}
