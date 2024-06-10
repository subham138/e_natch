<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandate extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('transaction_model');
    }

    function entry(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   		$txnId = substr(str_shuffle($characters), 0, 10);

        $data = array(
            'header' => 'eMandate Registration',
			'tnxId' => $txnId
        );

        $this->template->load('eMandate/entry', $data);
    }

    function generate_token(){
		$data = $this->input->post();
		$saltKey = SALT_KEY;
		$merchantId = MERCHANT_CODE;
		// var_dump($data);exit;
		$dt = $merchantId.'|'.$data["tnxId"].'|'.(int)$data["reg_amt"].'||'.$data["consumer_id"].'|'.$data["mobile_no"].'|'.$data["email"].'|'.date('d-m-Y', strtotime($data["strt_dt"])).'|'.date('d-m-Y', strtotime($data["end_dt"])).'|'.$data["bebit_amt"].'|'.$data["amt_type"].'|MNTH|||||'.$saltKey.'';
		// echo $dt;exit;
		echo hash('sha512', $dt);
	}

    function get_user_data(){
		$loan_id = isset($_POST['loan_id']) ? $_POST['loan_id'] : 0;
		$select = '*';
		$table_name = 'v_nach_data';
		$where = "loan_id = $loan_id";
		$order = null;
		$data_arr = array(
			'select' => $select,
			'table_name' => $table_name,
			'where' => $where,
			'order' => $order,
			"flag" => 0
		);

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
			echo $string;
		}else{
			echo 'Something wents wrong';
		}
		// var_dump($res_dt);
	}

    function get_tnx_response(){
        $data = '{"loan_id":"10311","cust_id":"103558","msg":"0392|User Aborted|Transaction aborted by user|JIlBZ8qebL||E36861409|121333|{itc:}{email:}{mob:}|10-06-2024 16:48:25|NA||||||f154d76a3a9a384387e052a122a9432696cd86e5f1aed02b797b79ee6f9a8d4846a42b3649d8ca0b3afaea0931060ae6ace0f011dbbd7dca9be57a2404a7cf53"}';
        try {
            $dec_data = json_decode($data);
            $loan_id = $dec_data->loan_id;
            $cust_id = $dec_data->cust_id;
            $resp_msg = $dec_data->msg;
            $resp_msg_arr = explode('|', $resp_msg);
            if(is_array($resp_msg_arr)){
                $tnx_dt_arr = array(
                    "cust_id" => $cust_id,
                    "loan_id" => $loan_id,
                    "txn_status" => $resp_msg_arr[0],
                    "txn_msg" => $resp_msg_arr[1],
                    "txn_err_msg" => $resp_msg_arr[2],
                    "clnt_txn_ref" => $resp_msg_arr[3],
                    "tpsl_bank_cd" => $resp_msg_arr[4],
                    "tpsl_txn_id" => $resp_msg_arr[5],
                    "txn_amt" => $resp_msg_arr[6],
                    "tpsl_txn_date_time" => date('Y-m-d h:i:s', strtotime($resp_msg_arr[8])),
                    "bank_tnx_id" => $resp_msg_arr[12],
                    "mandate_reg_no" => $resp_msg_arr[13],
                );
                if($this->transaction_model->tnx_save($tnx_dt_arr)){
                    
                    echo 'Success';
                }else{
                    echo 'False';
                }
            }else{
                throw new Exception("Error Processing Request", 1);
            }
        } catch (\Throwable $th) {
            var_dump($th); exit;
        }
    }
}
?>