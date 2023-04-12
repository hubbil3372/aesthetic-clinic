<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keranjang_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
    }

    function get($where = null, $order = null)
    {
        $this->db->from('keranjang');
        $this->db->join('produk', 'produk.produkId = keranjang.keranjangProdukId');

        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $data['keranjangId'] = $this->uuid->v4();
        $this->db->insert('keranjang', $data);
    }

    function update($data, $where)
    {
        $this->db->update('keranjang', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('keranjang', $where);
    }
}
