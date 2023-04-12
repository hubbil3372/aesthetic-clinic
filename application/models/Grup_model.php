<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grup_model extends CI_Model
{
    var $column_order = [null, 'grupNama', 'grupDeskripsi', null]; //field yang ada di table user
    var $column_search = ['grupNama', 'grupDeskripsi']; //field yang diizin untuk pencarian 
    var $order = ['grupNama' => 'ASC']; // default order 

    private function _get_datatables_query()
    {
        $user = $this->ion_auth->user()->row();
        $user_groups = $this->ion_auth->get_users_groups($user->id)->row();

        $this->db->select('grupId, grupNama, grupDeskripsi');
        $this->db->from('grup');
        if ($user_groups->id != '1') $this->db->where('grupId !=', '1');

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
        $user = $this->ion_auth->user()->row();
        $user_groups = $this->ion_auth->get_users_groups($user->id)->row();
        $this->db->from('grup');
        if ($user_groups->id != '1') $this->db->where('grupId !=', '1');
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null)
    {
        $user = $this->ion_auth->user()->row();
        $user_groups = $this->ion_auth->get_users_groups($user->id)->row();
        $this->db->from('grup');
        if ($user_groups->id != '1') $this->db->where('grupId !=', '1');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $this->db->insert('grup', $data);
    }

    function update($data, $where)
    {
        $this->db->update('grup', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('grup', $where);
    }
}
