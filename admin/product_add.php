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
    if(empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image']['name'])){
      if(empty($_POST['title'])){
        $titleError='Title cannot be null';
      }
      if(empty($_POST['content'])){
        $contentError='Content cannot be null';
      }
      if(empty($_FILES['image']['name'])){
        $imageError='Image cannot be null';
      }
    }else{
     //print_r($_FILES);
    $file='../images/'.($_FILES['image']['name']);
    $imageType=pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'png' && $imageType!= 'jpg' && $imageType!='jpeg'){
      echo "<script>alert('Image must be png,jpeg,jpeg!');</script>";
    }else{
      $title=$_POST['title'];
      $content=$_POST['content'];
      $image=$_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $file);
      $stmt=$pdo->prepare('INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id);');
      $stmt->bindValue(':title',$title);
      $stmt->bindValue(':content',$content);
      $stmt->bindValue(':image',$image);
      $stmt->bindValue(':author_id',$_SESSION['user_id']);
      $result=$stmt->execute();
      if($result){
        echo "<script>alert('Successfully Added!'); window.location.href='index.php';</script>";
      }
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
              <h1 align="center">Adding New Product</h1>
              <div class="card-body">
                <form action="product_add.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                  <label for="title">Title</label><br>
                  <p style="color:red"><?php echo empty($titleError) ? '' : '*'.$titleError; ?></p>
                  <input type="text" class="form-control" name="title">
                </div>
                <div class="form-group">
                 <label for="content">Content</label><br>
                 <p style="color:red"><?php echo empty($contentError) ? '' : '*'.$contentError; ?></p>
                 <textarea name="content" rows="8" class="form-control"></textarea>
                </div>
                <div class="form-group">
                  <label for="image">Image</label><br>
                  <p style="color:red"><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
                  <input type="file" name="image"><br>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="index.php" class="btn btn-primary" style="float:right;">BACK</a>
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






