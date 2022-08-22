<?php
session_start();
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
   header('Location: login.php');
  }
  if($_SESSION['role']!=1){
    header('Location :login.php');
  }  
?>
 <?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Listing</h3>
              </div>
              <?php 
                require '../config/config.php';
                if(!empty($_GET['pageno'])){
                  $pageno=$_GET['pageno'];
                }else{
                  $pageno=1;
                }
                $numOfRecs=5;
                $offset=($pageno-1)*$numOfRecs;
                $stmt=$pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                $stmt->execute();
                $rawresult=$stmt->fetchAll();
                $totalpages=ceil(count($rawresult)/$numOfRecs);
                $stmt1=$pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfRecs ");
                $stmt1->execute();
                $result=$stmt1->fetchAll();
                //print '<pre>';
                //print_r($result);
                
              ?> 
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $i=1;
                    if($result){
                      foreach ($result as $value) {
                        ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <?php 
                            $stmt=$pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                            $stmt->execute();
                            $user=$stmt->fetchAll();

                          ?>
                          <td><?php echo escape($user[0]['name']); ?></td>
                          <td><?php echo escape($value['total_price']); ?></td>
                          <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))); ?></td>
                          <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="order_details.php?id=<?php echo $value['id'];?>&order_date=<?php echo $value['order_date'] ?>&pageno=<?php echo $pageno ?>" type="button" class="btn btn-warning">View</a>
                            </div>
                          </div>
                          </td>
                        </tr>
                        <?php
                      } 
                    }?>
                  </tbody>
                </table>
                <nav aria-label="Page navigation example" style="float:right">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{echo '?pageno='.($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno>=$totalpages){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno>=$totalpages){echo '#';}else{echo '?pageno='.($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpages; ?>">Last</a></li>  
                  </ul>
                </nav>
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



