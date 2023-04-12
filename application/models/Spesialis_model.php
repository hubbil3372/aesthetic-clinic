<?php
defined('BASEPATH') or exit('No direct script access allowed');

class spesialis_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('uuid');
    }

    var $column_order = [null, 'spesialisNama', 'spesialisDeskripsi', null]; //field yang ada di table user
    var $column_search = ['spesialisNama', 'spesialisDeskripsi']; //field yang diizin untuk pencarian 
    var $order = ['spesialisDibuatPada' => 'DESC']; // default order 

    private function _get_datatables_query()
    {
        $this->db->select('spesialisId, spesialisNama, spesialisDeskripsi');
        $this->db->from('dokter_spesialis');

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
        $this->db->from('dokter_spesialis');
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null)
    {
        $this->db->from('dokter_spesialis');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $data['spesialisId'] = $this->uuid->v4();
        $this->db->insert('dokter_spesialis', $data);
        return $data['spesialisId'];
    }

    function update($data, $where)
    {
        $this->db->update('dokter_spesialis', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('dokter_spesialis', $where);
    }
}
