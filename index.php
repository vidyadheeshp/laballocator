<?php ini_set('display_errors', 1);
 
	if (session_id() == '') {
    session_start();
	$login_id = $_SESSION['s_id'];
	//$dept_id = $_SESSION['dept'];
}

 if(!isset($_SESSION['logged_in'])) {
      header("Location: login.php"); 
 }  
include('pages/required/db_connection.php');
include('pages/required/functions.php');
include('pages/required/tables.php');


	$loggen_in_query = "SELECT 
									um.*
								FROM 
									users um
								WHERE
									1=1
									AND um.id=".$login_id;
			$login_query_result = db_one($loggen_in_query);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Computer Center | Lab Allocator</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
   <!-- Bootstrap Counter -->
  <link rel="stylesheet" href="bootstrap/css/counter.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
	#datepicker1{z-index:1151 !important;}
	.notification_bg_color{background:#C9C5C5}
	#loading_image {
		  position:fixed;
		  top:0px;
		  right:0px;
		  width:100%;
		  height:100%;
		  background-color:#c1bdbb;
		  background-image:url('images/loading_processmaker.gif');
		  background-repeat:no-repeat;
		  background-position:center;
		  z-index:10000000;
		  opacity: 0.4;
	}
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"> <b>CC</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>CC</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
			 <!-- Notifications: style can be found in dropdown.less -->
			<li class="dropdown notifications-menu">
				<?php 
					/*$logged_in_member = $login_id;
					$notification_count_query ="SELECT UDF_NOTIFICATION_COUNT(".$logged_in_member.") AS n_count";
					$notification_count = db_one($notification_count_query);*/
						?>
				<a href="#" class="dropdown-toggle notify_drdw" data-toggle="dropdown">
				  <i class="fa fa-bell-o"></i>
				  <span class="label label-warning"><?php //(print_r($notification_count['n_count']));?></span>
				</a>
				<ul class="dropdown-menu">
				  <li class="header">You have <?php //print_r($notification_count['n_count'] == 0 ? 0 : $notification_count['n_count']);?> notifications</li>
				  <li>
					<!-- inner menu: contains the actual data -->
					<ul class="menu">
					<?php 
						/*$i=1;
						$nr_str = '';
						$notification_query ="SELECT 
												nm.*  
												,mnt.notification_code
											FROM 
												notification_master nm
												INNER JOIN meta_notification_type mnt ON mnt.sno=nm.notify_type
											WHERE 
												1=1 AND nm.added_date = CURDATE() 
												AND nm.notify_to = ".$_SESSION['s_id']." OR nm.notify_to = 0
											ORDER BY 
												nm.added_date DESC";
					$notification_result = db_all($notification_query);
					if(!(empty($notification_result))){
						foreach($notification_result AS $nr){
							if($nr[3] == $_SESSION['s_id']){
								$nr_str .='<li>
									<input type="hidden" class="notification_id" value="'.$nr[0].'">
									<a  title="'.$nr[2].'" class="'.($nr[6]== 0 ? 'notification_bg_color' : '').' equipment_adding_notification">'
									  .$nr[8].$nr[2].'
									</a>
									
								  </li>';
								}else{
									$nr_str .='<li>
									<input type="hidden" class="notification_id" value="'.$nr[0].'">
									<a  title="'.$nr[2].'" class="'.($nr[6]== 0 ? 'notification_bg_color' : '').' equipment_adding_notification">'
									  .$nr[8].$nr[2].'
									</a>
									
								  </li>';
								}
							
						  $i++;
						}
					}else{
						$nr_str .='<li align="center">No Notifications</li>';
					}
						echo $nr_str;*/
					?>
					</ul>
				  </li>
				  <li class="footer"><a href="view_all_notifications.php">View all</a></li>
				</ul>
			</li>
						
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="images/GIT-logo.jpg<?php //echo ($login_query_result['pro_image'] == NULL ? 'boxed-bg.jpg' : $login_query_result['pro_image']);?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php if(isset($_SESSION['name'])) {
					  echo  $_SESSION['name'];
					}?>
			   </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="images/GIT-logo.jpg<?php //echo ($login_query_result['pro_image'] == NULL ? 'boxed-bg.jpg' : $login_query_result['pro_image']);?>" class="img-circle" alt="User Image">

                <p>
                  <?php /*if(isset($_SESSION['name'])) {
						   echo  $_SESSION['name'];*/
						//}?>
                  <small>Member since <?php //echo $login_query_result ['member_since'];?></small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
			 
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
 <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
		  <!-- Sidebar user panel -->
		  <div class="user-panel">
			<div class="pull-left image">
			  <img src="images/GIT-logo.jpg<?php //echo ($login_query_result['pro_image'] == NULL ? 'boxed-bg.jpg' : $login_query_result['pro_image']);?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
			  <p><?php if(isset($_SESSION['name'])) {echo  $_SESSION['name'];}?></p>
			 <?php if($login_query_result ['uid'] == 1){?>
				  <a href="#"><i class="fa fa-circle text-success"></i>CC Admin</a>
				<?php }else if($login_query_result ['uid'] == 2){?>
				  <a href="#"><i class="fa fa-circle text-aqua"></i>HoD</a>
				<?php }else if($login_query_result ['uid'] == 3){?>
					<a href="#"><i class="fa fa-circle text-aqua"></i>Lab Instructor</a>
				<?php }?>
			</div>
		  </div>
		  <span style="height:50px;" id="clock" class="form-control" value=""></span>
		  <!-- search form >
		  <form action="#" method="get" class="sidebar-form">
			<div class="input-group">
			  <input type="text" name="q" class="form-control" placeholder="Search...">
				  <span class="input-group-btn">
					<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				  </span>
			</div>
		  </form>
		<!-- /.sidebar -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<?php //principal Section
				//if($login_query_result['USERTYPE'] == 1){
			?>
			<li class="active treeview">
			  <a href="index.php">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<li>
			  <a href="lab_slot.php">
				<i class="fa fa-bar-chart"></i> <span>Lab Allocation Chart</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<?php if($login_query_result['uid'] ==3){?>
			<li>
			  <a href="my_lab_slots.php">
				<i class="fa fa-cubes"></i> <span>My Lab Slots</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<?php }
			if($login_query_result['uid'] == 2){?>
				<li>
			  <a href="dept_lab_slots.php">
				<i class="fa fa-cubes"></i> <span>Dept Lab Slots</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<?php } ?>	
		</ul>
	</section>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<!--use the below section for adding content header-->
    <section class="content-header">
      <h1>
        Dashboard
        <!--small>Control panel</small-->
      </h1>
      <ol class="breadcrumb">
        <li class="active"> Home</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
					<!-- Left col -->
					<section class="col-lg-12 connectedSortable">
						
				  <?php 
				  	if($login_query_result ['uid'] == 1){
				  ?>
				  <div class="row">
						 	<div class="col-md-6">
			          <div class="box box-solid">
			            <div class="box-header with-border">
			              <h3 class="box-title">Lab Allocations Initiation</h3>
			            </div>
			            <div class="box-body">
			            			
			            			<table class="table table-bordered table-responsive">
			            				<caption>Previous Academic Year List</caption>
															<thead>
																	<tr>
																		<th>Academic Year</th>
																		<th>Start Time</th>
																		<th>End Time</th>
																	</tr>
															</thead>
															<tbody>
															<?php 
																		$aca_year_list = "SELECT *,
																												DATE_FORMAT(from_date,'%d %M %Y') AS frm_date,
						           																	DATE_FORMAT(to_date,'%d %M %Y') AS t_date
						           															  FROM academic_year 
						           															  WHERE 
						           															  	1=1";
						            				$aca_list = db_all($aca_year_list);
						            				$aca_str = '';
						            				if(empty($aca_list)){
						            					$aca_str .= "<tr>
																							<td colspan='3'>No recent Academic Year (Closed)</td>
																						</tr>";
																					}else{
																								foreach($aca_list AS $aca){
						            				$aca_str .= "<tr>
																							<td>".$aca['aca_year']."</td>
																							<td>".$aca['frm_date']."</td>
																							<td>".$aca['t_date']."</td>
																						</tr>";
						            			}
																					}
						            		
						            			echo $aca_str;
						            			?>
															</tbody>
																
														</table>
												

												<!-- The logic for setting the academic calender button-->
			           					<?php 
			           							$query_to_check_requisitions_open = "SELECT *,
			           																												DATE_FORMAT(from_date,'%d %M %Y') AS frm_date,
			           																												DATE_FORMAT(to_date,'%d %M %Y') AS t_date
			           																									  FROM 
			           																									  	academic_year 
			           																									  WHERE 
			           																									  1=1
			           																									  ORDER BY id DESC 
			           																									  LIMIT 1";
			           								$result_from_query = db_one($query_to_check_requisitions_open);
			           								
			           								
			           								if(empty($result_from_query)){
			           										echo '<h5>No Academic years Added</h5>';
			           										?>
			           										<button class="btn btn-success  set_date_button" ><i class="fa fa-tasks"></i> Set Academic year</button>
			           								<?php 
			           							}else{
			           									$current_date_time = strtotime(date('y-m-d h:m:s'));
			           								//echo $current_date_time.'<br/>';
			           								$start_date_to_number = strtotime($result_from_query['from_date']);
			           								//echo $start_date_to_number.'<br/>';
			           								$TO_date_to_number = strtotime($result_from_query['to_date']);
			           								//echo $TO_date_to_number.'<br/>'
			           						?>
			           							
			           						<div class="col-md-6">
			           							<h5><?php 
			           							if($start_date_to_number < $current_date_time && $TO_date_to_number < $current_date_time){

			           									echo 	"The Previous Academic Year (Sem Duration) is <br/><strong>".$result_from_query['frm_date']." : ".$result_from_query['t_date']."</strong>"; ?></h5>
			           							<button class="btn btn-success  set_date_button" ><i class="fa fa-tasks"></i> Set Academic year</button>
			            						
			            					<?php }else if($start_date_to_number <= $current_date_time && $TO_date_to_number >= $current_date_time){
			            						?>
			            								<h5><?php echo 	"The On-going Academic Year (Sem Duration) is <br/><strong>".$result_from_query['frm_date']." : ".$result_from_query['t_date']."</strong>"; ?></h5>
			           							<h4>The Current Academic Calender is already Set.</h4>
			            					<?php }else{?>
			            							<button class="btn btn-success  set_date_button" ><i class="fa fa-tasks"></i> Set Academic year</button>
			            						<?php }?>
			            					</div>
			            					
			            					
			            				<?php }?>
			            				<div class="col-md-12 calender_div hidden">
			            						
			            							 <label class="help-block">Academic Year Dates</label>
									              <div class="form-group">

										                <div class="input-group">
										                  <div class="input-group-addon">
										                    <i class="fa fa-clock-o"></i>
										                  </div>
										                  <input type="text" class="form-control pull-right aca_range" id="reservation">
										                </div>
										                <!-- /.input group -->
										              </div>
										              <!-- /.form group -->
									              	<button class="btn btn-warning	hidden set_button"  style="align: center"><i class="fa fa-check"></i> Set</button>	
			            					
			            					</div>

			            </div>
			          </div>
		        </div>

		        <div class="col-md-6">
			          <div class="box box-solid">
			            <div class="box-header with-border">
			              <h3 class="box-title">Lab Allocations Tasks</h3>
			            </div>
			            <div class="box-body">
			            		<div id="loading_image" style="display:none;"></div>
			            		<!-- Software Requirement Addition -->
			            		<div class="col-md-4">
														<div class="small-box bg-primary">
															<div class="inner">
																<p>Software Requirement</p>
															</div>
																<div class="icon">
																  <i class="fa fa-pencil"></i>
																</div>
																<button type="button" class="small-box-footer form-control" id="add_res" data-toggle="modal" data-target="#add_req_modal">Add <i class="fa fa-plus"></i></button>
																<div class="modal fade" id="add_req_modal" role="dialog">
																		<div class="modal-dialog modal-md">
																			<div class="modal-content">
																			  <div class="modal-header bg-primary">
																						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						  <span aria-hidden="true">&times;</span></button>
																						<h4 class="modal-title"> <i class="fa fa-plus"></i> Add Software Requirement</h4>
																				</div>
																					  
																				<span class="help-block">
																					<div class="software_req_added_notification">
																						<div id="loading_image" style="display:none;"></div>
																							<form method="post" id="" role="form">
																									<div class="modal-body">
																											
																											<div class="form-group col-md-8">
																												<label class="help-block">Software Requirement : <span class="text-danger">*</span></label>
																												<input type="text" id="soft_req" required name="soft_req" class="form-control" placeholder="Enter the Software requirement"/>
																											</div>
																											<div class="clearfix"></div>
																									</div>
																										
																									<div class="modal-footer">
																										<button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Close</button>
																										<button type="reset" class="btn btn-default btn-flat"></i> Reset</button>
																										<button type="button" class="btn btn-primary btn-flat" id="add_soft_req"><i class="fa fa-plus"></i> Add</button>
																									</div>
																							</form>
																							</div>
																				</span><!--end of help block-->
																		</div>
																		<!-- /.modal-content -->
																	  </div>
																	  <!-- /.modal-dialog -->
																	</div>
																	<!-- /.modal -->

																<!-- End of tile for (Software Requirement Addition)-->
													</div>
											</div>
												<div class="clearfix"></div>
											<div class="col-md-12 allocation_status">
			            				<?php if(empty($result_from_query)){ 
			            						echo '<h6 class="text-danger">Cannot Allocate</h6>';
			            				}else{?>
			            					<button class="btn btn-success btn-lg allocate_slots" <?php echo  (($result_from_query['status']==0 && $start_date_to_number < $current_date_time  &&  $TO_date_to_number < $current_date_time )? 'disabled' :'');?>><i class="fa fa-check"></i> Allocate</button>
			            				<?php } ?>	
			            		</div>
			            </div>

			           </div>
			      </div>

			      <div class="clearfix"></div>
			       <div class="col-md-12">
			          <div class="box box-solid">
			            <div class="box-header with-border">
			              <h3 class="box-title">Requisition List</h3>
			            </div>
			            <div class="box-body">
			            	<div class="col-md-4 form-group">
			            	
			            		<select class="form-control dept_id">
			            				<option value="0">Choose a Department</option>
					            	<?php
					            	$Department_list_query = "SELECT *  FROM users WHERE uid=2";
					            	$department_list = db_all($Department_list_query); 
					            		$dept_str = "";
					            	foreach ($department_list as $dept_row) {
					            		$dept_str .='<option value='.$dept_row['id'].'>'.$dept_row['name'].'</option>';
					            	}
					            	echo $dept_str;
					            	?>
					            	</select>
			            	</div>
			            	<div class="col-md-4" id="requisitin_count_div">
			            		
			            	</div>
			            	<div class="clearfix"></div>
			            	<div class="col-md-12"  id="requisition_content">
			            			<table class="table table-bordered table-responsive">
				            			<thead>
				            				<tr>
				            					<th>Sl.No</th>
				            					<th>Course Name</th>
				            					<th>Course Code</th>
				            					<th>No of Divisions</th>
				            					<th>Total Strength</th>
				            					<th>Academic Year</th>
				            					<th>Software Requirement</th>
				            					<th>Actions</th>
				            				</tr>
				            			</thead>
				            			<tbody>
				            			<?php 
				            				$requisition_list_query = "SELECT * FROM courses WHERE 1=1";
				            				$requisition_list = db_all($requisition_list_query);
				            				$list_string = '';
				            				$i=1;
				            				//print_r($requisition_list);
				            				foreach($requisition_list AS $list_row){
				            							$list_string .="<tr>
															            					<td>".$i."</td>
															            					<td>".$list_row['coursename']."</td>
															            					<td>".$list_row['coursecode']."</td>
															            					<td>".$list_row['divisions']."</td>
															            					<td>".$list_row['strength']."</td>
															            					<td>".$list_row['academicyear']."</td>
															            					<td>".$list_row['softwarereq']."</td>
															            					<td>
															            						<button class='btn btn-warning' title='Edit'><i class='fa fa-pencil'></i></button>
															            						<button class='btn btn-danger' title='Delete'><i class='fa fa-times'></i></button>
															            					</td>
															            				</tr>";
															            		$i++;
				            				}
				            				echo $list_string;
				            			?>
				            				
				            			</tbody>
				            		</table>
			            	</div>
			            		
			            </div>
			          </div>
			        </div>    


		      </div> 
					<?php
					//*************************content of Login for Dept users (HoD)**************************************//
				}elseif($login_query_result ['uid'] == 2){?>
					<div class="col-lg-3 col-xs-6 col-md-4">
				  <!-- small box -->
						<div class="small-box bg-aqua">
							<div class="inner">
							 <?php 
							 $query_to_check_requisitions_open = "SELECT *,
			           																												DATE_FORMAT(from_date,'%d %M %Y') AS frm_date,
			           																												DATE_FORMAT(to_date,'%d %M %Y') AS t_date
			           																									  FROM 
			           																									  	academic_year 
			           																									  WHERE 
			           																									  1=1
			           																									  ORDER BY id DESC 
			           																									  LIMIT 1";
			           								$result_from_query = db_one($query_to_check_requisitions_open);
			           								
			           								$exploded_year = explode('-',$result_from_query['aca_year']);
			           								$check_year_boolean = in_array(date('Y'),$exploded_year);
							?>
							  <h3><?php //echo($Resolutions_count['res_count']); ?></h3>

							  <p>Requisition Form</p>
							</div>
							<div class="icon">
							  <i class="fa fa-pencil"></i>
							</div>
							<button type="button" class="small-box-footer form-control" id="add_res" <?php echo  (($result_from_query['status']==0 && $start_date_to_number < $current_date_time  &&  $TO_date_to_number < $current_date_time )? 'disabled' :'');?> data-toggle="modal" data-target="#add_res_modal">Add <i class="fa fa-plus"></i></button>
								<div class="modal fade" id="add_res_modal" role="dialog">
									  <div class="modal-dialog modal-lg">
										<div class="modal-content">
										  <div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													  <span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title"> <i class="fa fa-plus"></i> Requisition Form</h4>
											</div>
												  
											<span class="help-block">
												<div class="requisition_added_notification">
													<div id="loading_image" style="display:none;"></div>
														<form method="post" id="add_requisition" role="form">
																<div class="modal-body">
																		<div class="form-group col-md-4">
																			<label class="help-block">Course Code : <span class="text-danger">*</span></label>
																			<input type="text" id="course_code" required name="course_code" class="form-control" placeholder="Enter the course code"/>
																		</div>
																		<div class="form-group col-md-8">
																			<label class="help-block">Course Name : <span class="text-danger">*</span></label>
																			<input type="text" id="course_name" required name="course_name" class="form-control" placeholder="Enter the course name"/>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="help-block">Department : <span class="text-danger">*</span></label>
																			<input type="text" disabled id="dept" class="form-control" value="<?php echo $login_query_result['name'];?>">
																					<input type="hidden" id="dept_id" required name="dept" class="form-control" value="<?php echo $login_query_result['id'];?>">
																		</div>
																		<div class="form-group col-md-4">
																			<label class="help-block">Semester : <span class="text-danger">*</span></label>
																			<select id="sem" required name="sem" class="form-control">
																				<option val="0">Choose One</option>
																				<option val="3">3</option>
																				<option val="4">4</option>
																				<option val="5">5</option>
																				<option val="6">6</option>
																				<option val="7">7</option>
																				<option val="8">8</option>
																				<option val="9">9</option>
																				<option val="10">10</option>
																			</select>
																		</div>
																		
																		<div class="form-group col-md-4">
																			<label class="help-block">Number of Divisions<span class="text-danger">*</span></label>
																			<input type="text" class="form-control" required id="no_div" name="no_div" placeholder="Enter the number of divisions"/>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="help-block">Total Number of Students<span class="text-danger">*</span></label>
																			<input type="text" class="form-control" required id="no_students" name="no_students" placeholder="Enter the number of students"/>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="help-block">Duration<span class="text-danger">*</span></label>
																			<input type="text" class="form-control" required id="duration" name="duration" placeholder="Enter the duration of lab"/>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="help-block">Academic Year : </label>
																			<?php $academic_year_query = "SELECT * FROM academic_year WHERE 1=1 AND status = 1 ORDER BY id DESC LIMIT 1";
																				$aca_year_result = db_one($academic_year_query);
																			 ?>
																			 <span class="help-block"><?php echo 'Current Academic Year is :'.$aca_year_result['aca_year'];?></span>
																			<!--input type="text" class="form-control" required id="academic_year" name="academic_year" disabled/-->
																			<select class="form-control" name="academic_year">
																				<option value="0"> Choose One</option>
																				<?php 
																					$academic_year_string = "";
																					$academic_year_string .="<option value='".$aca_year_result['id']."' selected>".$aca_year_result['aca_year']."</option>";
																					echo $academic_year_string;
																				?>
																			</select>
																		</div>
																		<div class="clearfix"></div>
																		<div class="form-group col-md-4">
																			<label class="help-block">Software Requirement :<span class="text-danger">*</span></label>
																			<select class="form-control" name="req">
																				<option value="0">Choose One</option>
																			<?php $soft_req_query = "SELECT * FROM software_requirements WHERE 1=1 AND status=1";
																			$soft_list = db_all($soft_req_query);
																			$soft_string = '';
																			foreach($soft_list AS $soft){
																				$soft_string .='<option value="'.$soft["id"].'">'.$soft["software_req"].'</option>';
																			}
																			echo $soft_string;
																			?>
																			</select>
																			<!--textarea class="form-control" required id="req" name="req" rows="2" cols="2"></textarea-->
																			
																		</div>
																		
																		<div class="clearfix"></div>
																</div>
																	
																<div class="modal-footer">
																	<button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Close</button>
																	<button type="reset" class="btn btn-default btn-flat"></i> Reset</button>
																	<button type="submit" class="btn btn-primary btn-flat" id="add_requisition"><i class="fa fa-check"></i> Submit</button>
																</div>
														</form>
														</div>
											</span><!--end of help block-->
									</div>
									<!-- /.modal-content -->
								  </div>
								  <!-- /.modal-dialog -->
								</div>
								<!-- /.modal -->
						</div>
					</div> <!-- End of div and modal-->
						<!-- The content to display requisition given by the concerned department-- ************ PENDING ******-->
						<div class="col-md-12">
		          <div class="box box-solid">
		            <div class="box-header with-border">
		              <h3 class="box-title">Requisition List</h3>
		            </div>
		            <div class="box-body">
		           
		            		<table class="table table-bordered table-responsive">
		            			<thead>
		            				<tr>
		            					<th>Sl.No</th>
		            					<th>Course Name</th>
		            					<th>Course Code</th>
		            					<th>No of Divisions</th>
		            					<th>Total Strength</th>
		            					<th>Academic Year</th>
		            					<th>Software Requirement</th>
		            					<th>Actions</th>
		            				</tr>
		            			</thead>
		            			<tbody>
		            			<?php 
		            				$requisition_list_query = "SELECT * FROM courses WHERE 1=1 AND deptid=".$login_query_result ['id'];
		            				$requisition_list = db_all($requisition_list_query);
		            				$list_string = '';
		            				$i=1;
		            				//print_r($requisition_list);
		            				foreach($requisition_list AS $list_row){
		            							$list_string .="<tr>
													            					<td>".$i."</td>
													            					<td>".$list_row['coursename']."</td>
													            					<td>".$list_row['coursecode']."</td>
													            					<td>".$list_row['divisions']."</td>
													            					<td>".$list_row['strength']."</td>
													            					<td>".$list_row['academicyear']."</td>
													            					<td>".$list_row['softwarereq']."</td>
													            					<td>";
													            					if($list_row['allocation_status'] == 0){
													            							$list_string .="<button class='btn btn-warning' data-toggle='modal' data-target='#edit_req_data".$i."' title='Edit'><i class='fa fa-pencil'></i></button>";
													            					}else{
													            						$list_string .="<h5 class='text-success'>Allocation Done</h5>";
													            					}
													            						
													            						$list_string .="<div class='modal fade' id='edit_req_data".$i."' role='dialog'>
																										  <div class='modal-dialog modal-lg'>
																											<div class='modal-content'>
																											  <div class='modal-header bg-primary'>
																														<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
																														  <span aria-hidden='true'>&times;</span></button>
																														<h4 class='modal-title'> <i class='fa fa-plus'></i> Requisition Form</h4>
																												</div>
																													  
																												<span class='help-block'>
																													<div class='requisition_updated_notification'>
																														<div id='loading_image' style='display:none;''></div>
																															<form method='post' id='edit_requisition' role='form'>
																																	<div class='modal-body'>
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Course Code : <span class='text-danger'>*</span></label>
																																				<input type='text' id='course_code' required name='course_code' class='form-control' placeholder='Enter the course code' value='".$list_row['coursecode']."'/>
																																			</div>
																																			<div class='form-group col-md-8'>
																																				<label class='help-block'>Course Name : <span class='text-danger'>*</span></label>
																																				<input type='text' id='course_name' required name='course_name' class='form-control' placeholder='Enter the course name' value='".$list_row['coursename']."'/>
																																			</div>
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Department : <span class='text-danger'>*</span></label>
																																				<input type='text' disabled id='dept' class='form-control' value='". $login_query_result['name']."'>
																																						<input type='hidden' id='dept_id' required name='dept' class='form-control' value='".$login_query_result['id']."'>
																																			</div>
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Semester : <span class='text-danger'>*</span></label>
																																				<select id='sem' required name='sem' class='form-control'>
																																					<option val='0'>Choose One</option>
																																					<option value='3' ".($list_row['sem'] == 3? 'selected':'').">3</option>
																																					<option value='4' ".($list_row['sem'] == 4? 'selected':'').">4</option>
																																					<option value='5' ".($list_row['sem'] == 5? 'selected':'').">5</option>
																																					<option value='6' ".($list_row['sem'] == 6? 'selected':'').">6</option>
																																					<option value='7' ".($list_row['sem'] == 7? 'selected':'').">7</option>
																																					<option val='8' ".($list_row['sem'] == 8? 'selected':'').">8</option>
																																					<option value='9' ".($list_row['sem'] == 9? 'selected':'').">9</option>
																																					<option value='10' ".($list_row['sem'] == 10? 'selected':'').">10</option>
																																				</select>
																																			</div>
																																			
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Number of Divisions<span class=''></span></label>
																																				<input type='text' class='form-control' required id='no_div' name='no_div' placeholder='Enter the number of divisions' value='".$list_row['divisions']."'/>
																																			</div>
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Total Number of Students<span class=''></span></label>
																																				<input type='text' class='form-control' required id='no_students' name='no_students' placeholder='Enter the number of students' value='".$list_row['strength']."'/>
																																			</div>
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Duration<span class='text-danger'>*</span></label>
																																				<input type='text' class='form-control' required id='duration' name='duration' value='".$list_row['duration']."'/>
																																			</div>
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Academic Year : </label>
																																				
																																				 <span class='help-block'><?php echo 'Current Academic Year </span>
																																				<input type='text' class='form-control' required id='aca_year' name='academic_year' value='".$list_row['academicyear']."' disabled/>
																																			</div>
																																		
																																			<div class='form-group col-md-4'>
																																				<label class='help-block'>Software Requirement :<span class='text-danger'>*</span></label>
																																				<select class='form-control' name='req'>
																																					<option value='0' >Choose One</option>";
																																					$soft_req_query = "SELECT * FROM software_requirements WHERE status=1";
																																					$soft_req_result = db_all($soft_req_query);
																																					foreach ($soft_req_result as $soft_val) {
																																						$list_string .="<option value='".$soft_val['id']."' ".($soft_val['id'] ==  $list_row['softwarereq'] ? 'selected':'').">".$soft_val['software_req']."</option>";
																																					}
																																					
																																					
																																				$list_string .="</select>
																																				<!--textarea class='form-control' required id='req' name='req' rows='2' cols='2'></textarea-->
																																				
																																			</div>
																																			
																																			<div class='clearfix'></div>
																																	</div>
																																		
																																	<div class='modal-footer'>
																																		<button type='button' class='btn btn-default pull-left btn-flat' data-dismiss='modal'>Close</button>
																																		<button type='reset' class='btn btn-default btn-flat'></i> Reset</button>
																																		<input type='hidden'  name='cid' value='".$list_row['cid']."'>
																																		<button type='submit' class='btn btn-primary btn-flat' id='edit_requisition'><i class='fa fa-check'></i> Submit</button>
																																	</div>
																															</form>
																															</div>
																												</span><!--end of help block-->
																										</div>
																										<!-- /.modal-content -->
																									  </div>
																									  <!-- /.modal-dialog -->
																									</div>
																									<!-- /. End of modal -->

													            					</td>
													            				</tr>";
													            		$i++;
		            				}
		            				echo $list_string;
		            			?>
		            				
		            			</tbody>
		            		</table>
		            </div>
		          </div>
	        </div>

					
					<?php //*************************content of Login for Lab users **************************************//
				}else{?>
					<div class="col-md-12">
		          <div class="box box-solid">
		            <div class="box-header with-border">
		              <h3 class="box-title">Lab Utilization Statistics</h3>
		            </div>
		            <div class="box-body">
		           	</div>
		          </div>
		      </div>	
		<?php }?>
			  <!-- /.row (main row) -->
				</section>

			</div>
	<?php //mysqli_close($con);?>
