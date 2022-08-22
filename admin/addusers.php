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
  
    if ($_POST) {
      if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])<4 || empty($_POST['address']) || empty($_POST['phone'])){
        if(empty($_POST['name'])){
          $nameError='Name cannot be null';
        }
        if(empty($_POST['email'])){
          $emailError='Email cannot be null';
        }
        if(empty($_POST['password'])){
          $passwordError='Password cannot be null';
        }
        if(strlen($_POST['password'])<4){
          $passwordError="Password should be at least 4 characters!";
        }
        if(empty($_POST['address'])){
          $addressError='Address cannot be null';
        }
        if(empty($_POST['phone'])){
          $phoneError='Phone cannot be null';
        }
      }else{
        $name=$_POST['name'];
        $email=$_POST['email'];
        $address=$_POST['address'];
        $phone=$_POST['phone'];
        $password=password_hash($_POST['password'],PASSWORD_DEFAULT);; 
        if(empty($_POST['admin'])){
            $role='0';
        }else{
            $role='1';
        }  
          
        $stmtforemail=$pdo->prepare('SELECT * FROM users WHERE email=:email');
        $stmtforemail->bindValue(':email',$email);
        $stmtforemail->execute();
        $sameemail=$stmtforemail->fetch(PDO::FETCH_ASSOC);
        if(empty($sameemail)){
          $stmt=$pdo->prepare('INSERT INTO users(name,email,password,address,phone,role) VALUES (:name,:email,:password, :address, :phone, :role);');
          $stmt->bindValue(':name',$name);
          $stmt->bindValue(':email',$email);
          $stmt->bindValue(':password',$password);
          $stmt->bindValue(':address',$address);
          $stmt->bindValue(':phone',$phone);
          $stmt->bindValue(':role',$role);
          $result=$stmt->execute();
          if($result){
            echo "<script>alert('Successfully Added!');window.location.href='users.php'; </script>";
          }  
        }else{
          echo "<script>alert('User already exits.Please use another email!');window.location.href='users.php';</script>";
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
              <div class="card-body">
                <form action="addusers.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                  <label for="name">Name</label><br>
                  <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                  <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                 <label for="email">Email</label><br>
                 <p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                 <input type="email" class="form-control" name="email">
                </div>
                <div class="form-group">
                 <label for="password">Password</label><br>
                 <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                 <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                 <label for="address">Address</label><br>
                 <p style="color:red"><?php echo empty($addressError) ? '' : '*'.$addressError; ?></p>
                 <input type="text" class="form-control" name="address">
                </div>
                <div class="form-group">
                 <label for="phone">Phone</label><br>
                 <p style="color:red"><?php echo empty($phoneError) ? '' : '*'.$phoneError; ?></p>
                 <input type="text" class="form-control" name="phone">
                </div>
                <div class="form-group">
                 <label for="admin">Admin</label><br>
                 <input type="checkbox" name="admin">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="users.php" class="btn btn-primary" style="float:right;">BACK</a>
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






