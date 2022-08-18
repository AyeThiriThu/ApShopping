<?php
session_start();
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
   header('Location: login.php');
  }
  // if($_SESSION['role']!=1){
  //   header('Location :login.php');
  // }  
?>
 <?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Details
                </h3>
              </div>
              <?php 
                require '../config/config.php';
                $stmt=$pdo->prepare("SELECT * FROM sale_order_details WHERE sale_order_id=".$_GET['id']);
                $stmt->execute();
                $result=$stmt->fetchAll(); 
              ?> 
              <!-- /.card-header -->
              <div class="card-body">
                <div style="float: right;">
                  <span style="color: darkred;">Order Date-</span><?php echo date('Y-m-d',strtotime($_GET['order_date'])) ?>
                </div><br><br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $i=1;
                    $total=0;
                    if($result){
                      foreach ($result as $value) {
                        ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <?php 
                            $stmt=$pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                            $stmt->execute();
                            $product=$stmt->fetchAll();
                          ?>
                          <td><?php echo escape($product[0]['name']); ?></td>
                          <td><?php echo escape($value['quantity']); ?></td>
                          <td><?php echo escape($product[0]['price']); ?></td>

                          <td><?php 
                          $subtotal=$value['quantity']*$product[0]['price'];
                          echo escape($subtotal); 
                          $total+=$subtotal;?></td>
                        </tr>
                        <?php
                      } 
                    }?>
                    <tr><td colspan="4" style="text-align: center;"><span style="align-content: right;" >Total</span></td>
                      <td><span style="color: red; font-size: 20px;"><?php echo $total; ?>kyats</span></td>
                    </tr>

                  </tbody>
                </table>
                <br><br>
                 <div>
                 <a href="orders.php" type="button" class="btn btn-success">Back</a> 
                </div><br>
              </div>
              <!-- /.card-body --> 
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



