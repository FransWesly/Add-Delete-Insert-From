<?php
session_start();

$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Status from saya
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// koneksi database
require_once 'DB.class.php';
$db = new DB();

$perPageLimit = 300;
$offset = !empty($_GET['page'])?(($_GET['page']-1)*$perPageLimit):0;

// Get search keyword
$searchKeyword = !empty($_GET['sq'])?$_GET['sq']:'';
$searchStr = !empty($searchKeyword)?'?sq='.$searchKeyword:'';

// Pencarian Data
$searchArr = '';
if(!empty($searchKeyword)){
    $searchArr = array(
        'name' => $searchKeyword,
        'email' => $searchKeyword,
        'phone' => $searchKeyword
    );
}

// koneksi ke tabel users saya
$con = array(
    'like_or' => $searchArr,
    'return_type' => 'count'
);
$rowCount = $db->getRows('users', $con);


// kenalkan Id
$con = array(
    'like_or' => $searchArr,
    'start' => $offset,
    'limit' => $perPageLimit,
    'order_by' => 'id DESC',
);
$users = $db->getRows('users', $con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FullDatabases/frans</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Tugas Besar</h2>
		<h2 style="text-align: center;">Daftar Kontak Teman</h2>
        <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
        <div class="alert alert-success"><?php echo $statusMsg; ?></div>
        <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
        <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
        <?php } ?>

        <div class="row">
            <div class="col-md-3 search-panel">
                <form>
                <div class="input-group">
                    <input type="text" name="sq" class="form-control" placeholder="Cari data..." value="<?php echo $searchKeyword; ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                        </button>
						
                    </div>
                </div>
				
                </form>
            </div>
        </div>
        <table class="table table-striped table-bordered">
            <br>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($users)){ $count = 0; 
                    foreach($users as $user){ $count++;
                ?>
                <tr>
                    <td><?php echo ''.$count; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td>
                        <a title="Sunting" href="addEdit.php?id=<?php echo $user['id']; ?>" class="glyphicon glyphicon-edit"></a>
                        <a title="Hapus" href="userAction.php?action_type=delete&id=<?php echo $user['id']; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Yakin ingin Menghapus Data?')"></a>
                    </td>
					
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="5">maaf user tidak filed...</td></tr>
                <?php } ?>
			
            </tbody>
            <tfoot>
                
            </tfoot>
			
        </table>
        <div class="col-xs-1 col-xs-offset-5 col-sm-1 col-sm-offset-8 col-md-1 col-md-offset-8">
            <span class="panel-heading"><a href="index.php"  class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a></span>
            </div>
    </div>
    <div class="navbar navbar-collapse navbar-fixed-bottom">
            <div class="container">
			   <p style="text-align: center;">by:@Franswesli</p>
            </div>
    </div>
</body>
</html>