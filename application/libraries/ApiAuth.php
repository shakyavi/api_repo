<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiAuth
 *
 * @author ubuntu
 */
class ApiAuth extends REST_Controller{
    public static $key_received;
    public static $currentEmail;
    public static $currentUsername;
    public static $currentPassword;
    public static $currentDevice;
    public static $currentUserId;

    //put your code here
    function __construct($config = 'rest') {
        parent::__construct($config);
      $this->load->model('ApiModel');  
    }
    public function index(){
        $headers = $this->input->request_headers();
        self::$key_received =$headers['key'];
        //$key_exist=$this->ApiModel->login_key_exists(self::$key_received);
        $getUser=  $this->ApiModel->identifyUser(self::$key_received);
       // echo "<br>getEmail".$getEmail;
        if(!$getUser)
        {
              $this->response(array('error'=>'Invalid Token'),404);
            
        }
        else
        {
            self::$currentEmail = $getUser['userEmail'];
            self::$currentUsername =$getUser['userName'];
            self::$currentPassword=$getUser['userPassword'];
            self::$currentDevice=$getUser['userDevice'];
            self::$currentUserId=$getUser['userId'];
            //echo "api class".self::$requestPassword;
//echo self::$requestEmail."<br>".self::$requestUsername;            

        }
                
    }
}
