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
	$aca_year = $_POST['academic_year']; 
	$req = $_POST['req'];
	
	//echo $_POST['academic_year'];
	//required_data
	$cid = "NULL";
	$allocation_status = 0;
	$table_no = 1;
	$table_name = 'courses';

	$insert_values = $cid.',"'.$course_code.'","'.$course_name.'",'.$dept.','.$sem.','.$no_div.','.$no_students.','.$req.','.$duration.','.$aca_year.','.$allocation_status;
					
	echo $insert_values;				
	//$Insert_result = db_insert($table_no,$table_name,$insert_values);

	if($Insert_result == 1){
		
				?>
				<div class="callout callout-success">
							<h4>Successful</h4>
							<?php //echo "The file ". htmlspecialchars( basename($res_doc)). " has been uploaded.";?>
							<p>Requisition Added.</p>
				</div>
				<?php }else{?>
					<div class="callout callout-danger">
						<h4>Unable to add requisition</h4>

						<p>Check Out.</p>
					  </div>
				<?php }
			

?>