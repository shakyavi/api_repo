<?php
require(APPPATH.'libraries/REST_Controller.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserRest
 *
 * @author ubuntu
 */
class UserDetails extends ApiAuth{
    //put your code here
    function __construct($config = 'rest') {
        parent::__construct($config);
        parent::index();
        $this->load->model('UserModel');
        
    }


    public function index_get(){
            $this->user_get();
}

    //show the list of registered users
    public function user_get() {
    //echo "user_get";
    $id_flag=FALSE;
    $id = ($this->uri->segment(4)?$this->uri->segment(4):$this->uri->segment(2));
     if($id == NULL)
    {   
         $id=$this->get('id');
    }
   // echo "user details id=".$id;
    if($id==NULL){
         $output = $this->UserModel->get_users();
         }
 else {
     $output = $this->UserModel->get_user($id);
     }
             
             if($output)
             {$this->response($output,200);}
             else{
                 $this->response(array('error'=>'Id Does not exist'),404);
             }
}

    //add new user on post request
    public function user_post() {
    $userName = $this->post('username');
    $userEmail = $this->post('useremail');
    $pswd = $this->post('password');
    $password=$this->bcrypt->hash_password($pswd);
if(is_null($userName)||is_null($password)||is_null($userEmail)){
        $this->response(array('error'=>'Fields cannot be null'),404);
    }
else{
        $data=array("user_name" => $userName,
        "user_email" => $userEmail,
        "user_password" => $password);

        $output = $this->UserModel->insert($data);
        if($output){
            $this->response($output,200);
        }
        else
        {
        $this->response(array('error'=>'Insert Error'),404);
        }
    }
}

    //update user on put-request using supplied key
    public function user_put() {
  
   $userName = $this->put('username');
    $password = $this->put('password');
    //echo "global".self::$requestEmail;
    $userEmail=parent::$currentEmail;
      // $userEmail=$this->UserModel->identifyUser(parent::$key_received);
echo "userEmail (userdetails)=".$userEmail;
  
if(is_null($userName)||is_null($password)||is_null($userEmail)){
        $this->response(array('error'=>'Fields cannot be null'),404);
    }
else{
        $data=array(
            "user_name" => $userName,
             "user_password" => $password);

       
        $output = $this->UserModel->update($userEmail,$data);
        if($output){
            $this->response($output,200);
        }
        else
        {
        $this->response(array('error'=>'Update Error'),404);
        }
    }
}

    //delete user on delete request using id received
    public function user_delete() {
    $email = $this->uri->segment(4);
    if($id == NULL)
    {   $id=$this->put('id');
     }
     
    if($id==NULL){
        $this->response(array('error'=>'ID is required to delete'),404);
    }
    else
        {
        $output=  $this->UserModel->delete($id);
        }
        if($output){
            $this->response($output,200);
    }
    else{
        $this->response(array('error'=>'Delete error'),404);
         }
}
 
    //edit user on post request using supplied key
    public function editUser_post(){
    $email =  parent::$currentEmail; 
    $oldHashedPassword = parent::$currentPassword;
    
    $oldPasswordReceived =  $this->post('oldPassword');
    
    $newPassword = $this->post('newPassword');
       //echo "corresponding email = ".$email;
    $newUsername = $this->post('username');
    $newPswd = $this->post('password');
    
    $newPassword =$this->bcrypt->hash_password($newPswd);
     
    if(!$newPassword||!$newUsername||!$oldPasswordReceived){
        $this->response(array('status' => 'Failed','message'=>'Fields cannot be empty!'),  REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
    }
    
    if($this->bcrypt->check_password($oldPasswordReceived,$oldHashedPassword))
         {
                   $data=array("user_name" => $newUsername,
                                          "user_password" => $newPassword);

                   $output = $this->UserModel->update($email,$data);
        
                    if($output){
                        $this->response(array('status'=>'Success','message'=>$output,'username'=>$newUsername),200);
                   }
                    else
                   {   
                    $this->response(array('status'=>'Failed','message'=>'Unable to Update Data'),  REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                   }
        }
        else
        {
            $this->response(array('status'=>'Failed','message'=>'Old Password does not match'),REST_Controller::HTTP_UNAUTHORIZED);
        }
    
    }
    
    //remove authorization token on logout
    public function logout_post(){
        $tokenDel = parent::$key_received;
        $removeToken = $this->UserModel->deleteToken($tokenDel);
        if($removeToken){
            $this->response(array('status'=>'Success','message'=>'Log Out Successful'),200);
        }
    }
   
}

    

