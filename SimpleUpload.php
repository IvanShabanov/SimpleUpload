<?php

    /* Retunr html input field for files */
    /* $fieldname - input name */
    /* $multiple = false/true - if need multiple upload */
    /* $fieldoptions =  some opltion, like class (example: 'class="files"')*/
    function FormField($fieldname, $multiple = false, $fieldoptions = '') {
      if ($multiple) {
         return '<input type="file" name="'.$fieldname.'[]" multiple '.$fieldoptions.'/>';
      }
      return '<input type="file" name="'.$fieldname.'" '.$fieldoptions.' />';
    }
    
    /* Return
        array(
            [] => array(
                'original' - original filename
                'uploaded' - uploaded filename
                'full_path' - full path to uploaded file
            )
            ['errors'] => array(
                'Text of error'
            )
        )
    */
/* $fieldname - input name */
/* $dir - upload directory */
/* $savenames - false/true save origin file names */
/* $avalable_extensions = array () */
/* $pattern - preg выражение по которому проверяется допустимость файла */
function SimpleUpload($fieldname, $dir, $savenames = false, $avalable_extensions = null, $pattern = null) {
    $Result = array();
    if (substr($dir, 0, -1) != '/') {
        $dir .= '/';
    };
    if (is_array($_FILES[$fieldname]["tmp_name"])) {
        foreach ($_FILES[$fieldname]["tmp_name"] as $key => $value) {
            if (!empty($_FILES[$fieldname]["tmp_name"][$key])) {
                $upload_this = true;
                $file = array();
                $file['original'] = basename($_FILES[$fieldname]["name"][$key]);

                $file['uploaded'] = $file['original'];
                $extension = explode(".", $file['original']);
                $extension = end($extension);
                $extension = mb_strtolower($extension);
                if (!$savenames) {
                    $hash = substr(md5(uniqid(microtime())), 1, 16);
                    $file['uploaded'] = $hash . '.' . $extension;
                }
                $file['full_path'] = $dir . $file['uploaded'];

                if (!is_null($avalable_extensions)) {
                    if (!is_array($avalable_extensions)) {
                        $avalable_extensions = explode(',', $avalable_extensions);
                    };
                    if (is_array($avalable_extensions)) {
                        if (!in_array($extension, $avalable_extensions)) {
                            $upload_this = false;
                            $Result['errors'][] = 'Не допустимый тип файла: ' . $file['original'];
                        }
                    }

                };
                if (!is_null($pattern)) {
                    if (!preg_match($pattern, $file['uploaded'])) {
                        $upload_this = false;
                        $Result['errors'][] = 'Не допустимые символы в имени файла: ' . $file['original'];
                    }
                };
                if ($upload_this) {
                    $tmp_name = $_FILES[$fieldname]["tmp_name"][$key];
                    $isloaded = true;
                    if (!move_uploaded_file($tmp_name, $file['full_path'])) {
                        if (!copy($tmp_name, $file['full_path'])) {
                            $isloaded = false;
                        }
                    }
                    if ($isloaded) {
                        $Result[] = $file;
                    } else {
                        $Result['errors'][] = 'Ошибка загрузки: ' . $file['original'];
                    };
                };
            };
        };
    } else {
        if (!empty($_FILES[$fieldname]["tmp_name"])) {
            $upload_this = true;
            $file = array();
            $file['original'] = basename($_FILES[$fieldname]["name"]);
            $file['uploaded'] = $file['original'];
            $extension = explode(".", $file['original']);
            $extension = end($extension);
            $extension = mb_strtolower($extension);
            if (!$savenames) {
                $hash = substr(md5(uniqid(microtime())), 1, 16);
                $file['uploaded'] = $hash . '.' . $extension;
            }
            $file['full_path'] = $dir . $file['uploaded'];

            if (!is_null($avalable_extensions)) {
                if (!is_array($avalable_extensions)) {
                    $avalable_extensions = explode(',', $avalable_extensions);
                };
                if (is_array($avalable_extensions)) {
                    if (!in_array($extension, $avalable_extensions)) {
                        $upload_this = false;
                        $Result['errors'][] = 'Не допустимый тип файла: ' . $file['original'];
                    }
                }
            };
            if (!is_null($pattern)) {
                if (!preg_match($pattern, $file['uploaded'])) {
                    $upload_this = false;
                    $Result['errors'][] = 'Не допустимые символы в имени файла: ' . $file['original'];
                }
            };
            if ($upload_this) {
                $tmp_name = $_FILES[$fieldname]["tmp_name"];
                $isloaded = true;
                if (!move_uploaded_file($tmp_name, $file['full_path'])) {
                    if (!copy($tmp_name, $file['full_path'])) {
                        $isloaded = false;
                    }
                }
                if ($isloaded) {
                    $Result[] = $file;
                } else {
                    $Result['errors'][] = 'Ошибка загрузки: ' . $file['original'];
                };
            };
        };
    };
    return $Result;
}
