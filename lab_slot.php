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
  <title>Computer Center | Allocation Chart</title>
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
			 <?php if($login_query_result ['id'] == 1) {?>
				  <a href="#"><i class="fa fa-circle text-success"></i> Admin</a>
				<?php }else{?>
				  <a href="#"><i class="fa fa-circle text-aqua"></i> User</a>
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
		< /.sidebar -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<?php //principal Section
				//if($login_query_result['USERTYPE'] == 1){
			?>
			<li class="treeview">
			  <a href="index.php">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<li class="active">
			  <a href="lab_slot.php">
				<i class="fa fa-bar-chart"></i> <span>Lab Allocation Chart</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<?php if($login_query_result['uid'] == 3){?>
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
				<li class="active">
			  <a href="dept_lab_slots.php">
				<i class="fa fa-cubes"></i> <span>Dept Lab Slots</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-right pull-right"></i>
				</span>
			  </a>
			</li>
			<?php }?>
		</ul>
	</section>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<!--use the below section for adding content header-->
    <section class="content-header">
      <h1>
        Lab Allocation Chart
        <!--small>Control panel</small-->
      </h1>
      <ol class="breadcrumb">
        <li class=""> Home</li>
        <li class="active"> Allocation Chart</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	    <div class="row">
				<!-- Left col -->
				<section class="col-lg-12 connectedSortable">
					
				<!-- ************************************The Page Content has to be Added Here **********************************************-->

				<div class="row">
	         
	      <div class="col-md-12">
	      		<div class="box box-solid col-md-12">
            <div class="box-header with-border">
              <h3 class="box-title">Indicators</h3>
            </div>
            <div class="box-body">
						 <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="">
                  <li><a style="color: #F3940B;" href="#"><i class="fa fa-square"></i> Aero</a>  </li>
                  <li><a style="color: #F7E505;" href="#"><i class="fa fa-square"></i> Arch</a> </li>
                  <li><a style="color: #AAF705;" href="#"><i class="fa fa-square"></i> Civil</a> </li>
                  <li><a style="color: #02C4F9;" href="#"><i class="fa fa-square"></i> CSE</a>  </li>
                  <li><a style="color: #B105F7;" href="#"><i class="fa fa-square"></i> EEE</a> </li>
                  <li><a style="color: #4F0A52;" href="#"><i class="fa fa-square"></i> ECE</a> </li>
                  <li><a style="color: #0A5250;" href="#"><i class="fa fa-square"></i> First Year</a> </li>
                  <li><a style="color: #DE104B;" href="#"><i class="fa fa-square"></i> ISE</a> </li>
                  <li><a style="color: #7E956C;" href="#"><i class="fa fa-square"></i> MBA</a> </li>
                  <li><a style="color: #58B9FC;" href="#"><i class="fa fa-square"></i> MCA</a> </li>
                  <li><a style="color: #030FFE;" href="#"><i class="fa fa-square"></i> MECH</a> </li>
                  <li><a style="color: #FEFE02;" href="#"><i class="fa fa-square"></i> Others</a> </li>
                </ul>
              </div>
               </div>
            </div>
	        <!-- /.col -->
	        <div class="clearfix"></div>
	          <div class="box box-solid">
	            <div class="box-header with-border">
	              <h3 class="box-title">Filter</h3>
	            </div>
	            <div class="box-body">
	            		<div class="col-md-4">
	            			<select class="form-control" id="lab_list">
	            				<option value="0">choose one</option>
	            				<?php 
	            				$lab_list_str = '';
	            					$lab_query = "SELECT * FROM labs WHERE 1=1";
	            					$lab_list = db_all($lab_query);
	            					foreach($lab_list AS $labname){
	            							$lab_list_str .="<option value='".$labname['labid']."'>".$labname['labname']."</option>"; 

	            					}
	            					echo $lab_list_str;
	            				?>
	            				
	            			</select>
	            		</div>
	            		<div class="col-md-4">
	            			<button class="btn btn-primary lab_slot_view"><i class="fa fa-search"></i> View</button>
	            		</div>
	            		<div class="clearfix"></div>

	             
	            </div>
	          </div>
	        </div>
	        <div class="clearfix"></div>
	        <!-- /.col -->
	        <div class="col-md-12 " id="calender_div">
	          <div class="box box-primary" >
               <div class="box-body no-padding">
                 <!-- THE CALENDAR -->
                 <div id="calendar"></div>
               </div>
               <!-- /.box-body -->
             </div>
             <!-- /. box -->
	          
	        </div>
	        <!-- /.col -->

	        	<!-- /.modal -->
				<!--Modal for viewing the slot allocated-->
				<div class="modal fade" id="view_slot_alloted" role="dialog">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header bg-primary">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title"><i class="fa fa-book"></i> 
									Slot Alloted Data
								
								</h4>
							</div>
							<div class="modal-body">
								<div class="slot_slloted_data">
									<div id="loading_image" style="display:none;"></div>
									<!--button class="btn btn-warning view_booking_details hidden">View Details</button>
									<input type="hidden" id="booking_id" value=""/-->
									
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-right btn-flat" data-dismiss="modal">Close</button>
							</div>
						</div>
					<!-- /.modal-content -->
					</div>
				  <!-- /.modal-dialog -->
				</div>
				<!-- /.modal Close for Viewing the alloted slot data-->

	      </div>
	      <!-- /.row -->


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
  <!--div class="fullcalender_content"><?php //include_once('fullcalender.php?');?></div-->
</div>
<!-- ./wrapper -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="plugins/table2excel/dist/jquery.table2excel.min.js"></script>


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
<!-- fullCalendar 2.2.5 -->

<!--div class="fullcalender_content"><?php //include_once('fullcalender.php?');?></div-->

<script id="calender_script">
	$(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
    }

    ini_events($('#external-events div.external-event'));

     /*initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    var lab_id_choosen = $('#lab_list').val();
    var calender_content_url = "calender_events.php";
    //alert(lab_id_choosen+'=>'+calender_content_url);
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
     defaultView: 'agendaWeek',
      buttonText: {
        today: 'today',
        month: 'month',
        week: 'week',
        day: 'day'
      },
      hiddenDays: [ 0 ],
      minTime: "08:00:00",
      maxTime: "18:00:00",
      //Random default events
      events : calender_content_url,
      //viewing the clicked event
         eventClick:  function(event, jsEvent, view) {
         //alert(event.id);
           // $('#booked_by_name').text(event.title);
           // $('#booked_by_date').text(event.start.format());
         //$('#booking_id').val(event.id);
            //$('#eventUrl').attr('href',event.url);
            $('#view_slot_alloted').modal();
          //for loading the booked seva
          var view_url = "ajax/view_slot_details.ajax.php";
      
         $("div #loading_image").removeAttr("style");
         $.post(
               view_url,{
                  p1 : event.id
               },
               function(data,status){
                  
                     $('.slot_slloted_data').html(data);
                     
                     // setTimeout(function () {
                           // window.location.reload();
                           // }, 2000);
               });
         //alert('seva booking modal');
     },
      //editable: true,
     /* droppable: true, // this allows things to be dropped onto the calendar !!!
      drop: function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        copiedEventObject.backgroundColor = $(this).css("background-color");
        copiedEventObject.borderColor = $(this).css("border-color");

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove();
        }

      }
    });

    /* ADDING EVENTS 
    var currColor = "#3c8dbc"; //Red by default
    //Color chooser button
    var colorChooser = $("#color-chooser-btn");
    $("#color-chooser > li > a").click(function (e) {
      e.preventDefault();
      //Save color
      currColor = $(this).css("color");
      //Add color effect to button
      $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
    });
    $("#add-new-event").click(function (e) {
      e.preventDefault();
      //Get value and make sure it is not null
      var val = $("#new-event").val();
      if (val.length == 0) {
        return;
      }

      //Create events
      var event = $("<div />");
      event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
      event.html(val);
      $('#external-events').prepend(event);

      //Add draggable funtionality
      ini_events(event);

      //Remove event from text input
      $("#new-event").val("");*/
    });
  });


	
