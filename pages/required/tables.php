<?php 

	$tables = array(
		1 => 
			array (
				'courses' => 'cid, coursecode, coursename, deptid, sem, divisions, strength, softwarereq, academicyear, allocation_status'
				),
		2 => 
			array (
				'academic_year' => 'id, from_date, to_date, aca_year, status'
			),
		3 => 
			array (
				'laballocations' => 'allocationid, day, date_start, date_end, slot, coursecode, batchno, academicyear, labid'
			),
		4 => 
			array (
				'software_requirements' => 'id, software_req, status'
			),

		);
		
		// foreach($tables AS $row){
			// echo $row[$key => $value]."<br/>";
			
		// }
		//print_r($tables[3]['patient_register']);
?>