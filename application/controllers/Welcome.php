<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   		$txnId = substr(str_shuffle($characters), 0, 10);
		// var_dump($txnId);
		// exit;
		$data = array(
			'tnxId' => $txnId
		);
		$this->load->view('welcome_message', $data);
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
}
