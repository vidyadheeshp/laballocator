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

	$id  = $_POST['p1'];


	$query = "SELECT 
				l.*,
				l.allocationid, 
				c.coursename,
				c.coursecode,
				la.labname,
				c.deptid,
				u.Name,
				DATE_FORMAT(l.date_start,''),
				DATE_FORMAT(l.date_start,'%h:%i %p')AS start_time,
				DATE_FORMAT(l.date_end,'%h:%i %p') AS end_time
			  FROM 
			  	laballocations l
			  	INNER JOIN courses c on c.cid = l.coursecode
			  	INNER JOIN labs la ON la.labid = l.labid
			  	INNER JOIN users u ON u.id = c.deptid
			  WHERE 
			  	1=1
			  	AND l.allocationid=".$id;
	$result = db_one($query);


?>
<table class="table table-bordered table-responsive">
	<tr>
		<th>Lab Number</th>
		<td><?php echo $result['labname']?></td>
	</tr>
	<tr>
		<th>Start Time</th>
		<td><?php echo $result['start_time']?></td>
	</tr>
	<tr>
		<th>End Time</th>
		<td><?php echo $result['end_time']?></td>
	</tr>
	<tr>
		<th>Course Code</th>
		<td><?php echo $result['coursecode']?></td>
	</tr> 
	<tr>
		<th>Course</th>
		<td><?php echo $result['coursename']?></td>
	</tr>
	<tr>
		<th>Department </th>
		<td><?php echo $result['Name']?></td>
	</tr>
	
</table>
<div class="lab_slot_update_details">
	<form class="form">
		<div class="row">
			<div class="form-check form-switch col-md-4">
				 <label class="form-check-label" for="mySwitch">Mark As NOT Conducted</label>
			  <input class="form-check-input" type="checkbox" id="mySwitch" name="darkmode"/>
			 	<input type="hidden" class="form-control" id="lab_id" value="<?php echo $result['allocationid'];?>">
			</div>
			
		</div>
		<div class="row">
			<div class="col-md-4 form-group">
				<label class="help-block">Student Count (Present Count)</label>
				<input type="text" id="st_count" class="form-control" placeholder="No of Students present for the Lab" title="No of Students present for the Lab" />
			</div>
			<div class="col-md-4 form-group">
				<label class="help-block">Incharge Faculty</label>
				<input type="text" id="faculty" class="form-control" placeholder="Faculty Initial (ex: prof.ABC)" title="Faculty Initial (ex: prof.ABC)" />
			</div>
			<div class="col-md-4 form-group">
				<label class="help-block"></label>
				<button class="btn btn-success" type="button" id="update_lab_conduction"> <i class="fa fa-check"></i> Update</button>
			</div>
		</div>
	</form>
	<div class="clearfix"></div>
</div>