<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>File Upload</title>
    </head>
    <body>
      <?php
     // echo "Hello"; 
      echo $error; 
      //echo form_open_multipart('upload/do_upload');?> 
        <form action="<?php echo site_url();?>upload/uploadNews/uploadFunc" method="post" enctype="multipart/form-data">
<!--            <label for="user">
                <input type="text" name="user">-->
<label for='newsTitle'>News Title</label>
            <input type="text" name="newsTitle">
                <br><br>
                <input type="checkbox" name="Popular" value="Popular" id="Popular">Popular
                <br><br>
                <input type="checkbox" name="Featured" value="Featured" id="Featured">Featured
                <br><br>
                <input type = "file" name = "userfile" size = "20" /> 
         <br /><br /> 
         <input type = "submit" value = "upload" /> 
     </form>
		
    </body>
</html>
