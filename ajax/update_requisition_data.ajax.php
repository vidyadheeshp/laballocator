	<?php 
ini_set('display_errors', 1);
?>
<?php 

/*	if (session_id() == '') {
    session_start();
	//$_SESSION['first_name']=$result[0];
	$login_id = $_SESSION['s_id'];
}*/
	include('../pages/required/db_connection.php');
	include('../pages/required/tables.php');
	require('../pages/required/functions.php');
	
	//data retrieval
	$course_code = $_POST['course_code'];
	$course_name = $_POST['course_name'];
	$dept = $_POST['dept'];
	
	$sem = $_POST['sem'];
	$no_div = $_POST['no_div'];
	$no_students = $_POST['no_students'];
	$duration = $_POST['duration'];
	//$aca_year = $_POST['academic_year']; 
	$req = $_POST['req'];
	
	//echo $_POST['academic_year'];
	//required_data
	$cid = $_POST['cid'];
	$allocation_status = 0;
	$table_name = 'courses';

	$update_values = 'coursecode="'.$course_code.'",coursename="'.$course_name.'",deptid='.$dept.',sem='.$sem.',divisions='.$no_div.',strength='.$no_students.',softwarereq='.$req.',duration='.$duration;	
	$where_value = "cid=".$cid;	
	//echo $update_values;				
	$update_result = db_update($table_name,$update_values,$where_value);

	if($update_result == 1){
		
				?>
				<div class="callout callout-success">
							<h4>Successful</h4>
							<?php //echo "The file ". htmlspecialchars( basename($res_doc)). " has been uploaded.";?>
							<p>Requisition Updated.</p>
				</div>
				<?php }else{?>
					<div class="callout callout-danger">
						<h4>Unable to update the requisition</h4>

						<p>Check Out.</p>
					  </div>
				<?php }
			

?>