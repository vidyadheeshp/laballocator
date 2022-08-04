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
		            				$requisition_list_query = "SELECT * FROM courses WHERE 1=1 AND deptid=".$dept_id;
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