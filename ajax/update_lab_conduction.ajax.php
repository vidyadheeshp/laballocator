<?php 
ini_set('display_errors', 1);
?>
<?php 

	if (session_id() == '') {
    session_start();
	//$_SESSION['first_name']=$result[0];
	$login_id = $_SESSION['s_id'];
}
	include('../pages/required/db_connection.php');
	include('../pages/required/tables.php');
	require('../pages/required/functions.php');

	$conduction_status  = $_POST['l1'];
	$student_count  = $_POST['l2'];
	$faculty_incharge  = $_POST['l3'];
	$lab_id   = $_POST['l4'];

	$table_name = "laballocations";
	$set_value =  "conduction_status = $conduction_status, student_count = $student_count, faculty_details = '".$faculty_incharge."', updated_by = $login_id";
	$where = "allocationid=$lab_id";

	$result = db_update($table_name,$set_value,$where); // to update the content.
	if($result == 1){
?>
	<div class="callout callout-success">
		<h4>Updated Successfully</h4>

		<p>The Lab deatils are updated Successfully.</p>
	</div>
<?php }else { ?>
	<div class="callout callout-danger">
		<h4>Unable yo update!</h4>

		<p>Kindly check</p>
	</div>
<?php } ?>