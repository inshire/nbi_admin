<?php

/**
 * Created by PhpStorm.
 * User: 韦腾赟
 * Date: 2018/7/9
 * Time: 12:03
 */
class Admin_model extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取一个后台用户
     * @param $cond
     */
    public function db_one_admin($cond) {
        $this->db->get_where();
    }

    public function insert_test() {

    }
}