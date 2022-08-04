<?php
include('../pages/required/db_connection.php');
include('../pages/required/functions.php');
include('../pages/required/tables.php');




function get_free_slots($lab_id,$no_batches, $duration, $lab_2hrs_status,$lab_3hrs_status,$lab_4hrs_status){
    $week =["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    $get_lab_slots= db_all("select * from slot_list where duration=$duration");
    
        foreach($get_lab_slots as $slot)
        {
            
            $no_of_free_slots=0;
            $lab_slots_to_allocate=array();
            foreach($week as $day)
            {
                //echo $day." ".$slot['slot_id'];
                if($day!="Saturday" && ($slot['slot_id']!==3||$slot['slot_id']!=6 || $slot['slot_id']!=7 ||$slot['slot_id']!=9))
                {
                    
                    if($duration==2)
                    {
                        if($slot['slot_id']==4) // 2hr slot of 8.30-10.30am
                        {
                            //check if 2hr 8.30-10.30 and 3hr 8.30-11.30 and 4hr 8.30-12.30 is free
                            if($lab_2hrs_status[$lab_id.$day."4"]=="" && $lab_3hrs_status[$lab_id.$day."1"]=="" && $lab_4hrs_status[$lab_id.$day."8"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."4";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                        else if($slot['slot_id']==5) //2hr slot of 10.30am to 12.30am 
                        {
                            if($lab_2hrs_status[$lab_id.$day."5"]=="" && $lab_3hrs_status[$lab_id.$day."1"]=="" && $lab_3hrs_status[$lab_id.$day."2"]=="" && $lab_4hrs_status[$lab_id.$day."8"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."5";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                        else if($slot['slot_id']==6){
                            if($day!="Saturday")
                            if($lab_2hrs_status[$lab_id.$day."6"]=="" && $lab_3hrs_status[$lab_id.$day."3"]=="" && $lab_3hrs_status[$lab_id.$day."2"]=="" && $lab_4hrs_status[$lab_id.$day."9"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."6";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                        else{
                            if($day!="Saturday")
                            if($lab_2hrs_status[$lab_id.$day."7"]=="" && $lab_3hrs_status[$lab_id.$day."3"]=="" && $lab_4hrs_status[$lab_id.$day."9"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."6";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                        
                    }
                    else if($duration==3){
                        
                        if($slot['slot_id']==1){
                        
                            if($lab_3hrs_status[$lab_id.$day."1"]=="" && $lab_2hrs_status[$lab_id.$day."4"]=="" && $lab_2hrs_status[$lab_id.$day."5"]=="" && $lab_4hrs_status[$lab_id.$day."8"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."1";
                                $no_of_free_slots++;
                               
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                        else if($slot['slot_id']==2)
                        {
                            if($lab_3hrs_status[$lab_id.$day."2"]=="" && $lab_2hrs_status[$lab_id.$day."5"]=="" && $lab_4hrs_status[$lab_id.$day."8"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."2";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }
                            }
                        }
                        else if($slot['slot_id']==3 && $day!="Saturday")
                        {
                            
                            if($lab_3hrs_status[$lab_id.$day."3"]=="" && $lab_2hrs_status[$lab_id.$day."6"]==""  && $lab_4hrs_status[$lab_id.$day."9"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."3";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }    
                            } 
                        }         
                    }
                    if($duration==4){
                        if($slot['slot_id']==8){
                            if($lab_4hrs_status[$lab_id.$day."8"]=="" && $lab_2hrs_status[$lab_id.$day."4"]=="" && $lab_2hrs_status[$lab_id.$day."5"]=="" && $lab_3hrs_status[$lab_id.$day."1"]==""&&$lab_3hrs_status[$lab_id.$day."2"]=="")
                            {
                                $lab_slots_to_allocate[$no_of_free_slots]=$lab_id.$day."8";
                                $no_of_free_slots++;
                                if($no_of_free_slots==$no_batches){
                                    return $lab_slots_to_allocate;
                                }    
                            } 
                        }
                        else{
                            if($day!="Saturday")
                            if($lab_4hrs_status[$lab_id.$day."9"]=="" && $lab_3hrs_status[$lab_id.$day."3"]=="" && $lab_3hrs_status[$lab_id.$day."2"]=="")
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
        }     
    }
}

function get_labs($labs,$lab_slots_to_allocate, $lab_2hrs_status,$lab_3hrs_status,$lab_4hrs_status)
{
   
    $lab_to_allocate=array();
    $k=0;
    $day="";
    $slot="";
    $flags=array();
    for($i=0;$i<count($lab_slots_to_allocate);$i++)
        $flag[$i]=false;
    for($j=0;$j<count($labs);$j++){
        for($i=0;$i<count($lab_slots_to_allocate);$i++){
            $day=substr($lab_slots_to_allocate[$i],1,strlen($lab_slots_to_allocate[$i])-2);
            $slot=substr($lab_slots_to_allocate[$i],strlen($lab_slots_to_allocate[$i])-1);
            
        
            if($slot==1){
                if($lab_3hrs_status[$labs[$j].$day.$slot]=="" && $lab_2hrs_status[$labs[$j].$day."4"]==""
                 &&$lab_2hrs_status[$labs[$j].$day."5"]=="" &&
                 $lab_4hrs_status[$labs[$j].$day."8"]==""){
                    $flag[$i]=true;
                 }
            }
            if($slot==2){
                if($lab_3hrs_status[$labs[$j].$day.$slot]=="" && $lab_2hrs_status[$labs[$j].$day."6"]==""
                 &&$lab_2hrs_status[$labs[$j].$day."5"]=="" &&
                 $lab_4hrs_status[$labs[$j].$day."8"]=="" && $lab_4hrs_status[$labs[$j].$day."9"]=="" ){
                    $flag[$i]=true;
                 }
            }
            if($slot==3){
                if($lab_3hrs_status[$labs[$j].$day.$slot]=="" && $lab_2hrs_status[$labs[$j].$day."6"]==""
                &&$lab_2hrs_status[$labs[$j].$day."7"]=="" &&
                $lab_4hrs_status[$labs[$j].$day."9"]=="" ){
                    $flag[$i]=true;
                }
            }
            if($slot==4){
                if($lab_2hrs_status[$labs[$j].$day.$slot]=="" && $lab_3hrs_status[$labs[$j].$day."1"]==""
                && $lab_4hrs_status[$labs[$j].$day."8"]==""){
                    $flag[$i]=true;
                }
            }
            if($slot==5){
                if($lab_2hrs_status[$labs[$j].$day.$slot]=="" && $lab_3hrs_status[$labs[$j].$day."1"]==""
                && $lab_3hrs_status[$labs[$j].$day."2"]==""&& $lab_4hrs_status[$labs[$j].$day."8"]==""){
                    $flag[$i]=true;
                }
            }
            if($slot==6){
                if($lab_2hrs_status[$labs[$j].$day.$slot]=="" && $lab_3hrs_status[$labs[$j].$day."2"]==""
                && $lab_3hrs_status[$labs[$j].$day."3"]==""&& $lab_4hrs_status[$labs[$j].$day."9"]==""){
                    $flag[$i]=true;
                }
            }
            if($slot==7){
                if($lab_2hrs_status[$labs[$j].$day.$slot]=="" 
                && $lab_3hrs_status[$labs[$j].$day."3"]==""&& $lab_4hrs_status[$labs[$j].$day."9"]==""){
                    $flag[$i]=true;
                }
            }
            if($slot==8){
                if($lab_4hrs_status[$labs[$j].$day.$slot]=="" &&$lab_3hrs_status[$labs[$j].$day."1"]==""
                && $lab_3hrs_status[$labs[$j].$day."2"]==""&& $lab_2hrs_status[$labs[$j].$day."4"]==""
                && $lab_2hrs_status[$labs[$j].$day.""]==""){
                    $flag[$i]=true;
                }
            }
            if($slot==9){
                if($lab_4hrs_status[$labs[$j].$day.$slot]=="" &&$lab_3hrs_status[$labs[$j].$day."3"]==""
                && $lab_3hrs_status[$labs[$j].$slot."2"]==""&& $lab_2hrs_status[$labs[$j].$day."6"]=="")
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
