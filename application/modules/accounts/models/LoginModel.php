<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KeyModel
 *
 * @author ubuntu
 */
class LoginModel extends CI_Model {
    //put your code here
    //public static $ci;
    function __construct() {
        parent::__construct();
       
    }
    
    public function _get_key($email,$device){
        //$query = "select * from login_keys where user_email = '$email'";
        $query = "select * from login_keys where user_email = '$email' and device_id='$device'";
        $output=$this->db->query($query)->num_rows();
         //debugging check no. of selected rows
        //echo $output;
        if($output==0){
            return FALSE;}
        else {
                  return TRUE;}
        }
    //update the key if it already exists
    public function update_key($data){
        
        $email=$data['user_email'];
        $key=$data['auth_key'];
          
            $this->db->where('user_email',$email);
            $update= $this->db->update('login_keys',$data);
          $check=$this->db->affected_rows();
          if($check==NULL)
           return FALSE;
          elseif($check>0)
            return TRUE;
    }
    //insert key after valid login
    public function _insert_key($data)
     {
                   $email=$data['user_email'];
                   $deviceId=$data['device_id'];
                   $key=$data['auth_key'];
                //check if key exists for the email
            if($this->_get_key($email,$deviceId))
        {   
          //$result=  $this->update_key($data);
                $result = 0;
                $message = "Already Logged In";
                            }
        else{
      $query = $this->db->insert('login_keys',$data);
          $result=  $this->db->affected_rows($query);
              }
            
               if ($result>0) { 
            return TRUE; 
            }
            else 
                return $message;
   
     }
//check if generated key exists
    public function _key_exists($key)
    {
        return $this->db
            ->where('auth_key', $key)
            ->count_all_results('login_keys') > 0;
    }
    //insert new users
    public function insert($data,$imageDetails=array()) { 
        $email=$data['user_email'];
        $userName=$data['user_name'];
        $pswd=$data['user_password'];
        $imagePath="http://192.168.0.180/api/profilePictures/".$imageDetails['file_name'];
        
        $query=$this->db->query("CALL addUser(?,?,?,?);",array($email,$userName,$pswd,$imagePath));
                //$query=$this->db->insert("user_ac", $data);
               $insert=  $this->db->affected_rows($query);
            
               if ($insert) { 
            return "Registration Successful"; 
            }
            else 
                return false;
        }
        //check if username is unique before register
    public function check_unique($username,$email){
           $usernameQuery = $this->db->get_where('user_ac',array('user_email'=>$username));
           $emailQuery = $this->db->get_where('user_ac',array('user_email'=>$email));
           
           $usernameCount=$usernameQuery->num_rows();
           $emailCount=$emailQuery->num_rows();
           
           if($emailCount>0 && $usernameCount>0)
           {
               return "Both username and email exist";
           }
           elseif($usernameCount>0)
           {
               return "Username already exists";
           }
           elseif ($emailCount>0) {
           return "Email already exists";
       }
 else {
       return true;    
       }
       }
    //validate login credentials   
    public function loginCheck($identity,$password){
           //$selectQuery = $this->db->get_where("user_ac",array("user_email"=>$identity,"user_password"=>$password));
           $selectQuery = $this->db->get_where("user_ac",array("user_email"=>$identity));
          //$result = $selectQuery->result();
          $result = $selectQuery->row();
          
               
          if($result!=NULL)
          {$username=$result->user_name;
              $databasePassword = $result->user_password;
             // $newHash = $this->bcrypt->hash_password($password);
              //echo "new Hash".$newHash;
               //echo "<br>from model dbpsw :".$databasePassword.";<br>input psw:".$password;
              if ($this->bcrypt->check_password($password,$databasePassword)){
                                return $username;}
  else
   return FALSE;
    }
          
          else
          return FALSE;
                    }
                    
          }
