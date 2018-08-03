<?php
/**
 * Created by PhpStorm.
 * User: 韦腾赟
 * Date: 2018/7/9
 * Time: 9:55
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    protected $_redis_key            = 'aq:admin:'; //水产后台项目redis key 前缀
    protected $_access_token_expire  = 5; //小时
    protected $_refresh_token_expire = 30; //天
    protected $_params               = []; //用户的输入参数
    protected $_login_info;

    /**
     * MY_Controller constructor.
     * @param bool $check_login 是否需要验证登录
     */
    public function __construct($check_login = true) {
        parent::__construct();
        $this->load->helper('url');//加载url辅助函数
        $this->fetch_request_param(); //获取输入的参数
        $check_login && $this->check_login(); //验证登录
    }

    /**
     * 成功返回
     * @param bool $data 需要返回会给前端的数据
     * @param string $msg 成功信息
     */
    protected function exit_success($data = false, $msg = '') {
        $array['status']  = 0; //状态码0
        $array['message'] = $msg; //状态码0
        $array !== false && $array['data'] = $data; //默认不返回data字段，除非有需要返回数据
        exit(json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); //不转义中文，不转义 /
    }

    /**
     * 错误返回
     * @param string $msg 错误信息
     * @param int $status 错误码
     */
    protected function exit_error($msg = '', $status = 40001) {
        $array['status']  = $status; //默认的错误码为 40001
        $array['message'] = $msg;
        exit(json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); //不转义中文，不转义 /
    }

    /**
     * 对参数的相关验证
     * @param $rules_arr 字段以及对应规则设置
     * @param array $params 参数验证
     */
    public function input_validate($rules_arr, array $params = array()) {
        // $this->lang->load('form_validation', 'sc'); //加载语言包
        $this->load->helper(array('security')); //加载安全辅助函数
        $this->load->library('form_validation'); //加载表单验证库
        $this->form_validation->set_data($params ?: $this->_params);
        foreach ($rules_arr as $key => $value) {
            $this->form_validation->set_rules($value[0], $value[1], $value[2]);
        }
        $this->form_validation->set_error_delimiters('', ''); //设置错误信息的标签
        if ($this->form_validation->run() === false) {
            $errmsg = validation_errors();
            $this->exit_error($errmsg, 40001);
        }
    }

    /**
     * 校验token
     * @param $access_token
     * @return bool
     */
    protected function check_login() {
        if (empty($this->_params['access_token'])) {
            $this->exit_error('请提交access_token');
        }
        $this->_login_info = json_decode(aes_decode($this->_params['access_token']), true); //解密获取登录信息
        empty($this->_login_info['uid']) && $this->exit_error('登录验证失败');
    }


    /**
     * 生成 access_token 和 refresh_token
     * @param $login_info
     * @return array
     */
    protected function make_access_token($login_info) {
        if (empty($login_info['uid'])) {
            $this->exit_error('生成token失败');
        }
        $refresh_token           = [
            'overtime'  => strtotime("+{$this->_refresh_token_expire} day"),
            'token_key' => md5($login_info['uid'] . microtime() . rand(0, 10000)),
        ];
        $login_info['overtime']  = strtotime("+{$this->_refresh_token_expire} hour"); //过期时间
        $login_info['token_key'] = $refresh_token['token_key']; //$refresh_token 和 $access_token 总是成对的
        //生成access_token密文
        $access_token  = aes_encode(json_encode($login_info));
        $refresh_token = aes_encode(json_encode($refresh_token));
        if (!$access_token) {
            $this->exit_error('生成token失败');
        }
        return [
            'access_token'  => $access_token,
            'refresh_token' => $refresh_token,
        ];
    }


    /**
     * 获取请求参数
     */
    protected function fetch_request_param() {
        switch (true) {
            case $this->_params = $this->input->get():
                my_trim($this->_params);
            case $this->_params = $this->input->post():
                my_trim($this->_params);
                return;
            case $data = json_decode(file_get_contents('php://input'), true):
                my_trim($data);
                $this->_params = $data;
        }
    }
}