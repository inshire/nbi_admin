<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // phpinfo();
        var_dump($this->input->get());
        // // $encode = aes_encode('我是中国人');
        // $this->load->model('User_model');
        // $this->User_model->insert_test();
        // // $this->load->view('welcome_message');
    }
}
