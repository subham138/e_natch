<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandate_verify extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('transaction_model');
    }

    function get_all_transaction($data_arr){
        // Convert the JSON string to a buffer
		$buffer = utf8_encode(json_encode($data_arr));

		$hexBuffer = bin2hex($buffer);

		$req_data = array(
			"data" => $hexBuffer
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => API_URL . '/api/get_res',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($req_data),
		CURLOPT_HTTPHEADER => array(
			'X-Auth: '. API_AUTH_KEY,
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$res_dt = json_decode($response);
		if($res_dt->suc > 0){
			// Convert the hex string to binary
			$binaryBuffer = hex2bin($res_dt->msg);

			// Convert the binary data to UTF-8 string
			$string = utf8_decode($binaryBuffer);
			return $string;
		}else{
			return false;
		}
    }

    function show_mandate_entry(){
        $selected = array(
            'frm_dt' => isset($_POST['frm_dt']) ? $_POST['frm_dt'] : '',
            'to_dt' => isset($_POST['to_dt']) ? $_POST['to_dt'] : '',
            'search_val' => isset($_POST['search_val']) ? $_POST['search_val'] : '',
        );

        $result = array();

        if(isset($_REQUEST['search'])){
            $select = 'a.*, b.CUST_NAME';
            $table_name = 'm_enach_reg_data a, v_nach_data b';
            // $where = "to_date(TO_CHAR(TPSL_TXN_DATE_TIME, 'dd/mm/yyyy'), 'dd/mm/yyyy') BETWEEN to_date('". date('d/m/Y', strtotime($selected['frm_dt'])) ."', 'dd/mm/yyyy') AND to_date('". date('d/m/Y', strtotime($selected['to_dt'])) ."', 'dd/mm/yyyy')";
            $where = "a.client_id=b.CUST_CD AND (a.client_id = '" . $selected['search_val'] . "' OR a.LOAN_ID = '" . $selected['search_val'] . "' OR a.UMRNNUMBER = '" .$selected['search_val'] . "')";
            $order = null;
            $data_arr = array(
                'select' => $select,
                'table_name' => $table_name,
                'where' => $where,
                'order' => $order,
                "flag" => 1
            );
            $result = $this->get_all_transaction($data_arr);
            // var_dump($result);exit;
        }

        $data = array(
            'header' => 'Mandate Verification',
            'result' => json_decode($result),
            'selected' => $selected
        );
        $this->template->load('eMandate/verify', $data);
    }
}