<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h3>Your file was successfully uploaded!</h3>  
		
      <ul> 
         <?php foreach($upload_data as $item => $value):?> 
         <li><?php echo $item;?>: <?php echo $value;?></li> 
         <?php endforeach;  ?>
      </ul>  
		
        <p><input type="button" onclick="windows.location=<?php echo base_url();?>upload/uploadNews" value="Upload Another File!"></p>  
        <a href = "<?php echo site_url(); ?>upload/uploadNews">Upload Another File</a>
    </body>
</html>