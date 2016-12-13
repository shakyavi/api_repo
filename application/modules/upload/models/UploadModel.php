<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadModel
 *
 * @author ubuntu
 */
class UploadModel extends CI_Model{
    //put your code here
    function __construct() {
        parent::__construct();
    }
       public function insertNews($title,$news=array()) { 
        // $ngelDb = $this->load->database('ngelDb',TRUE);
           
           $data = array(
               'news_title'=>$title,
             'image_name' => $news['file_name'],
             'image_path' => "http://192.168.0.180/api/newsImages/".$news['file_name']
         );
//           if ($ngelDb->insert("news", $data)) 
//            return true; 
//           if($ngelDb->insert("news",$data))
//           {
////               $query="SELECT news_id from news where news_title=$title";
//               $ngelDb->distinct();
//               $ngelDb->select('news_id');
//               $ngelDb->where('news_title',$title);
//               $query=$ngelDb->get('news');
//               $results=$query->row();
//               return $results->news_id;
//           }
                      if($this->db->insert("news",$data))
           {
//               $query="SELECT news_id from news where news_title=$title";
               $this->db->distinct();
               $this->db->select('news_id');
               $this->db->where('news_title',$title);
               $query=$this->db->get('news');
               $results=$query->row();
               return $results->news_id;
           }
           else 
               return FALSE;
           
      }
      public function popularInsert($id){
          //$ngelDb = $this->load->database('ngelDb',TRUE);
          $data=array("popular_newsId"=>$id);
          $query=$this->db->insert("popular_table",$data);
             $insert= $this->db->affected_rows($query);
             if($insert){
                 return TRUE;
             }
 else {
     return "Could not update Popular Table";
 }
          
      }
      public function featuredInsert($id){
          //$ngelDb = $this->load->database('ngelDb',TRUE);
          $arr=['featured_newsId'=>$id];
          $query=$this->db->insert("featured_table",$arr);
          $insert= $this->db->affected_rows($query);
             if($insert){
                 return TRUE;
             }
 else {
     return "Could not update Featured Table";
 }
      }
       }
