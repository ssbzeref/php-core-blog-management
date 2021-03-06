<?php include_once( '../include/database.php'); ?>
<?php include_once( '../include/functions.php'); ?>
<?php include_once( '../include/Sessions.php'); ?>
<?php confirm_login(); ?>
<?php 
    global $connection;
    $dataError='';
    $sn=0 ;
    $sql="SELECT * FROM blog ORDER BY datetime DESC" ;
    $result=$connection->query($sql);


	$unapproveCommentCount = "SELECT count(*) as upapproveComment from comments where status='Pending'";
	$count = $connection->query($unapproveCommentCount);

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<script src="../js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../css/adminstyles.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Dashboard</title>
	<style>
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
				<div class="nav-header">
				<!-- <a href="index.php" style="argin: -6px;margin-right: 10px;" class="text-decoration-none text-white">
               CMS
            </a> -->
				</div>
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
					<li class="nav-item active"> <a class="nav-link" href="../blog.php">Home</a>
					</li>
				</ul>
				<!-- <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> -->
			</div>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 mt-2">
			<h1 class="text-primary text-center">DASH</h1>
				<ul id="side_menu" class="nav flex-column nav-pills">
					<li class="nav-item">
						<a class="nav-link active" href="dashboard.php"> <i class="fa fa-tachometer" aria-hidden="true"></i>
							&nbsp;Dashboard</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="AddNewPost.php"> <i class="fa fa-list" aria-hidden="true"></i>
							&nbsp;Add New post</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="categories.php"> <i class="fa fa-tags" aria-hidden="true"></i>
							&nbsp;Categories</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="admins.php"> <i class="fa fa-users" aria-hidden="true"></i>
							&nbsp;Manage Admins</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="aboutus.php"> <i class="fa fa-info" aria-hidden="true"></i>
							&nbsp;About Us</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="Comments.php"> <i class="fa fa-comments" aria-hidden="true"></i>
							&nbsp;Comments <?php while($countcom = $count->fetch_assoc()){
								$unapprove = $countcom["upapproveComment"];
							} ?>
							<?php if($unapprove > 0){ ?><span class="badge badge-danger" style="float:right;"><?php echo $unapprove ?></span> <?php } ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#"> <i class="fa fa-rss" aria-hidden="true"></i>
							&nbsp;Live Blog</a>
					</li>
					<li class="nav-item">
                        <a class="nav-link" href="contacts.php">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            &nbsp;Contacts
                        </a>
                    </li>
					<li class="nav-item">
						<a class="nav-link" href="Logout.php"> <i class="fa fa-sign-out" aria-hidden="true"></i>
							&nbsp;Logout</a>
					</li>
				</ul>
			</div>
			<!-- ending of side area-->
			<div class="col-sm-10">
				<?php if($_SESSION["succMessage"]){ ?>
					<div class="alert alert-success alert-dismissible mt-4">
						<?php echo successMessage(); ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					</div>
				<?php } ?>
				<h5 class="m-5 text-center">Admin Dashboard </h5>
				<div class="table-responsive m-3">
					<table class="table table-striped table-hover text-center">
						<thead>
							<tr>
								<th scope="col">SN</th>
								<th scope="col">Title</th>
								<th scope="col">Banner</th>
								<th scope="col">Category</th>
								<th scope="col">Comments</th>
								<th scope="col">Actions</th>
								<th scope="col">Details</th>
							</tr>
						</thead>
						<tbody>
                            <?php
                             while( $row=$result->fetch_assoc() ){ 
                                 $sn++;
                                 $id = $row["id"];
                                 $image= $row["image"]; 
                                 $title = $row["title"];  
								 $category = $row["category"]; 
								 $status = $row["status"]
                            ?>
							<tr>
								<th scope="row">
									<?php echo $sn ?>
								</th>
								<td>
									<?php if(strlen($title)>20 ){
                                         $title = substr($title,0,20). '...'; 
                                         } 
                                         echo $title ?>
                                        </td>
								<td>
									<img src="<?= $image ?>" alt="banner" style="height:60px; width:130px;">
								</td>
								<td>
									<?php echo $category ?>
								</td>
								<td>
									<?php 
										// For Pending Comments
										global $connection;
										$Count=mysqli_query($connection,"SELECT count(*) as total from comments where blog_id='$id' and status = 'Pending'");
										$data=mysqli_fetch_assoc($Count);
										if($data["total"] > 0){
											echo "<span class='badge badge-danger'>".$data['total'] ."</span>";
										}
										
									?>
									<?php 
										// For Approve Comments
										global $connection;
										$CountApprove=mysqli_query($connection,"SELECT count(*) as total from comments where blog_id='$id' and status = 'Approve'");
										$dataApprove=mysqli_fetch_assoc($CountApprove);
										if($dataApprove["total"] > 0){
											echo "<span class='badge badge-success'>".$dataApprove['total'] ."</span>";
										}
									?>
								</td>
								<td>
								<?php if($status) { ?>
											<a href="NotPublishedPost.php?id=<?php echo $id; ?>">
                                            <button type="button" class="btn btn-success">Published</button>
											</a>
                                        <?php } ?>
										<?php if(!$status) { ?>
											<a href="PublishedPost.php?id=<?php echo $id; ?>">
                                            <button type="button" class="btn btn-warning">Not Published</button>
											</a>
                                        <?php } ?>
									<a href="EditPost.php?Edit=<?php echo $id ?>">
										<button type="button" class="btn btn-primary">Edit</button>
									</a>
								</td>
								<td>
								
									
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- ending of side area-->
		</div>
		<!-- ending  of row-->
	</div>
	<!-- ennd containger-->
	<div id="footer" class="mt-5">
		<!-- Footer div -->
		<hr>
		<p>| &copy; | 2020 <a href="https://sambhattarai.com.np" target="_blank">SAMBHATTARAI</a> All Right Reserved</p>
		<hr>
	</div>
	<div style="height:5px; background-color: #283b5f"></div>
</body>

</html>