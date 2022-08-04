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

	$dept_id  = $_POST['p1'];


	$query = "SELECT 
				cid,
				count(cid) AS requisition_count
			  FROM 
			  	courses
			  WHERE 
			  	1=1
			  	AND deptid=".$dept_id;
	$result = db_one($query);

//The color code for each department
	if($dept_id == 1){ // Aero
			$backgroundcolor = '#F3940B';
		
		}else if($dept_id == 2){ //Arch
			$backgroundcolor = '#F7E505';
			
		}else if($dept_id == 3){ //Civil
			$backgroundcolor = '#AAF705';
		
		}else if($dept_id == 4){ //CSE
			$backgroundcolor = '02C4F9';
			
		}else if($dept_id == 5){ //EEE
			$backgroundcolor = '#B105F7';
			
		}else if($dept_id == 6){//ECE
			$backgroundcolor = '#4F0A52';
			
		}else if($dept_id == 7){// First Year CCP
			$backgroundcolor = '#0A5250';
		
		}else if($dept_id == 8){//ISE
			$backgroundcolor = '#DE104B';
			
		}else if($dept_id == 9){ //MBA
			$backgroundcolor = '#7E956C';
			
		}else if($dept_id == 10){// MCA
			$backgroundcolor = '#58B9FC';
	
		}else if($dept_id == 11){ // MECH
			$backgroundcolor = '#030FFE';
			
		}else{    /// Other depts.
			$backgroundcolor = '#FEFE02';
			
		}

?>
<div class="col-xs-6 col-md-3 text-center">
    <input type="text" class="knob" value="<?php echo $result['requisition_count'];?>" data-width="90" data-height="90" data-fgColor="<?php echo $backgroundcolor;?>">

  <div class="knob-label">Requisition Count</div>
</div>