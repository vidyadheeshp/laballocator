<?php
include('../pages/required/db_connection.php');
include('../pages/required/functions.php');
include('../pages/required/tables.php');




function get_free_slots($lab_id,$no_batches, $duration,$startdate,$enddate){
    
    $week =["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    $get_lab_slots= db_all("select * from slot_list where duration=$duration");

    foreach($get_lab_slots as $slot)
    {
            
        $no_of_free_slots=0;
        $lab_slots_to_allocate=array();
       $day="";     
                    
        if($duration==2)
        {
            if($slot['slot_id']==7){
                if($day!="Saturday")
                {
                    foreach($week as $day)
                    {
                       //echo $day." ".$slot['slot_id'];
                        if($day!="Saturday" && ($slot['slot_id']!==3 || $slot['slot_id']!=6  ||  $slot['slot_id']!=7  || $slot['slot_id']!=9))
                        {
                
                            $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=7  ||  slot=3 ||  slot=9)");
                            if(count($check_lab_availability)==0)
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."6";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                    }
                }
            }
                        
                        
            else if($slot['slot_id']==6){
                if($day!="Saturday")
                {
                    foreach($week as $day)
                    {   
                        //echo $day." ".$slot['slot_id'];
                        if($day!="Saturday" && ($slot['slot_id']!==3 || $slot['slot_id']!=6  ||  $slot['slot_id']!=7  || $slot['slot_id']!=9))
                        {
                            $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=6  ||  slot=3  ||  slot=2  ||  slot=9)");
                            if(count($check_lab_availability)==0)
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."6";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                    }
                }
            }
            else if($slot['slot_id']==5) //2hr slot of 10.30am to 12.30am 
            {
                foreach($week as $day)
                {
                    //echo $day." ".$slot['slot_id'];
                               
                    $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=5  ||  slot=1  ||  slot=2  ||  slot=8)");
                    if(count($check_lab_availability)==0)
                    {
                         $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."5";
                         $no_of_free_slots++;
                        if($no_of_free_slots==$no_batches){
                            return $lab_slots_to_allocate;
                        }
                    }
                                
                }
            }
            else 
            if($slot['slot_id']==4) // 2hr slot of 9-11am
            {
                foreach($week as $day)
                {              
                 // echo "select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and labid=$lab_id and (slot=1  ||  slot=4  ||  slot=8)";               
                  //check if 2hr 8.30-10.30 and 3hr 8.30-11.30 and 4hr 8.30-12.30 is free
                    $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and labid=$lab_id and (slot=1  ||  slot=4  ||  slot=8)");
                                    
                    if(count($check_lab_availability)==0)
                    {
                        $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."4";
                        $no_of_free_slots++;
                        if($no_of_free_slots==$no_batches){
                            return $lab_slots_to_allocate;
                        }
                    }
                 }
            }
            
       }                
                     
        else if($duration==3){
            if($slot['slot_id']==3)
            {
                foreach($week as $day)
                {
                    //echo $day." ".$slot['slot_id'];
                    if($day!="Saturday"){
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$lab_id and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and (slot=3  ||  slot=6 || slot=7 ||  slot=9)");
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."3";
                            $no_of_free_slots++;
                            if($no_of_free_slots==$no_batches){
                                return $lab_slots_to_allocate;
                            }    
                        } 
                 
                    }
                                
               }
            }
                        
            else if($slot['slot_id']==2)
            {
                foreach($week as $day)
                {
                    //echo $day." ".$slot['slot_id'];
                                
                    $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$lab_id and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and (slot=2  ||  slot=5 ||  slot=8)");
                    if(count($check_lab_availability)==0)
                    {
                                
                        $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."2";
                        $no_of_free_slots++;
                        if($no_of_free_slots==$no_batches){
                            return $lab_slots_to_allocate;
                        }
                                        
                    }
                                
               }
            }
            else if($slot['slot_id']==1){
                foreach($week as $day)
                {
                     
                    $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$lab_id and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and (slot=1  ||  slot=4  ||  slot=8)");
                    //echo "lab=$lab_id day=$day and slot=".$slot['slot_id']." count=".count($check_lab_availability);
                    if(count($check_lab_availability)==0)
                    {
                        $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."1";
                        $no_of_free_slots++;
                               
                        if($no_of_free_slots==$no_batches){
                            return $lab_slots_to_allocate;
                        }

                    }
                                
                }
            }
                                 
        }
        if($duration==4){
            if($slot['slot_id']==9){
                foreach($week as $day)
                {
                    //echo $day." ".$slot['slot_id'];
                    if($day!="Saturday" && ($slot['slot_id']!==3 || $slot['slot_id']!=6  ||  $slot['slot_id']!=7  || $slot['slot_id']!=9))
                    {
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$lab_id and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and (slot=9  ||  slot=3 ||  slot=2 || slot=7 || slot=6)");
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."9";
                            $no_of_free_slots++;
                            if($no_of_free_slots==$no_batches){
                                return $lab_slots_to_allocate;
                            }    
                        }
                    }
                }
                 
            }
            else{
                foreach($week as $day)
                {
                    //echo $day." ".$slot['slot_id'];
                               
                    $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$lab_id and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and (slot=8  ||  slot=4 ||  slot=5 ||  slot=1 ||  slot=2)");
                    if(count($check_lab_availability)==0)
                    {
                        $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."8";
                        $no_of_free_slots++;
                        if($no_of_free_slots==$no_batches){
                            return $lab_slots_to_allocate;
                        }    
                    } 
                               
                }
                       
            } 
        }
    }
    return [];
}
        
    

