<?php
/**
 * Created by PhpStorm.
 * User: 韦腾赟
 * Date: 2018/7/9
 * Time: 19:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    public function __construct() {
        parent::__construct(false);
    }

    public function login() {
        // $verify_conf = [
        //     ['phone', '手机号码', 'required|exact_length[11]'],
        //     ['password', '密码', 'required|min_length[8]|max_length[20]'],
        // ];
        // $this->input_validate($verify_conf);
        $this->check_login();

    }


    /**
     * 刷新 access_token
     */
    public function refresh_access_token() {


    }

    public function get_vcode() {
        $this->load->helper('captcha');

        $this->exit_error(is_writable('./captcha/'));
        $vals = array(
            'word'        => '2323',
            'img_path'    => './captcha/',
            'img_url'     => 'http://example.com/captcha/',
            'font_path'   => './path/to/fonts/texb.ttf',
            'img_width'   => '150',
            'img_height'  => 30,
            'expiration'  => 7200,
            'word_length' => 8,
            'font_size'   => 16,
            'img_id'      => 'Imageid',
            'pool'        => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

            // White background and border, black text and red grid
            'colors'      => array(
                'background' => array(255, 255, 255),
                'border'     => array(255, 255, 255),
                'text'       => array(0, 0, 0),
                'grid'       => array(255, 40, 40)
            )
        );

        $cap = create_captcha($vals);
        echo $cap['image'];
        // var_dump($cap);
    }
}