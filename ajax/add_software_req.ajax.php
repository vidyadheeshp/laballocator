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

	$soft_req  = $_POST['p1'];

	$id = "NULL";
	$status = 1;
	$table_no = 4;
	$table_name = 'software_requirements';

	$insert_values = $id.",'".$soft_req."',".$status;
					
	//echo $insert_values;				
	$Insert_result = db_insert($table_no,$table_name,$insert_values);

	if($Insert_result == 1){
		
				?>
				<div class="callout callout-success">
							<h4>Successful</h4>
							<?php //echo "The file ". htmlspecialchars( basename($res_doc)). " has been uploaded.";?>
							<p>Requirement Added.</p>
				</div>
				<?php }else{?>
					<div class="callout callout-danger">
						<h4>Unable to add Requirement</h4>

						<p>Check Out.</p>
					  </div>
				<?php }
			

?>
