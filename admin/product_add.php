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

  if($_POST) {
    if(empty($_POST['name']) || empty($_POST['description'])|| empty($_POST['category']) || empty($_POST['quantity'])|| empty($_POST['price']) ||  empty($_FILES['image']['name'])){
      if(empty($_POST['name'])){
        $nameError='Name cannot be null';
      }
      if(empty($_POST['description'])){
        $descError='Description cannot be null';
      }
      if(empty($_POST['category'])){
        $catError='Category cannot be null';
      }
      if(empty($_POST['quantity'])){
        $qtyError='Quantity cannot be null';
      }elseif(is_numeric($_POST['quantity']) != 1){
       $qtyError='Quantity must be number!!'; 
      }
      if(empty($_POST['price'])){
        $priceError='Price cannot be null';
      }elseif(is_numeric($_POST['price']) != 1){
       $priceError='Price must be number!!'; 
      }
      if(empty($_FILES['image']['name'])){
        $imageError='Image cannot be null';
      }
    }else{
     //print_r($_FILES);
      $file='images/'.($_FILES['image']['name']);
      $imageType=pathinfo($file,PATHINFO_EXTENSION);

      if($imageType != 'png' && $imageType!= 'jpg' && $imageType!='jpeg'){
        echo "<script>alert('Image must be png,jpg,jpeg!');</script>";
      }else{
        $image=$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
        $stmt=$pdo->prepare('INSERT INTO products(name,description,category_id,quantity,price,image) VALUES (:name,:description,:category_id,:quantity,:price,:image);');
        $stmt->bindValue(':name',$_POST['name']);
        $stmt->bindValue(':description',$_POST['description']);
        $stmt->bindValue(':category_id',$_POST['category']);
        $stmt->bindValue(':quantity',$_POST['quantity']);
        $stmt->bindValue(':price',$_POST['price']);
        $stmt->bindValue(':image',$image);
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
                 <label for="categoty">Category</label><br>
                 <p style="color:red"><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
                 <select class="form-control" name="category">
                   <option value="">SELECT CATEGORY</option>
                   <?php 
                    $stmt=$pdo->prepare("SELECT * FROM categories");
                    $stmt->execute();
                    $catname=$stmt->fetchAll();
                    foreach ($catname as $value) {
                    ?>
                     <option value=<?php echo $value["id"]; ?>><?php echo $value["name"]; ?></option>
                    <?php 
                    }
                    ?>
                 </select>
                </div>
                <div class="form-group">
                  <label for="quantity">Quantity</label><br>
                  <p style="color:red"><?php echo empty($qtyError) ? '' : '*'.$qtyError; ?></p>
                  <input type="number" class="form-control" name="quantity">
                </div>
                <div class="form-group">
                  <label for="price">Price</label><br>
                  <p style="color:red"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                  <input type="number" class="form-control" name="price">
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






