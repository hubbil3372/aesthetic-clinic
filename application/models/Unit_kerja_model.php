<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit_kerja_model extends CI_Model
{
    var $column_order = [null, 'ukNama', 'ukDeskripsi', null]; //field yang ada di table user
    var $column_search = ['ukNama', 'ukDeskripsi']; //field yang diizin untuk pencarian 
    var $order = ['ukNama' => 'ASC']; // default order 

    public function __construct()
    {
      parent::__construct();
  
      $this->load->library('uuid');
    }

    private function _get_datatables_query()
    {
        $this->db->select('ukId, ukNama, ukDeskripsi');
        $this->db->from('unit_kerja');

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
        $this->db->from('unit_kerja');
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null)
    {
        $this->db->from('unit_kerja');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order); 

        return $this->db->get();
    }

    function get_join($where = null, $order = null)
    {
        $this->db->from('unit_kerja');
        $this->db->join('pengguna_unit_kerja', 'pengguna_unit_kerja.pengukUkId=unit_kerja.ukId');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order); 

        return $this->db->get();
    }

    function create($data)
    {
        $data['ukId'] = $this->uuid->v4();
        $this->db->insert('unit_kerja', $data);
    }

    function update($data, $where)
    {
        $this->db->update('unit_kerja', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('unit_kerja', $where);
    }
}