function get_labs($labs,$lab_slots_to_allocate,$startdate,$enddate)
{
   
    $lab_to_allocate=array();
    $k=0;
    $day="";
    $slot="";
    $flags=array();
   
    for($j=0;$j<count($labs);$j++){
        for($i=0;$i<count($lab_slots_to_allocate);$i++){
            $flag[$i]=false;
        }
   

        for($i=0;$i<count($lab_slots_to_allocate);$i++){
            if(ctype_alpha($lab_slots_to_allocate[$i][1])){
                $day=substr($lab_slots_to_allocate[$i],1,strlen($lab_slots_to_allocate[$i])-2);
            }
            else{
                $day=substr($lab_slots_to_allocate[$i],2,strlen($lab_slots_to_allocate[$i])-3);
            }
            $slot=substr($lab_slots_to_allocate[$i],strlen($lab_slots_to_allocate[$i])-1);
        
            if($slot==1){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and labid=$labs[$j] and (slot=$slot  ||  slot=4  ||  slot=5  ||  slot=8)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==2){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and labid=$labs[$j] and (slot=$slot  ||  slot=6 ||  slot=5 || slot=8 || slot=9)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                 }
            }
            if($slot==3){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate'  and labid=$labs[$j] and (slot=$slot  ||  slot=6 ||  slot=7 || slot=9)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==4){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$labs[$j] and (slot=$slot  ||  slot=1 || slot=8)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==5){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$labs[$j] and (slot=$slot  ||  slot=1 ||  slot=2 || slot=8)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==6){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$labs[$j] and (slot=$slot  ||  slot=2 ||  slot=3 || slot=9)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==7){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$labs[$j] and (slot=$slot  ||  slot=3 ||  slot=9)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==8){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$labs[$j] and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and (slot=$slot  ||  slot=1 ||  slot=2 || slot=4 || slot=5)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
            if($slot==9){
                $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$labs[$j] and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and (slot=$slot  ||  slot=3 ||  slot=2 || slot=6 || slot=7)");
                if(count($check_lab_availability)==0)
                {
                    $flag[$i]=true;
                }
            }
        }
        $flag_to_allocate=true;
    
        for($l=0;$l<count($lab_slots_to_allocate);$l++){
            
            if($flag[$l]==false) $flag_to_allocate=false ;
                    
        }
        if($flag_to_allocate==true){
            $lab_to_allocate[$k++]=$labs[$j];
            
        }
       
    }
   
  return $lab_to_allocate;
}

function get_all_free_slots($labs,$duration,$startdate,$enddate){
    $week =["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    $get_lab_slots= db_all("select * from slot_list where duration=$duration");
    $lab_slots_to_allocate=array();
    $no_of_free_slots=0;
    
    foreach($labs as $lab_id){
        foreach($get_lab_slots as $slot)
        {
            if($duration==2)
            {
                if($slot['slot_id']==7)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 03pm-5pm and 3hr and 3hr 2-5 and 4hr 1.00-5. is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and ( slot=3  ||  slot=7  ||  slot=9)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."7";
                        }
                    }
                }
                if($slot['slot_id']==6)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 01pm-3pm and 3hr 11.30-2pm and 3hr 2-5 and 4hr 8.30-12.30 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and labid=$lab_id and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and (slot=2 || slot=3  ||  slot=6  || slot=8||  slot=9)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."6";
                        }
                    }
                }
                if($slot['slot_id']==5)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 11-01pm and 3hr 11.30-2pm and 4hr 8.30-12.30 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=1 || slot=2  ||  slot=5  ||  slot=8)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."5";
                        }
                    }
                }

                if($slot['slot_id']==4) // 2hr slot of 8.30-10.30am
                {
                    foreach($week as $day)
                    {              
                                   
                        //check if 2hr 09-11am and 3hr 8.30-11.30 and 4hr 8.30-12.30 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=1  ||  slot=4  ||  slot=8)");
                          
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."4";
                        }
                    }
                }
            }
            else if($duration==3){
                
                if($slot['slot_id']==3)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 01pm-3pm and 3hr 2-5 and 4hrs 1-5 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=3  ||  slot=6 || slot=7  ||  slot=9)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."3";
                        }
                    }
                }
                
                if($slot['slot_id']==2)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 11-01pm, 1-3pm and 3hr 11.30-2pm and 4hr 8.30-12.30, 1-5pm is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=2  ||  slot=5|| slot=6  ||  slot=8||slot=9)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."2";
                        }
                    }
                }
                if($slot['slot_id']==1) // 3hr slot of 8.30-11.30am
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 09-11am, 11-1pm and 3hr 8.30-11.30 and 4hr 8.30-12.30 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=1  ||  slot=4 || slot=5 ||  slot=8)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."1";
                        }
                    }
                }

            }
            else if($duration==4){
                
                if($slot['slot_id']==9)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 01pm-3pm,3-5 and 3hr 11.30-2pm, 2-5 and 4hrs 1-5 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=2 || slot=3 || slot=6  ||  slot=7  ||  slot=9)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."9";
                        }
                    }
                }
                if($slot['slot_id']==8)
                {
                    foreach($week as $day)
                    {              
                        
                        //check if 2hr 9-11,11-01pm and 3hr 8.30am-11.30pm, 11.30-2pm and 4hr 8.30-12.30 is free
                        $check_lab_availability=db_all("select * from laballocations where day='$day' and date_end between '$startdate' and '$enddate' and date_start between '$startdate' and '$enddate' and labid=$lab_id and (slot=1 || slot=2  ||  slot=4|| slot=5  ||  slot=8)");
                            
                        if(count($check_lab_availability)==0)
                        {
                            $lab_slots_to_allocate[$no_of_free_slots++]=$lab_id.$day."8";
                        }
                    }
                }
            }
        }
    }
        return $lab_slots_to_allocate;

}