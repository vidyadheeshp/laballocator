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
	$aca_range = explode(" - ", $_POST['p1']);
	$start_date = db_date($aca_range[0]);
	$end_date = db_date($aca_range[1]); //db_date($_POST['p2']);
	
	//echo $start_date.':'.$end_date;
	$aca_year = "2022-2023";
	//required_data
	$aca_id = "NULL";
	$table_no = 2;
	$table_name = 'academic_year';
	$status = 1;

	$insert_values = $aca_id.",'".$start_date."','".$end_date."','".$aca_year."',".$status;
					
	//echo $insert_values;				
	$Insert_result = db_insert($table_no,$table_name,$insert_values);

	if($Insert_result == 1){
		
				?>
				<div class="callout callout-success">
							<h4>Successful</h4>
							<?php //echo "The file ". htmlspecialchars( basename($res_doc)). " has been uploaded.";?>
							<p>Academic Year Added.</p>
				</div>
				<?php }else{?>
					<div class="callout callout-danger">
						<h4>Unable to add academic Year</h4>

						<p>Check Out.</p>
					  </div>
				<?php }
			

?>