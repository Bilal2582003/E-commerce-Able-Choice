<?php

include 'database.php';


    if(isset($_POST['update'])){
        // echo json_encode(array('error'=>'Theme ID is missing.')); exit;
        if(!isset($_POST['id']) || empty($_POST['id'])){
            echo json_encode(array('error'=>'Theme ID is missing.')); exit;
        }else if(empty($_POST['old_logo']) && empty($_FILES['new_logo']['name'])){
            echo json_encode(array('error'=>'Logo Field is Empty.')); exit;
        }elseif(!isset($_POST['new_sample_image']) && empty($_POST['old_sample_image'])){
            echo json_encode(array('error'=>'Image Field is Empty.')); exit;
        }else{

            if(!empty($_POST['old_logo'])  && empty($_FILES['new_logo']['name'])){
                $file_name = $_POST['old_logo'];

            }else if(!empty($_POST['old_logo']) && !empty($_FILES['new_logo']['name'])){
                $errors= array();
                 /* get details of the uploaded file */
                $file_name = $_FILES['new_logo']['name'];
                $file_size =$_FILES['new_logo']['size'];
                $file_tmp =$_FILES['new_logo']['tmp_name'];
                $file_type=$_FILES['new_logo']['type'];
                $file_name = str_replace(array(',',' '),'',$file_name);
                $file_ext=explode('.',$file_name);
                $file_ext=strtolower(end($file_ext));
                $extensions= array("jpeg","jpg","png");
                if(in_array($file_ext,$extensions)=== false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                if($file_size > 2097152){
                    $errors[]='File size must be excately 2 MB';
                }
                if(file_exists('images/'.$_POST['old_logo'])){
                    unlink('images/'.$_POST['old_logo']);
                }
                $file_name = time().str_replace(array(' ','_'), '-', $file_name);

            }else if(empty($_POST['old_logo']) && !empty($_FILES['new_logo']['name'])){
                $errors= array();
                 /* get details of the uploaded file */
                $file_name = $_FILES['new_logo']['name'];
                $file_size =$_FILES['new_logo']['size'];
                $file_tmp =$_FILES['new_logo']['tmp_name'];
                $file_type=$_FILES['new_logo']['type'];
                $file_name = str_replace(array(',',' '),'',$file_name);
                $file_ext=explode('.',$file_name);
                $file_ext=strtolower(end($file_ext));
                $extensions= array("jpeg","jpg","png");
                if(in_array($file_ext,$extensions)=== false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                if($file_size > 2097152){
                    $errors[]='File size must be excately 2 MB';
                }
                $file_name = time().str_replace(array(' ','_'), '-', $file_name);
            }


            if(!empty($_POST['old_sample_image'])  && empty($_FILES['new_sample_image']['name'])){
                $file_name_image = $_POST['old_sample_image'];

            }else if(!empty($_POST['old_sample_image']) && !empty($_FILES['new_sample_image']['name'])){
                $errors= array();
                 /* get details of the uploaded file */
                $file_name_image = $_FILES['new_sample_image']['name'];
                $file_size =$_FILES['new_sample_image']['size'];
                $file_tmp =$_FILES['new_sample_image']['tmp_name'];
                $file_type=$_FILES['new_sample_image']['type'];
                $file_name_image = str_replace(array(',',' '),'',$file_name_image);
                $file_ext=explode('.',$file_name_image);
                $file_ext=strtolower(end($file_ext));
                $extensions= array("jpeg","jpg","png");
                if(in_array($file_ext,$extensions)=== false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                if($file_size > 2097152){
                    $errors[]='File size must be excately 2 MB';
                }
                if(file_exists('images/'.$_POST['old_sample_image'])){
                    unlink('images/'.$_POST['old_sample_image']);
                }
                $file_name_image = time().str_replace(array(' ','_'), '-', $file_name_image);

            }else if(empty($_POST['old_sample_image']) && !empty($_FILES['new_sample_image']['name'])){
                $errors= array();
                 /* get details of the uploaded file */
                $file_name_image = $_FILES['new_sample_image']['name'];
                $file_size =$_FILES['new_sample_image']['size'];
                $file_tmp =$_FILES['new_sample_image']['tmp_name'];
                $file_type=$_FILES['new_sample_image']['type'];
                $file_name_image = str_replace(array(',',' '),'',$file_name_image);
                $file_ext=explode('.',$file_name_image);
                $file_ext=strtolower(end($file_ext));
                $extensions= array("jpeg","jpg","png");
                if(in_array($file_ext,$extensions)=== false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                if($file_size > 2097152){
                    $errors[]='File size must be excately 2 MB';
                }
                $file_name_image = time().str_replace(array(' ','_'), '-', $file_name_image);
            }
            // echo json_encode(array('1'=>'1')); exit;
            if(!empty($errors)){
               echo json_encode($errors); exit;
            }else{
                

                $db = new Database();
               
                $params = [
                    'color' => $db->escapeString($_POST['color']),
                    'logo' => $db->escapeString($file_name),
                    'image' => $db->escapeString($file_name_image),
                ];

                $db->update('theme',$params,"id='{$_POST['id']}'");
                
                $response = $db->getResult();
                if(!empty($response)){

                    if(!empty($_FILES['new_logo']['name'])){
                        /* directory in which the uploaded file will be moved */
                        move_uploaded_file($file_tmp,"../../images/".$file_name);
                    }
                    if(!empty($_FILES['new_sample_image']['name'])){
                        /* directory in which the uploaded file will be moved */
                        move_uploaded_file($file_tmp,"../../images/".$file_name_image);
                    }
                    echo json_encode(array('success'=>$response[0])); exit;
                }
            }
        }
    }

?>