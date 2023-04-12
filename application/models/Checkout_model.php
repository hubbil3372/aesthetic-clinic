<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkout_model extends CI_Model
{
    var $column_order = [null, 'checkoutKode', 'customerNama', 'checkoutStatusBayar', 'checkoutStatusPengiriman', 'checkoutTotalTagihan', null]; //field yang ada di table user
    var $column_search = ['checkoutKode', 'customerNama']; //field yang diizin untuk pencarian 
    var $order = ['checkoutDibuatPada' => 'DESC']; // default order 

    private function _get_datatables_query()
    {
        $this->db->select('checkout.*, customerNama');
        $this->db->from('checkout');
        $this->db->join('customer', 'customer.customerId = checkout.checkoutCustomerId');

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
        $this->db->from('checkout');
        $this->db->join('customer', 'customer.customerId = checkout.checkoutCustomerId');
        return $this->db->count_all_results();
    }

    function get($where = null, $order = null)
    {
        $this->db->select('checkout.*,
        checkout_detail.detailProdukGambar,
        checkout_detail.detailProdukNama,
        province as checkoutAlamatProvinsiNama,
        CONCAT(type,\' \',city) as checkoutAlamatKotkabNama,
        subdistrict_name as checkoutAlamatKecamatanNama,
        kurirNama,
        customerNama');
        $this->db->from('checkout');
        $this->db->join('checkout_detail', 'checkout_detail.detailCheckoutId = checkout.checkoutId');
        $this->db->join('x_subdistricts', 'x_subdistricts.subdistrict_id = checkout.checkoutAlamatKecamatanId');
        $this->db->join('kurir', 'kurir.kurirKode = checkout.checkoutKurirNama', 'left');
        $this->db->join('customer', 'customer.customerId = checkout.checkoutCustomerId');
        $this->db->group_by('checkout.checkoutId');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function get_detail($where = null, $order = null)
    {
        $this->db->from('checkout_detail');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $this->db->insert('checkout', $data);
    }

    function create_detail($data)
    {
        $this->db->insert_batch('checkout_detail', $data);
    }

    function update($data, $where)
    {
        $this->db->update('checkout', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('checkout', $where);
    }

}