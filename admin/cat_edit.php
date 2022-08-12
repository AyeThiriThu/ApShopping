<?php 
session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
    if ($_SESSION['role']!=1) {
    header('Location : login.php');
  }
	require '../config/config.php';
  require '../config/common.php';
	
	if($_POST){	
		if(empty($_POST['name'])||empty($_POST['description'])){
      if(empty($_POST['name'])){
        $nameError="Name cannot be null!";
      }
      if(empty($_POST['description'])){
        $descError="Description cannot be null!";
      }
    }else{
	    $name=$_POST['name'];
      $description=$_POST['description'];
			$id=$_POST['id'];
			$stmt1=$pdo->prepare("UPDATE categories SET name='$name', description='$description' WHERE id='$id';");
			$result1=$stmt1->execute();
			if($result1){
	      header('Location: categories.php?pageno='.$_GET['pageno']);
	    }		
    }	
	}
	$stmt=$pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
	$stmt->execute();
	$result=$stmt->fetchAll();
	// print "<pre>";
	// print_r($result);
	// exit();
?>

<?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                <div class="form-group">
                  <label for="name">Title</label><br>
                  <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                  <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']); ?>">
                </div>
                <div class="form-group">
                 <label for="description">Content</label><br>
                 <p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                 <textarea name="description" rows="8" class="form-control"><?php echo escape($result[0]['description']); ?></textarea>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="index.php?pageno=<?php echo escape($_GET['pageno']); ?>" class="btn btn-primary" style="float:right;">BACK</a>
                </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  
  <?php include('footer.html'); ?>