</script>
<script>
$(document).ready(function(){	
	//for clock
  setInterval('updateClock()', 1000);
 //calender script

 
  //for setting the lab_id to fetch respctive events in the calender later.
  $(document).on('click','.lab_slot_view',function(e){
		e.preventDefault();
		//alert($('.calender_content').html());
		var lab_id = $('#lab_list').val();
		//alert(lab_id);
		var lab_slot_url = 'fullcalender.php';
		$.post(
			lab_slot_url,{
				l1 : lab_id
				},
			function(data,status){
					//$('.calender_content').html("<script>"+data+"<script>");
					$('#calender_div').html(data);
					//location.reload();
					//$("#calender").load(location.href + " #calender");
					/*$.getScript(data, function(){
					    alert("Successfully Running");
					});*/
						
			});

  });
 // To set and unset while updating the lab conduction details.
	$(document).on('click','#mySwitch',function(e){
		if ($(this).is(':checked')){
			$('#st_count').attr('disabled',true);
			$('#faculty').attr('disabled',true);
		}else{
			$('#st_count').removeAttr('disabled');
			$('#faculty').removeAttr('disabled');
		}
	});

	//to update the lab conducation details for individual labs.
	$(document).on('click','#update_lab_conduction',function(e){
		e.preventDefault();
		alert('clicked');
		//to check whether the conduction checkbox is checked.
		if($('#mySwitch').is(':checked')){
			var conduction_status = 0; // not conduccted
		}else{
			var conduction_status = 1; // conducted.
		}

		//alert(student_count +'-'+facult_incharge);
		//Exception handling
		if(conduction_status == 1 && (student_count == '' || facult_incharge == '')){
			alert('Please fill up the details');
			if(student_count == ''){
				$('#st_count').focus();
			}
			if(facult_incharge == ''){
				 $('#faculty').focus();
			}
		}
		var lab_id = $('#lab_id').val();

		//based on conduction status, change the values to update.
		if(conduction_status == 1){
			var student_count = $('#st_count').val();
			var facult_incharge = $('#faculty').val();

		}else{
			var student_count = 0
			var facult_incharge = '';
		}

		var update_url = 'ajax/update_lab_conduction.ajax.php';
		$.post(
			update_url,{
				l1 : conduction_status, l2 : student_count, l3 : facult_incharge, l4 : lab_id 
				},
			function(data,status){
					//$('.calender_content').html("<script>"+data+"<script>");
					$('.lab_slot_update_details').html(data);
					
			});

  	});


});
</script>
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
</body>
</html>
