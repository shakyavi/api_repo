<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewsModel
 *
 * @author ubuntu
 */
class NewsModel extends CI_Model{
    public static $ngelDb;
    //put your code here
    function __construct() {
        parent::__construct();
        //self::$ngelDb = $this->load->database('ngelDb',TRUE);
        
    }
    //get all news 
    public function allNews($id){
        
        //  $query= self::$ngelDb->query('Select * from news');
        
        //get all news from news table
//        $this->db->order_by("news_id","DESC");
//        $query=  $this->db->get('news');
//        $allnews=$query->result();
//     
       
        //get id of favorites news
//        $favQuery = "SELECT favorites_table.news_id FROM `news` LEFT JOIN `favorites_table` ON news.news_id = favorites_table.news_id WHERE favorites_table.user_id = ".$id;
//        $fav=  $this->db->query($favQuery);
//        $data=$fav->result();
        //return merged array of favorites and all news id
        //$merged=  [$data,$allnews];
        //single call to sql routine with favorites and count
        $newQuery = $this->db->query('CALL get_news(?);',$id);
        $output = $newQuery->result();
        
   
        return $output;
  
}
//get featured news
public function featuredNews($id){
//get data from ngels db    
//$query=  self::$ngelDb->query('SELECT * FROM `featured_table` FULL JOIN `news` ON `featured_newsId` = `news_id`');
    
//get data from featured table only
//$query= $this->db->query('SELECT * FROM `featured_table` FULL JOIN `news` ON `featured_newsId` = `news_id`');
    //call sql routine with favorites & count
   $query=  $this->db->query('CALL get_featured(?);',$id);   
return $query->result_array();
   
}
//get popular news
public function popularNews($id){
    //get data from popular table only    
    //$query=  $this->db->query('SELECT * FROM `popular_table` FULL JOIN `news` ON `popular_newsId` = `news_id`');
    //call sql routine with favorites & count
    $query=  $this->db->query('CALL get_popular(?);',$id);
   return $query->result_array();
}
//update popular
public function popularInsert($id){
          //$ngelDb = $this->load->database('ngelDb',TRUE);
          $data=array("popular_newsId"=>$id);
          $this->db->where("popular_newsId",$id);
          $check=$this->db->get("popular_table");
          
          //call to sql routine
         // $check=$this->db->query('CALL popular_check(?);',$id);
          
          if($check->num_rows()>0)
          {
              return TRUE;
          }
          else
          {
          $query=$this->db->insert("popular_table",$data);
             $insert= $this->db->affected_rows($query);
             if($insert){
                 return TRUE;
             }
            else {
            return "Could not update Popular Table";
            }
         }
}
public function popularRemove($newsId){
    $data = array('popular_newsId'=>$newsId);
    $this->db->where($data);
    $query=$this->db->delete('popular_table',$data);
      
    $result = $this->db->affected_rows();
    if(!$result)
    {
        return "Update Error";
    }
 else {
        return  TRUE;
    }
}
//get favorite news
public function favoriteNews($id){
   //from ngels db
    //$query=  self::$ngelDb->query("SELECT * FROM `favorites_table` FULL JOIN `news` ON favorites_table.news_id = news.news_id WHERE `user_id`= ".$id);
    
    //$query=  $this->db->query("SELECT * FROM `favorites_table` JOIN `news` ON favorites_table.news_id = news.news_id WHERE `user_id` = ".$id);
   $query=$this->db->query('CALL get_favorites(?);',$id);
    $result=$query->result_array();
  
   if(!$result)
   {
        //var_dump($result);
       return FALSE;
   }
   else
   return $result; 
    
}
public function setFavoriteNews($userId,$newsId){
    $data = array('news_id'=>$newsId, 'user_id'=>$userId);
    //check if newsId is valid
    $this->db->where('news_id',$newsId);
    $check = $this->db->get('news');
    $checkResult = $check->result();
    
    //check if item is already favorite
    $this->db->where('news_id',$newsId);
    $this->db->where('user_id',$userId);
    $favorite = $this->db->get('favorites_table');
    $favoriteResult = $favorite->result();
    //invalid news ID
    if(!$checkResult)
    {
        return "Invalid News Id";
    }
    
   //item is already favorite:; remove from fav
    if($favoriteResult){
        $this->delFavoriteNews($userId, $newsId);
        $msg['msg']="Removed from favorites";
        $msg['code']="0";
        return $msg;
    }
    //otherwise add to favorites
    else{
        $query=$this->db->insert('favorites_table',$data);
        $result = $this->db->affected_rows();
        if(!$result)
        {
        return FALSE;
        }
        else {
            $this->countFavorites();
        return  TRUE;
        }
    }
}
private function delFavoriteNews($userId,$newsId){
    $data = array('news_id'=>$newsId, 'user_id'=>$userId);
    $this->db->where($data);
    $query=$this->db->delete('favorites_table',$data);
      
    $result = $this->db->affected_rows();
    if(!$result)
    {
        return "Update Error";
    }
 else {
     $this->countFavorites();
        return  TRUE;
    }
}

public function countFavorites(){
    $query="SELECT news_id, COUNT(*) AS count FROM favorites_table GROUP BY news_id";
    $getCount = $this->db->query($query);
    //$getCount = $this->db->query('CALL countFavorites();');
    
    $favCount=($getCount->result_array());
    foreach($favCount as $favRow){
        //echo "<br>".$favRow['news_id'].":".$favRow['count'];
        $newsId=$favRow['news_id'];
        $count=$favRow['count'];
        
        if($count>=2){
            //if favorite count>=2 insert to popular
            $this->popularInsert($newsId);
        }
        elseif($count<2)
        {
            $this->popularRemove($newsId);
        }
    }
    
}
}
