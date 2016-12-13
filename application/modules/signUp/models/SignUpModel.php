<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignUpModel
 *
 * @author ubuntu
 */
class SignUpModel extends CI_Model{
    //put your code here
    function __construct() {
        parent::__construct();
    }
    
    public function insert($data){
        $name=$data['user_name'];
        $email=$data['user_email'];
        $psw=$data['user_password'];
        $insertQuery = "INSERT INTO user_ac (user_name,user_email,user_password) VALUES ('$name','$email','$psw')";
        
                $this->db->query($insertQuery);
                        
    }
}
