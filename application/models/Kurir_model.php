<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kurir_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
    }

    var $column_order = [null, 'kurirNama', 'kurirKode', 'kurirStatus', null]; //field yang ada di table user
    var $column_search = ['kurirNama', 'kurirKode', 'kurirStatus']; //field yang diizin untuk pencarian 
    var $order = ['kurirNama' => 'ASC']; // default order 

    private function _get_datatables_query()
    {
        $this->db->select('kurirId, kurirNama, kurirKode, kurirStatus');
        $this->db->from('kurir');

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
        $this->db->from('kurir');
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null)
    {
        $this->db->from('kurir');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $data['kurirId'] = $this->uuid->v4();
        $this->db->insert('kurir', $data);
    }

    function update($data, $where)
    {
        $this->db->update('kurir', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('kurir', $where);
    }
}