</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>KLS GIT</b> 
    </div>
    <strong>Copyright &copy; 2022 <a href="#"></a>.</strong> All rights
    reserved. GIT Software Team
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="plugins/table2excel/dist/jquery.table2excel.min.js"></script>
<!-- jQuery Knob -->
<script src="plugins/knob/jquery.knob.js"></script>
<script>
  $(function () {
    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
	
});
    /* END JQUERY KNOB */
</script>
<script>


$(document).ready(function(){	
	//for clock
  setInterval('updateClock()', 1000);
 
 //Date range picker
    $('#reservation').daterangepicker();
	
	$(document).on('click','.set_date_button',function(e){
		e.preventDefault();
		$('.calender_div').toggleClass('hidden');
		$('.set_button').toggleClass('hidden');

	});
	
	
	//form processing for storing resolution data.
	$(document).on('submit','#add_requisition',function(e){
		e.preventDefault();
		///var aca_year = $('#academic_year').val();
		//alert(aca_year);

		// Get form
        var form = $('#add_requisition')[0];
 
       // Create an FormData object 
        //var data = new FormData(form);
		//alert(data);
			$.ajax({
				url: 'ajax/save_requisition_data.ajax.php', 
				type: 'POST',
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function(data) {
					$('.requisition_added_notification').html(data);
					/*setTimeout(function () {
							window.location.reload();
						}, 2000);*/
				}
			});
		/*$.post(
				url,{
					r1 : res_title, r2 :  cat_id, r3 : res_status_id, r4 : res_date, r5 : dept, r6 : res_no,  r7 : sanctioning_auth, r8 : res_doc, r9 : res_image
				},
				function(data,status){
						$('.resolution_added_notification').html(data);
						/*setTimeout(function () {
							window.location.reload();
						}, 3000);*/
		//}
				
	});

//for setting the date range for the academic year to start with requisition form.
$(document).on('click','.set_button',function(e){
		e.preventDefault();
		//alert("clicked");
		var aca_range = $('.aca_range').val();
		//var start_date = $('.start_date').val();
		alert(aca_range);
		var aca_year_date_set_url = 'ajax/aca_year_date_set.ajax.php';
		
		$("div #loading_image").removeAttr("style");
		$.post(
				aca_year_date_set_url,{
					p1 : aca_range
				},
				function(data,status){
						$('.calender_div').html(data);
						setTimeout(function () {
							window.location.reload();
						}, 3000);
					});

	});

///Incomplete
$(document).on('click','#close_requisition',function(e){
		e.preventDefault();
		//alert('clicked');
		var close_url = 'ajax/close_requisition_form.ajax.php';
		$.post(
				close_url,{
					p1 : 'Nil'
				},
				function(data,status){
						$('.requisition_close_notification').html(data);
					setTimeout(function () {
							window.location.reload();
						}, 3000);
					});

	});

//action on lab allocation button click
$(document).on('click','.allocate_slots',function(e){
		e.preventDefault();
		//alert('clicked');
		var close_url = 'ajax/laballocation.php';
		$("div #loading_image").removeAttr("style");
		$.post(
				close_url,{
					p1 : 'Nil'
				},
				function(data,status){
						$('.allocation_status').html(data);
					/*setTimeout(function () {
							window.location.reload();
						}, 3000);*/
					});

	});

//for fetching requisition data in addmin login
$(document).on('change','.dept_id',function(e){
		e.preventDefault();
		//alert('changed');
		var dept_id = $(this).val();
		var count_url = 'ajax/requisition_count.ajax.php';
		//$("div #loading_image").removeAttr("style");
		$.post(
				count_url,{
					p1 : dept_id
				},
				function(data,status){
						//alert(data+'-'+status);
						$('#requisitin_count_div').html(data);
						//alert($('.requisitin_count_div').html());
					/*setTimeout(function () {
							window.location.reload();
						}, 3000);*/
					});
		var requisition_url = "ajax/requisition_data.ajax.php";
		$.post(
				requisition_url,{
					p1 : dept_id
				},
				function(data,status){
						//alert(data+'-'+status);
						$('#requisition_content').html(data);
						//alert($('.requisitin_count_div').html());
					/*setTimeout(function () {
							window.location.reload();
						}, 3000);*/
					});

	});

//for adding new Software requirement
$(document).on('click','#add_soft_req',function(e){
		e.preventDefault();
		//alert("clicked");
		var soft_req = $('#soft_req').val();
		//alert(soft_req);
		var soft_req_url = "ajax/add_software_req.ajax.php";
		//$("div #loading_image").removeAttr("style");
		$.post(
				soft_req_url,{
					p1 : soft_req
				},
				function(data,status){
						//alert(data+'-'+status);
						$('.software_req_added_notification').html(data);
					
					setTimeout(function () {
							window.location.reload();
						}, 3000);
					});
	});



//form editing the  for requisiont data. (Already entered)
	$(document).on('submit','#edit_requisition',function(e){
		e.preventDefault();
		///var aca_year = $('#academic_year').val();
		//alert(aca_year);

		// Get form
      //  var form = $('#add_requisition')[0];
 
       // Create an FormData object 
        //var data = new FormData(form);
		//alert(data);
			$.ajax({
				url: 'ajax/update_requisition_data.ajax.php', 
				type: 'POST',
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function(data) {
					$('.requisition_updated_notification').html(data);
					/*setTimeout(function () {
							window.location.reload();
						}, 2000);*/
				}
			});
		/*$.post(
				url,{
					r1 : res_title, r2 :  cat_id, r3 : res_status_id, r4 : res_date, r5 : dept, r6 : res_no,  r7 : sanctioning_auth, r8 : res_doc, r9 : res_image
				},
				function(data,status){
						$('.resolution_added_notification').html(data);
						/*setTimeout(function () {
							window.location.reload();
						}, 3000);*/
		//}
				
	});

});
</script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script>
$(function() {
//Date picker
    $('#datepicker1').datepicker({
    	autoclose: true,
    	 setDate : new Date()
    });
    $('#datepicker2').datepicker({
    	autoclose: true,
    	 setDate : new Date()
    });
    $('#datepicker').datepicker({
    	autoclose: true,
    	defaultDate : new Date()
    });

	//Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
});


//for the display of clock

</script>
<!-- jQuery 2.2.3 -->

<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/fullcalendar/fullcalendar.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script><!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- date time clock -->
<script src="plugins/date_time/date_time.js"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--script src="dist/js/pages/dashboard2.js"></script-->


<!--Bar chart data ends-->	
	
<!-- ChartJS 1.0.1 -->
<script src="plugins/chartjs/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="bootstrap/js/counter.js"></script>
</body>
</html>
