<?php
    function SimpleUpload($fieldname, $path) {
       $Result = array();
       if (is_array($_FILES[$fieldname]["tmp_name"])) {
         foreach ($_FILES[$fieldname]["tmp_name"] as $key => $value) {
           if (true) {
               $tmp_name = $_FILES[$fieldname]["tmp_name"][$key];
               $name = basename($_FILES[$fieldname]["name"][$key]);
               $hash = substr(md5(uniqid(microtime())), 1, 16);
               $name = $hash . strrchr(strtolower($name), '.');

               $isloaded = true;
               If (!move_uploaded_file($tmp_name, $path.$name) ) {
                   If (!copy($tmp_name, $path.$name) ) {
                      $isloaded = false;
                   } 
               }
               if ($isloaded)   {
                 $Result[] = $name;
               } else {
                 echo 'ERROR';
               };

           };
         };
       } else {

           if (!empty($_FILES[$fieldname]["tmp_name"])) {
               $tmp_name = $_FILES[$fieldname]["tmp_name"];
               $name = basename($_FILES[$fieldname]["name"]);
               $hash = substr(md5(uniqid(microtime())), 1, 16);
               $name = $hash . strrchr(strtolower($name), '.');
               $isloaded = true;
               If (!move_uploaded_file($tmp_name, $path.$name) ) {
                   If (!copy($tmp_name, $path.$name) ) {
                      $isloaded = false;
                   } 
               }
               if ($isloaded)   {
                 $Result[] = $name;
               } else {
                 echo 'ERROR';
               };

           };            
       };            
       return $Result;
    } 
