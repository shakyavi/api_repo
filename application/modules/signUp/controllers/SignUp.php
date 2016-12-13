<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignUp
 *
 * @author ubuntu
 */
class SignUp extends CI_Controller {
    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('SignUpModel');
                
    }
    public function index(){
        $this->load->view('signUp/signUpForm');
    }
    
    public function toJson(){
        $data=array(
            'user_name'=> $this->input->post('userName'),
            'user_email'=> $this->input->post('userEmail'),
            'user_password'=> $this->input->post('password')
                );
        $json_data = json_encode($data);   
        $this->register($json_data);
    }
    
    public function register($input){
        var_dump($input);
        $inputData = json_decode($input,true);
       // var_dump($inputData);
        
        $data=array(
            'user_name'=> $inputData['user_name'],
            'user_email'=> $inputData['user_email'],
            'user_password'=> $inputData['user_password']
                );
                $this->SignUpModel->insert($data);
        
    }
    
}
