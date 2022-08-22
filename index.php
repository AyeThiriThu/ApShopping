<?php 

				include('header.php'); 
                require 'config/config.php';
                if(!empty($_GET['pageno'])){
                  $pageno=$_GET['pageno'];
                }else{
                  $pageno=1;
                }
                $numOfRecs=3;
                $offset=($pageno-1)*$numOfRecs;

                if(!empty($_POST['search'])){
    				setcookie('search',$_POST['search'],time()+(86400*30),"/");
  				}else{                            //delete cookie, otherwise other page will get cookie 
    				if(empty($_GET['pageno'])){
      					unset($_COOKIE['search']);
      					setcookie('search',null,-1,'/');
    				}				
  				}

                if(empty($_POST['search']) && empty($_COOKIE['search'])){
                  $stmt=$pdo->prepare("SELECT * FROM products ORDER BY id DESC");
                  $stmt->execute();
                  $rawresult=$stmt->fetchAll();
                  $totalpages=ceil(count($rawresult)/$numOfRecs);

                  $stmt1=$pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfRecs ");
                  $stmt1->execute();
                  $result=$stmt1->fetchAll();
                  //print '<pre>';
                   //print_r($result);
                }else{
                  if(empty($_GET['pageno'])){
                    $searchKey=$_POST['search'] ;
                  }else{
                    $searchKey=$_COOKIE['search'];
                  }
                  //$searchKey=$_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
                  $stmt=$pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                  $stmt->execute();
                  $rawresult=$stmt->fetchAll();
                  //print_r($rawresult);
                  $totalpages=ceil(count($rawresult)/$numOfRecs);

                  $stmt1=$pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecs ");
                  $stmt1->execute();
                  $result=$stmt1->fetchAll();
                  // print "<pre>";
                  // print_r($result);exit();

                }
                $catstmt=$pdo->prepare("SELECT * FROM categories");
                $catstmt->execute();
                $catname=$catstmt->fetchAll();
                // print "<pre>";
                // print_r($catname);

?>
<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						<?php 
						$i=0;
						foreach ($catname as $value) { ?>
						<li class="main-nav-list">
							<a data-toggle="collapse" href="#">
								<span
								 class="lnr lnr-arrow-right"></span><?php echo escape($catname[$i++]['name']); ?>
							</a>	
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="pagination">
						<a href="?pageno=1" class="">First</a>
						<a <?php if($pageno<=1){echo 'disabled';} ?> href="<?php if($pageno<=1){echo '#';}else{echo '?pageno='.($pageno-1);} ?>" class="prev-arrow">
							<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
						</a>
						<a href="#" class="active"><?php echo $pageno; ?></a>
						<a <?php if($pageno>=$totalpages){echo 'disabled';} ?> href="<?php if($pageno>=$totalpages){echo '#';}else{echo '?pageno='.($pageno+1);} ?>" class="next-arrow">
							<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
						</a>
						<a href="?pageno=<?php echo $totalpages; ?>" class="">Last</a>
					</div>
				</div>
				<!-- End Filter Bar -->
				
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<!-- single product -->
					<?php 
						if($result){
					foreach ($result as $value) { ?>
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
								<img class="" src="admin/images/<?php echo escape($value['image']) ?>" alt="" height='300px';>
								<div class="product-details">
									<h6><?php echo escape($value['name']); ?></h6>
									<div class="price">
										<h6><?php echo escape($value['price']) ?>MMK</h6>
									</div>
									<div class="prd-bottom">

										<a href="" class="social-info">
											<span class="ti-bag"></span>
											<p class="hover-text">add to bag</p>
										</a>
										<a href="" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
										</a>
									</div>
								</div>
							</div>
						</div>			
							<?php	}
							}
						 ?>
						
					</div>
				</section>
				<!-- End Best Seller -->
<?php include('footer.php');?>
