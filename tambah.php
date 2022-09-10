<?php
session_start();

$postData = $userData = array();

// session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// menentukan data
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// bagian munculkan data
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}

// key user
if(!empty($_GET['id'])){
    include 'con.php';
    $db = new DB();
    $conditions['where'] = array(
        'id' => $_GET['id'],
    );
    $conditions['return_type'] = 'single';
    $userData = $db->getRows('users', $conditions);
}

// Pre  data
$userData = !empty($postData)?$postData:$userData;

// membuat aksi
$actionLabel = !empty($_GET['id'])?'Ubah':'Tambah';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home tambah</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
    <h2 style="text-align: center;">Home</h2>
        <h2 style="text-align: center;">Tambah Data Teman</h2>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                <?php } ?>
                        
                <div class="panel panel-default">
                    <!-- <div class="panel-heading"><a href="index.php" class="glyphicon glyphicon-arrow-left"></a></div> -->
                    <div class="panel-heading"><?php echo $actionLabel; ?> Data</div>
                    <div class="panel-body">
                        <form method="post" action="userAction.php" class="form">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="name" value="<?php echo !empty($userData['name'])?$userData['nama']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>NPM</label>
                                <input type="text" class="form-control" name="npm" value="<?php echo !empty($userData['npm'])?$userData['npm']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="<?php echo !empty($userData['alamat'])?$userData['alamat']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" class="form-control" name="jurusan" value="<?php echo !empty($userData['jurusan'])?$userData['jurusan']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Kontak</label>
                                <input type="text" class="form-control" name="kontak" value="<?php echo !empty($userData['kontak'])?$userData['kontak']:''; ?>">
                            </div>
                            <input type="hidden" name="id" value="<?php echo !empty($userData['id'])?$userData['id']:''; ?>">
                            <div class="row">
                                <div class="col-xs-1 col-md-1">
                                    <input type="submit" name="usersSubmit" class="btn btn-success" value="MASUKKAN"/>
                                </div>
                                <div class="col-xs-1 col-xs-offset-5 col-sm-1 col-sm-offset-8 col-md-1 col-md-offset-8">
                                    <span class="panel-heading"><a href="index.php"  class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>Back</a></span>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>