<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewsFeed
 *
 * @author ubuntu
 */
class NewsFeed extends ApiAuth{
    //public $userID = parent::$currentUserId;
    //put your code here
    function __construct($config = 'rest') {
        parent::__construct($config);
       parent::index();
        $this->load->model('NewsModel');
    }
    public function index_get(){
        $this->allNews_get();
        
    }
    //get all news items
    public function allNews_get(){
        $news = $this->NewsModel->allNews(parent::$currentUserId);
        if(!$news){
            $this->response(array('status'=>'Failed','message'=>'Unable to Read data'),  REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
            $this->response($news,200);
    }
    
    //get featured news
    public function featured_get(){
        $featured = $this->NewsModel->featuredNews(parent::$currentUserId);
        if(!$featured){
             $this->response(array('status'=>'Failed','message'=>'Unable to Read data'),  REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
            $this->response($featured,200);
       }
    
    //get popular news items
    public function popular_get(){
             $popular = $this->NewsModel->popularNews(parent::$currentUserId);
        if(!$popular){
             $this->response(array('status'=>'Failed','message'=>'Unable to Read data'),  REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
            $this->response($popular,200);
    }
    //get favorite news
    public function favorite_get(){
        $userId = parent::$currentUserId;
       //echo $userId;
        $favorite = $this->NewsModel->favoriteNews($userId);
       // var_dump($favorite);
        
        if($favorite===FALSE){
            $msg=array('status'=>'Failed','message'=>'No favorite articles');
            $this->response($msg,404);
            }
 else {
            $this->response($favorite,200);
 }
    }
    
       //set favorite news
       public function favorite_post(){
           $newsId = $this->post('newsId');
           $userId = parent::$currentUserId;
           $setFavorite = $this->NewsModel->setFavoriteNews($userId,$newsId);
           if($setFavorite===TRUE)
           {
               $this->response(array('status'=>'Success','message'=>'Added To Favorites','messageCode'=>"1"),200);
           }
           elseif($setFavorite===FALSE)
           {
               $this->response(array('status'=>'Failure','message'=>'Unable To Update','messageCode'=>"0"),500);
           }
           else
               $this->response(array('status'=>'OK','message'=>$setFavorite['msg'],'messageCode'=>$setFavorite['code']),  REST_Controller::HTTP_OK);
       }
       //remove favorite news
//       public function deleteFavorite_post(){
//           $newsId = $this->post('newsId');
//           $userId = parent::$currentUserId;
//           $delFavorite = $this->NewsModel->delFavoriteNews($userId,$newsId);
//           if($delFavorite===TRUE)
//           {
//               $this->response(array('status'=>'Success','message'=>'Favorites Updated'),200);
//           }
//           else
//               $this->response(array('status'=>'Failure','message'=>'Unable to Update'),500);
//       }
}
