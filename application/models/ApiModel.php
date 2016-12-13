<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiModel
 *
 * @author ubuntu
 */
class ApiModel extends CI_Model {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
        public function identifyUser($keyReceived){
            
            $this->db->distinct();
            $this->db->select("*");
            $this->db->from("login_keys lk");
            $this->db->join("user_ac u","u.user_email=lk.user_email","left outer");
            $this->db->where("lk.auth_key",$keyReceived);
            $query = $this->db->get();
            $results = $query->row();
            if($results!=NULL)
            {
                $requestUser['userEmail']=$results->user_email;
                $requestUser['userName']=$results->user_name;
                $requestUser['userPassword']=$results->user_password;
                $requestUser['userDevice']=$results->device_id;
                $requestUser['userId']=$results->user_id;
                return $requestUser;
            }
    else {
     return false;}
    
            }
}
