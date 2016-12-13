<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserModel
 *
 * @author ubuntu
 */
class UserModel extends CI_Model{
    //put your code here
    function __construct() {
        parent::__construct();
    }
     public function get_users(){
     //query
//        $selectQuery = $this->db->get('user_ac');
//        $users = $selectQuery->result();
         
//stored procedure
    $query = $this->db->query("CALL getUsers();");
    $users=$query->result();
        return  $users;
     }
      
       public function get_user($id){
     $selectQuery = $this->db->query("CALL getUser(?)",array($id));
        $user = $selectQuery->result();
//        query 
//        $selectQuery = $this->db->get_where("user_ac",array("user_id"=>$id));
//        $user = $selectQuery->result();
        return  $user;
      
      }
           public function insert($data) { 
               $query=$this->db->insert("user_ac", $data);
               $insert=  $this->db->affected_rows($query);
            //echo $insert;
               if ($insert) { 
            return "Data added"; 
            }
            else 
                return false;
        }
        
        public function delete($id_del){
            $deleteQuery = $this->db->delete("user_ac","user_id = ".$id_del);
            if( $deleteQuery)
            return "Delete Successful";
            else
                return false;
        }
        
        public function update($email_update,$data){
               $userName=$data['user_name'];
               $password=$data['user_password'];
            $this->db->query("CALL updateUser(?,?,?);",array($email_update,$userName,$password));
//            $this->db->where('user_email',$email_update);
//            $update= $this->db->update('user_ac',$data);
          $check=$this->db->affected_rows();
          if($check==NULL)
           return FALSE;
          elseif($check>0)
            return "Update successful";
       
        }
        public function deleteToken($token){
            $this->db->where('auth_key',$token);
            $result=$this->db->delete("login_keys");
            //$result = $this->db->affected_
            return $result;
            
        }
}