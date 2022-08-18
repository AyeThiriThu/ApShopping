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
		if(empty($_POST['name']) || empty($_POST['description'])|| empty($_POST['category']) || empty($_POST['quantity'])|| empty($_POST['price']) ){
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
    }else{
      $name=$_POST['name'];
      $description=$_POST['description'];
      $category_id=$_POST['category'];
      $quantity=$_POST['quantity'];
      $price=$_POST['price'];
			$id=$_POST['id'];
			if($_FILES['image']['name']!=null){
				$file='images/'.$_FILES['image']['name'];
				$imageType=pathinfo($file,PATHINFO_EXTENSION);
				if($imageType!='png' && $imageType!='jpg' && $imageType!='jpeg'){
					echo "<script>alert('Image must be png,jpg or jpeg!');</script>";
				}else{
					$image=$_FILES['image']['name'];	
			 		move_uploaded_file($_FILES['image']['tmp_name'], $file);
			 		$stmt1=$pdo->prepare("UPDATE products SET name='$name', description='$description', category_id='$category_id',quantity='$quantity',price='$price', image='$image' WHERE id='$id';");
			 		$result1=$stmt1->execute();
			 		if($result1){
			 			
	      		header('Location: index.php?pageno='.$_GET['pageno']);
	    		}	
				} 	
			}else{
				$stmt1=$pdo->prepare("UPDATE products SET name='$name', description='$description', category_id='$category_id',quantity='$quantity',price='$price' WHERE id='$id';");
				$result1=$stmt1->execute();
				if($result1){
	      	header('Location: index.php?pageno='.$_GET['pageno']);
	    	}
			}	
    }	
	}
	$stmt=$pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
	$stmt->execute();
	$result=$stmt->fetchAll();
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
                <input type="hidden" name="id" value="<?php echo escape($result[0]['id']); ?>">
                
                <div class="form-group">
                  <label for="name">Name</label><br>
                  <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                  <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']); ?>" >
                </div>
                <div class="form-group">
                 <label for="description">Description</label><br>
                 <p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                 <textarea name="description" rows="5" class="form-control"><?php echo escape($result[0]['description']); ?></textarea>
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
                     <option value="<?php echo escape($value['id']);?>" <?php if($value['id']==$result[0]['category_id']){echo "selected";}; ?>><?php echo escape($value["name"]); ?></option>
                    <?php 
                    }
                    ?>
                 </select>
                </div>
                <div class="form-group">
                  <label for="quantity">Quantity</label><br>
                  <p style="color:red"><?php echo empty($qtyError) ? '' : '*'.$qtyError; ?></p>
                  <input type="number" class="form-control" name="quantity" value="<?php echo escape($result[0]['quantity']); ?>">
                </div>
                <div class="form-group">
                  <label for="price">Price</label><br>
                  <p style="color:red"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                  <input type="number" class="form-control" name="price" value="<?php echo escape($result[0]['price']); ?>">
                </div>
                <div class="form-group">
                  <label for="image">Image</label><br>
                  <img src="images/<?php echo escape($result[0]['image']); ?>" width="100px" height="100px"><br><br>
                  <input type="file" name="image" ><br><br>
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
