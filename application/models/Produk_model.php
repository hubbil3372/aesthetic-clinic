<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
    }

    var $column_order = [null, 'produkNama', 'produkGambar', 'kategoriNama', 'produkHarga', 'produkStok', 'produkDiskon', 'produkStatus', null]; //field yang ada di table user
    var $column_search = ['produkNama', 'produkHarga']; //field yang diizin untuk pencarian 
    var $order = ['produkDibuatPada' => 'DESC']; // default order 

    private function _get_datatables_query($where = null)
    {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->join('produk_kategori', 'produk_kategori.kategoriId = produk.produkKategoriId', 'left');
        if($where) $this->db->where($where);

        $i = 0;
        foreach ($this->column_search as $item) // looping awal
        {
            if (@$_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                // looping awal
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like('LOWER("' . $item . '")', strtolower($_POST['search']['value']));
                } else {
                    $this->db->or_like('LOWER("' . $item . '")', strtolower($_POST['search']['value']));
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($where = null)
    {
        $this->_get_datatables_query($where);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($where = null)
    {
        $this->_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($where = null, $like = null)
    {
        $this->db->from('produk');
        $this->db->join('produk_kategori', 'produk_kategori.kategoriId = produk.produkKategoriId', 'left');
        if ($where) $this->db->where($where);
        if ($like) $this->db->like($like);
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null, $limit = null, $like = null)
    {
        $this->db->from('produk');
        $this->db->join('produk_kategori', 'produk_kategori.kategoriId = produk.produkKategoriId', 'left');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);
        if ($limit != null) $this->db->limit($limit[0], $limit[1]);
        if ($like != null) $this->db->like($like);

        return $this->db->get();
    }

    function create($data)
    {
        $data['produkId'] = $this->uuid->v4();
        $this->db->insert('produk', $data);
    }

    function update($data, $where)
    {
        $this->db->update('produk', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('produk', $where);
    }
}
