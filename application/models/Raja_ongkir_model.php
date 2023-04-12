<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Raja_ongkir_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->api_key = file_get_contents('https://makmur.digitaline.site/rajaongkir.php', "rb");
    }

    public function cost($data = null, $berat = 1, $origin, $origin_type, $destination, $destination_type = 'subdistrict')
    {
        $kurir = '';
        foreach ($data as $key => $v) {
            if ($key === array_key_last($data)) {
                $kurir .= $v->kurirKode;
            }else{
                $kurir .= $v->kurirKode . ':';
            }
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin={$origin}&originType={$origin_type}&destination={$destination}&destinationType={$destination_type}&weight={$berat}&courier={$kurir}",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $this->api_key
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    public function waybill($resi, $kurir)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "waybill={$resi}&courier={$kurir}",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $this->api_key
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

}
?>