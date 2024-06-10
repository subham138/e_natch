<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    function index(){
        $data = array(
            'title' => 'Test'
        );

        $this->template->load('pages/dashboard', $data);
    }
}
?>