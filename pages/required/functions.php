<?php 
		//function for fetching more no of rows from the database.
	function db_all($sql_query){
		include('db_connection.php');
		//$db_select = mysqli_select_db("em", $con);
		$query = mysqli_query($con,$sql_query);
		// Fetch all
		//print_r($query);
		$rows= array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $rows[] = $row;
        }
        //print_r ($rows);
        return $rows;
	}

	
	function db_one($sql_query){
		include('db_connection.php');
		//fetch one
		//$db_select = mysql_select_db("em", $con);
		$result = mysqli_query($con, $sql_query);
		$row = mysqli_fetch_assoc($result);
		//echo $result;
		return $row;
	}
	
	function login_message($msg,$type){
	
		if($type == 1){
			$color = 'success';
		}else{
			$color = 'danger';
		}
		$print_msg = "<div class='callout callout-".$color."'>
								<h4>".$msg."</h4>
					</div>";
		echo $print_msg;
		return 0;
	}
	
	function db_delete($from,$where){		
	include('db_connection.php');

	}
	
	function db_insert($table_no,$table_name,$values){
		include('db_connection.php');
		include('tables.php');
		//$table_name='';
		//$db_select = mysql_select_db("em", $con);
		$table_content = $tables[$table_no][$table_name];
		//echo $table_content;
		$return_msg='';
		//echo $table_content;
		$sql = "INSERT INTO ".$table_name."(".$table_content.") VALUES (".$values.");";
		//echo $sql;
		//$result = mysqli_query($con, $sql);
		//for fetching the inserted if part is used for a custome table called equipment_master.
		
			if (mysqli_query($con,$sql) == TRUE) {
					return 1;
				}else{
					return 0;
				}
			//return $return_msg;
	}
	
	function db_update($table_name,$set_value,$where_value){
		include('db_connection.php');
		
		//$db_select = mysql_select_db("em", $con);
		$sql_query = 'UPDATE '.$table_name.' SET '.$set_value.' WHERE '.$where_value;
		//echo $sql_query;
		if (mysqli_query($con,$sql_query)== TRUE) {
			return 1;
		}else{
			return 0;
			}
		
	}
	
	function days_from_dates($date1,$date2){
		$datetime1 = date_create($date1);
		$datetime2 = date_create($date2);
	   
		$interval = date_diff($datetime1, $datetime2);
	   
		return $interval->format('%a');
		
	}
	
	function db_date($ui_date){
		include('db_connection.php');
		$ui_seperator = '/';
		$db_seperator = '-';
		$exploded_date = explode($ui_seperator,$ui_date);
		//print_r($exploded_date);
		 $db_date = $exploded_date[2].$db_seperator.$exploded_date[0].$db_seperator.$exploded_date[1];
		 return $db_date;
	}
	
	function ui_date($db_date){
		include('db_connection.php');
		$ui_seperator = '/';
		$db_seperator = '-';
		$exploded_date = explode($db_seperator,$db_date);
		$ui_date = $exploded_date[1].$ui_seperator.$exploded_date[2].$ui_seperator.$exploded_date[0];
		return $ui_date;
	}
	
	//function for getting the data between the braces
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
												
	
	//mailer function
	
	function mail_to($to,$name,$body){
		
		require("phpmailer/PHPMailerAutoload.php");

		$mail = new PHPMailer();

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = '';                 // SMTP username
		$mail->Password = '';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('from@example.com', 'Mailer');
		//changed here
		$mail->addAddress($to , $name);     // Add a recipient
		$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC('cc@example.com');
		$mail->addBCC('bcc@example.com');

		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Testing mail';
		$mail->Body    = $body;
		//'This is the HTML message body <b>in bold!</b> testing email';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			$error_message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;;
			return $error_message;
		} else {
			return 1;
		}
				
	}
	
	//function for finding the difference between a pair of time and the preasent time.
	function time_diff($from_time,$to_time){
			date_default_timezone_set('Asia/Kolkata');
			$curr_time= date("h:i:s");
			//echo (strtotime($curr_time)- strtotime($from_time).'-');
			$from_time_val = strtotime($from_time);
			$to_time_val = strtotime($to_time);
			echo date('H',$from_time_val);
			//echo strtotime($to_time).'-';
			// if(strtotime($curr_time)>strtotime($from_time)) if(strtotime($curr_time)<strtotime($to_time)){
				// return 1;
			// }else{
				// return 0;
			// }
	}
	// custom function for updating the equipment status whether it is available or not accoring to the present time	
		//file_get_contents('http://example.com/scripts/calc.php');
	//}
	function update_equipment_status() {
				include_once('update_equipment_status.php');
				return $update_result;
	}
	
	function validate_email($email)
	{
		$regex = '/([a-z0-9_.-]+)'. 
		'@'. 
		'([a-z0-9.-]+){2,255}'. 
		'./'; 
		
		if($email == '') 
			return false;
		else
			$eregi = preg_replace($regex, '', $email);
		//echo $eregi;
		return empty($eregi) ? true : false;
	}
	
	
?>	
		
