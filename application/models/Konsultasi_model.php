<?php
defined('BASEPATH') or exit('No direct script access allowed');

class konsultasi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
    }

    var $column_order = [null, 'konsultasiJudul', 'konsultasiTeks', 'customerNama', null]; //field yang ada di table user
    var $column_search = ['konsultasiJudul', 'konsultasiTeks', 'customerNama']; //field yang diizin untuk pencarian 
    var $order = ['konsultasiDibuatPada' => 'DESC']; // default order 

    private function _get_datatables_query()
    {
        $this->db->from('konsultasi');
        $this->db->join('customer', 'customer.customerId = konsultasi.konsultasiCustomerId');

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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('konsultasi');
        $this->db->join('customer', 'customer.customerId = konsultasi.konsultasiCustomerId');
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null, $limit = null, $like = null)
    {
        $this->db->from('konsultasi');
        $this->db->join('customer', 'customer.customerId = konsultasi.konsultasiCustomerId');
        if ($where != null) $this->db->where($where);
        if ($like != null) $this->db->like($like);
        if ($limit != null) $this->db->limit($limit[0], $limit[1]);
        if ($order != null) $this->db->order_by(key($order), $order[key($order)]);

        return $this->db->get();
    }

    function count_all_results($where = null, $like = null)
    {
        $this->db->from('konsultasi');
        $this->db->join('customer', 'customer.customerId = konsultasi.konsultasiCustomerId');
        if ($where != null) $this->db->where($where);
        if ($like != null) $this->db->like($like);

        return $this->db->count_all_results();
    }

    function create($data)
    {
        $data['konsultasiId'] = $this->uuid->v4();
        $data['konsultasiDibuatPada'] = date('Y-m-d H:i:s');
        $this->db->insert('konsultasi', $data);
        return $data['konsultasiId'];
    }

    function detail_create($data)
    {
        $data['kdId'] = $this->uuid->v4();
        $data['kdDibuatPada'] = date('Y-m-d H:i:s');
        $this->db->insert('konsultasi_detail', $data);
    }


    function detail_update($data, $where)
    {
        $this->db->update('konsultasi_detail', $data, $where);
    }

    function update($data, $where)
    {
        $this->db->update('konsultasi', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('konsultasi', $where);
    }
}
