<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginRest
 *
 * @author ubuntu
 */
class Users extends REST_Controller{
    //put your code here
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model('LoginModel');
        //$this->load->helper('phpass');
       
    
    }
   
    public function index(){
       echo "welcome";
   }
   //register new user
   public function register_post(){
       
          $config['upload_path']   = './profilePictures/'; 
          $config['allowed_types'] = 'gif|jpg|png'; 
          $config['max_size']      = 5000; 
              //  $config['max_width']     = 1024; 
                //$config['max_height']    = 768;  
          $this->load->library('upload', $config);
 
          //if ( !$this->upload->do_upload('profilePicture')) 
           //         {
                     //   $error = array('error' => $this->upload->display_errors()); 
                  //      $this->response($error,500); 
             //       }
               //     else
                //    {	
                $this->upload->do_upload('profilePicture');
        $userName = $this->post('username');
    $userEmail = $this->post('useremail');
    $pswd = $this->post('password');
    $device_id=$this->post('deviceId');
    //$password = $this->passwordhash->HashPassword($pswd);
    //echo $userName;

    //$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
    //$password = $hasher->HashPassword($pswd);
    $password=  $this->bcrypt->hash_password($pswd);
   // echo "hashed = ".$password;
   // echo $unique;

//check for null data
if(!$userName||!$password||!$userEmail||!$device_id){
        $this->response(array('status'=>'Failed','message'=>'Fields cannot be empty!'),  REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
    }
        //check for unique email
         $unique = $this->LoginModel->check_unique($userName,$userEmail);

         if($unique===TRUE){
    //if email unique then generate auth key/toke
    $key=  $this->_generate_key();
    //$device_id=rand(0,100);
     $data_keys=array('user_email' => $userEmail, 'device_id' => $device_id, 'auth_key'=>$key);
  
    
    $data=array("user_name" => $userName,
        "user_email" => $userEmail,
        "user_password" => $password);
    //first register in user_ac table
        $output = $this->LoginModel->insert($data,$this->upload->data());
        //only then update key in login_keys table 
        //otherwise fk constraint error
        $keyInsert = $this->LoginModel->_insert_key($data_keys);
        //&& $keyInsert
        if($output && $keyInsert){
            $this->response(array('status'=>'Success','message'=>$output,'key'=>$key,'userPicture' => 'http://192.168.0.180/api/profilePictures/avatar5.png'),201);
        }
        else
        {
        $this->response(array('status'=>'Failed','message'=>'Unable to insert Data'),  REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    else
    {
        $this->response(array('status'=>'Failed','message'=>$unique),409);
    }
   }
   
   //validate user login   
   public function login_post(){
       //$username = $this->post('username');
       $useremail = $this->post('useremail');
       $password = $this->post('password');
       $device_Id =  $this->post('deviceId');
      if(!$useremail||!$password||!$device_Id){
          $this->response(['status'=>'Failed','message'=>'Fields Cannot be empty!'],  parent::HTTP_UNPROCESSABLE_ENTITY);
      }
       $validate = $this->LoginModel->loginCheck($useremail,$password);
       if($validate)
       {
          // Build a new key
        $key = $this->_generate_key();
        //$device_id=rand(0,100);
         //store the key in array   
         $data=array('user_email' => $useremail, 'device_id' => $device_Id, 'auth_key'=>$key);
         // Insert the new key
         $insertKey=$this->LoginModel->_insert_key($data);
        if($insertKey===TRUE)
        {
            $this->response([
                'status' => 'Login Successful' ,
                'username' => $validate,
                'key' => $key,
                'userPicture' => 'http://192.168.0.180/api/profilePictures/avatar5.png'
            ], REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => $insertKey
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // INTERNAL_SERVER_ERROR (500) being the HTTP response code
        }
           
       }
       else
       {
           $this->response(
           ['status' => 'Error',
                'message' => 'Invalid Credentials'],  REST_Controller::HTTP_UNAUTHORIZED);
                      
       }
       
   }
   
   private function _generate_key()
    {
        do
        {
            // Generate a random salt
            $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }

            $new_key = substr($salt, 0, config_item('rest_key_length'));
        }
        while ($this->LoginModel->_key_exists($new_key));

        return $new_key;
    }
    
 
}
