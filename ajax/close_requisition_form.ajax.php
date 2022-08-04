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
	$Nilldate = $_POST['p1'];
	
	$table_name = 'academic_year';

	$set_val = "to_date='".date('Y-m-d')."'";
	
	//Fetching Where Value (The last inserted value)
	$query = "SELECT * FROM academic_year WHERE 1=1 ORDER BY id DESC LIMIT 1";
	$result_from_query = db_one($query);
	
	$where_val = "id=".$result_from_query['id'];
					
	$update_result = db_update($table_name,$set_val,$where_val);

	if($update_result == 1){
		
				?>
				<div class="callout callout-success">
							<h4>Successful</h4>
							<?php //echo "The file ". htmlspecialchars( basename($res_doc)). " has been uploaded.";?>
							<p>Requisition Closed.</p>
				</div>
				<?php }else{?>
					<div class="callout callout-danger">
						<h4>Unable to close</h4>

						<p>Check Out.</p>
					  </div>
				<?php }
			

?>