<?php
  session_start();
  require '../config/config.php';
  require '../config/common.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
    if ($_SESSION['role']!=1) {
    header('Location : login.php');
  }

  if ($_POST) {
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
      $stmt=$pdo->prepare("INSERT INTO categories(name,description) VALUES (:name,:description);");
      $stmt->bindValue(':name',$name);
      $stmt->bindValue(':description',$description);
      $result=$stmt->execute();
      if($result){
        echo "<script>alert('Successfully added new category!!');window.location.href='categories.php';</script>";
      }
    }
  } 
?>
 <?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <h1 align="center">Adding New Category</h1>
              <div class="card-body">
                <form action="cat_add.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                  <label for="name">Name</label><br>
                  <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                  <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                 <label for="description">Description</label><br>
                 <p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                 <textarea name="description" rows="8" class="form-control"></textarea>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="categories.php" class="btn btn-primary" style="float:right;">BACK</a>
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

 




