<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Upload
 *
 * @author €€€€
 */
class UploadNews extends CI_Controller{
    //put your code here
     public function __construct() { 
         parent::__construct(); 
         $this->load->model('UploadModel');
      }
		
      public function index() { 
        //echo "Hello"; 
        $this->load->view('Upload_form', array('error' => ' ' )); 
      } 
		
      public function uploadFunc() { 
      
  //Image Upload		
                //$config['upload_path']   = 'http://192.168.0.174/opt/lampp/htdocs/restapi/uploads/'; 
                $config['upload_path']   = './newsImages/'; 
                $config['allowed_types'] = 'gif|jpg|png'; 
                $config['max_size']      = 5000; 
              //  $config['max_width']     = 1024; 
                //$config['max_height']    = 768;  
                $this->load->library('upload', $config);
                
                $popular = $this->input->post('Popular');
                $featured = $this->input->post('Featured');
                
               
                
                if ( !$this->upload->do_upload('userfile')) 
                    {
                        $error = array('error' => $this->upload->display_errors()); 
                        $this->load->view('Upload_form', $error); 
                    }
			
                else
                    { 
                        $this->load->model('UploadModel');
                        
                        $newsTitle=  $this->input->post('newsTitle');                                
                        $insertId =$this->UploadModel->insertNews($newsTitle,$this->upload->data());
                        //echo $insertId;
                        if($insertId==FALSE)
                        {
                            echo "Error";  
                        }
                        else{
                            if($popular){
                               //echo "popular =".$popular;
                               $this->UploadModel->popularInsert($insertId);
                            }
                            if($featured)
                            {
                                //echo "<br>featured =".$featured;  
                                $this->UploadModel->featuredInsert($insertId);
                            }
                        $data = array('upload_data' => $this->upload->data()); 
                        
                        $this->load->view('Upload_success', $data); 
                    }
              }
      
      }         
  
} 
