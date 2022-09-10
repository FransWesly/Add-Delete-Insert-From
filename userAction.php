<?php
session_start();

require_once 'con.php';
$db = new DB();
$tblName = 'users';
$redirectURL = 'index.php';

if(isset($_POST['userSubmit'])){
    // Get submitted data
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $id     = $_POST['id'];
    $userData = array(
        'name'  => $name,
        'email' => $email,
        'phone' => $phone
    );

    $sessData['postData'] = $userData;
    $sessData['postData']['id'] = $id;
    
    $idStr = !empty($id)?'?id='.$id:'';

    if(!empty($name) && !empty($email) && !empty($phone)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            if(!empty($id)){
                // Update data
                $condition = array('id' => $id);
                $update = $db->update($tblName, $userData, $condition);
                
                if($update){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Data berhasil diperbarui.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'addEdit.php'.$idStr;
                }
            }else{
                $insert = $db->insert($tblName, $userData);
                
                if($insert){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Data berhasil ditambahkan.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    
                    $redirectURL = 'addEdit.php';
                }
            }
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg']  = 'Email yang Anda masukkan tidak valid.';
            
            $redirectURL = 'addEdit.php'.$idStr;
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Data tidak boleh kosong. Silahkan isi dengan lengkap!';
        
        $redirectURL = 'addEdit.php'.$idStr;
    }
    
    $_SESSION['sessData'] = $sessData;

    header("Location: ".$redirectURL);
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
    $condition = array('id' => $_GET['id']);
    $delete = $db->delete($tblName, $condition);
    if($delete){
        $sessData['status']['type'] = 'success';
        $sessData['status']['msg']  = 'Data berhasil dihapus!';
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Some problem occurred, please try again.';
    }
    
    $_SESSION['sessData'] = $sessData;
}

header("Location: ".$redirectURL);
exit();