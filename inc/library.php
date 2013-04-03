<?php
include ("dbutil.php");
dbconnect();
//echo "here";

function ListNumber($selday=0,$start,$end)
{
 for($i=$start;$i<=$end;$i++)
   {
    if($i==$selday) $option.= "<option selected value='$i'>".$i."</option>";
  
    else  $option.= "<option  value='$i'>".$i."</option>";
   }
	return $option;
}  


function ListDay($selday=1)
{
 $day=array("","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
   for($i=1;$i<=31;$i++)
   {
    if($i==$selday) $option.= "<option selected value='$day[$i]'>".$day[$i]."</option>";
  
    else  $option.= "<option  value='$day[$i]'>".$day[$i]."</option>";
   }
	return $option;
}  


function ListMonth($selmonth=0)
  { 
    $option="";
    $month=array("","01","02","03","04","05","06","07","08","09","10","11","12");
	for($i=1;$i<=12;$i++)
	{
	  if($i==$selmonth) $option.= "<option selected value='$month[$i]'>".$month[$i]."</option>";
	  else  $option.= "<option  value='$month[$i]'>".$month[$i]."</option>";
	}
	return $option;
  }


function ListYear($selyear,$fyear=2006,$tyear=2015)
  { 
    $option="";
	if(is_null($tyear)) $tyear=$fyear+10;
	for($i=$fyear;$i<=$tyear;$i++)
	{
	  if($i==$selyear) $option.= "<option selected value='$i'>$i</option>";
	  else  $option.= "<option  value='$i'>$i</option>";
	}
	return $option;
  }
  
  
function ListField($tbl="",$val="")
{
    if(isset($list)) unset($list);
    global $tbl_qry;
    global $list;
    global $lname;
    global $lid;
    $list=""; $lname="";
    if($tbl_qry=="") 
	{
      $crset=mysql_query("select * from $tbl order by 2") or die("te");
    } 
	else 
	{
	  $crset=mysql_query($tbl_qry);
    }
    if($crset) 
	{
      while($clist=mysql_fetch_row($crset))
	  {
	    if(strlen($clist[1])>60) $disp=substr($clist[1],0,65); else $disp=$clist[1];
        if($val==$clist[0] and $val!="") 
		{
	      $lname=$disp;
 	      $list.="<option value='".$clist[0]."' selected>".$disp."</option>";
 	    } 
	    else 
		{
	      $list.="<option value='".$clist[0]."'>".$disp."</option>";
	    }
      }
	$tbl_qry="";
    }
    else 
	{
	  $list="";
	  $lname="";
    }
    return $list;
}


//################################### Change Date Format to dd-mm-yyyy

function ConvDate($rqdate) 
{
  ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $rqdate,$arrdate); 
  if($arrdate[3]>0)
	  return "$arrdate[3]-$arrdate[2]-$arrdate[1]";   
  else
  	  return false;
} 


//################################### Change Date Format to yyy-mm-dd

function ConvDate1($rqdate) 
{
  ereg("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})", $rqdate,$arrdate); 
  if($arrdate[1]>0)
	  return "$arrdate[3]-$arrdate[2]-$arrdate[1]";   
  else
  	 return false;
} 

//################################### Return start date and end date of required YEAR

function rangeYear($datestr) {
    /*date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('Y-m-d', strtotime('first day of this year', $dt));
    $res['end'] = date('Y-m-d', strtotime('last day of this year', $dt));
    return $res;*/
	
	date_default_timezone_set(date_default_timezone_get());
    $dt = date("Y", strtotime($datestr));
    $res['start'] = date('Y-m-d', strtotime('first day of January '.$dt.''));
    $res['end'] = date('Y-m-d', strtotime('last day of December '.$dt.''));
    return $res;
    }

//################################### Return start date and end date of required MONTH

function rangeMonth($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));
    $res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));
    return $res;
    }

//################################### Return start date and end date of required WEEK

function rangeWeek($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
    $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
    return $res;
    }


//################################### Create date range array between 2 dates

function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}


//################################### Gives diffence between dates in many ways

function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */
  
  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds
  
  switch($interval) {
  
    case 'yyyy': // Number of full years

      $years_difference = floor($difference / 31536000);
      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
        $years_difference--;
      }
      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      }
      $datediff = $years_difference;
      break;

    case "q": // Number of full quarters

      $quarters_difference = floor($difference / 8035200);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $quarters_difference--;
      $datediff = $quarters_difference;
      break;

    case "m": // Number of full months

      $months_difference = floor($difference / 2678400);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $months_difference--;
      $datediff = $months_difference;
      break;

    case 'y': // Difference between day numbers

      $datediff = date("z", $dateto) - date("z", $datefrom);
      break;

    case "d": // Number of full days

      $datediff = floor($difference / 86400);
      break;

    case "w": // Number of full weekdays

      $days_difference = floor($difference / 86400);
      $weeks_difference = floor($days_difference / 7); // Complete weeks
      $first_day = date("w", $datefrom);
      $days_remainder = floor($days_difference % 7);
      $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
      if ($odd_days > 7) { // Sunday
        $days_remainder--;
      }
      if ($odd_days > 6) { // Saturday
        $days_remainder--;
      }
      $datediff = ($weeks_difference * 5) + $days_remainder;
      break;

    case "ww": // Number of full weeks

      $datediff = floor($difference / 604800);
      break;

    case "h": // Number of full hours

      $datediff = floor($difference / 3600);
      break;

    case "n": // Number of full minutes

      $datediff = floor($difference / 60);
      break;

    default: // Number of full seconds (default)

      $datediff = $difference;
      break;
  }    

  return $datediff;

}

//Generate Calender//////////////

function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array(),$root){
    $first_of_month = gmmktime(0,0,0,$month,1,$year);
    #remember that mktime will automatically correct if invalid dates are entered
    # for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
    # this provides a built in "rounding" feature to generate_calendar()

    $day_names = array(); #generate all the day names according to the current locale
    for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
        $day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

    list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
    $weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
    $title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

    #Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
    @list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
    if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
    if($n) $n = '&nbsp;<span class="calendar-next" bgcolor="#008800">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
    $calendar = '<table cellpadding="0" cellspacing="2" border="0">'."\n".
        '<caption class="calendar-month" >'."</caption>\n<tr>";

    if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
        #if day_name_length is >3, the full name of the day will be printed
        foreach($day_names as $d)
            $calendar .= "<th  abbr='".htmlentities($d)."'bgcolor='#CEEBBF'><font face='verdana' size='1' color='#FF0000'>".htmlentities($day_name_length < 4 ? substr($d,0,1) : $d)."</font></th>";
        $calendar .= "</tr>\n<tr bgcolor='#CEEBBF'>";
    }

    if($weekday > 0) {
	$emptycols=$weekday;
	  while($emptycols>0) {
	    $calendar .= '<td bgcolor="#CEEBBF" >&nbsp;</td>'; #initial 'empty' days
		$emptycols--;
	  }
	}
    for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
        if($weekday == 7){
            $weekday   = 0; #start a new week
            $calendar .= "</tr>\n<tr bgcolor='#CEEBBF'>";
        }
        if(isset($days[$day]) and is_array($days[$day])){
            @list($link, $classes, $content) = $days[$day];
            if(is_null($content))  $content  = $day;
            $calendar .= '<td '.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
                ($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
        }
        else 
		  {
		 	$cday=date("j");
			if($cday==$day) $calendar .= "<td align='center' bgcolor='#CEEBBF'>&nbsp;".checklink($day,$root)."&nbsp;</td>";
			else $calendar .= "<td align='center' >&nbsp;".checklink($day,$root)."&nbsp;</td>";

		  }	
    }
    if($weekday != 7) {
	  $emptycols=7-$weekday;
	  while($emptycols>0) {
	    $calendar .= '<td>&nbsp;</td>'; #remaining "empty" days
		$emptycols--;
	  }
	}

    return $calendar."</tr>\n</table>\n";
}




/*function db_createlist($select,$default,$query,$blank)
{
    if($blank)
    {
       print("<option select value=\"0\">$blank</option>");
    }       
			
    $rst1=mysql_query($query) or die(mysql_error());    
	$num = mysql_num_rows($rst1);  
    
    while($num>0)
    {
        $row = mysql_fetch_array($rst1);
        $cid=$row['id'];
		$idname=$row['name'];
        if($select==$cid)
		$a = $a."<option value='$cid' selected>$idname</option>";
        else 
		$a = $a."<option value='$cid'>$idname</option>";		
		$num--;		
    }	
	return $a;
}*/


function db_createlist($select, $show, $result, $blank, $item, $itemid)
{
	//return $result;
	$name = $item;	
	$itid = $itemid;
	
	if($blank)
    {
       print("<option select value=\"0\">$blank</option>");
    }       
	    
    foreach($result as $row)
    {
        $id = $row->$itid;
		//$idname=$row[$name];
		$idname=ucwords(strtolower($row->$name));
		
		if($id==$show)
		$a = $a."<option value='$id' selected> $idname</option>";
		else 
		$a = $a."<option value='$id'> $idname</option>";
				
		$num--;		
    }	
	return $a;
}


function db_createlist_multiple($select,$show,$query,$blank, $item, $itemid)
{
	$name = $item;	
	$itid = $itemid;
	/*echo "<script> alert ('".$show."'); </script>";*/
	if($blank)
    {
       print("<option select value=\"0\">$blank</option>");
    }       
			
    $rst1=mysql_query($query) or die(mysql_error());    
	$num = mysql_num_rows($rst1);  
	
	//************************
	$ch = array();
	$ch = explode(":",$show);	
	//************************
	//$default = $show;
    
    
    while($num>0)
    {
        $row = mysql_fetch_array($rst1);
        $id=$row[$itid];
		$idname=$row[$name];
		
        
			if (strpos($show, ':') !== false)
			{
				if(in_array($id,$ch))
				$a = $a."<option value='$id' selected> $idname</option>";
				else 
				$a = $a."<option value='$id'> $idname</option>";
			}
			else
			{
				if($id==$show)
				$a = $a."<option value='$id' selected> $idname</option>";
				else 
				$a = $a."<option value='$id'> $idname</option>";
			}
		
        		
		$num--;		
    }	
	return $a;
}


//************************ URL Functions Start **********************

function base64UrlEncode($data)
{
  return strtr(rtrim(base64_encode($data), '='), '+/', '-_');
}

function base64UrlDecode($base64)
{
  return base64_decode(strtr($base64, '-_', '+/'));
}



// ==== I don't guarantee this is faster than the PHP 6 before needle, ====
// ====  but it works for PHP below 6 atleast. ====
// ==== IT ALSO HAS INCLUDE NEEDLE BOOLEAN.. ====
function strstrbi($haystack,$needle,$before_needle,
$include_needle,$case_sensitive)
{
  $strstr = ($case_sensitive) ? 'strstr' : 'stristr';
  if($before_needle!=true && $before_needle!=false && isset($before_needle)){
      die('PHP: Error in function '.chr(39).'$strstrbi'. chr(39).' :  parameter '. chr(39).'$before_needle'.chr(39).' is not a supplied as a boolean.');
  } // END BOOLEAN CHECK '$before_needle'

  if($include_needle!=true && $include_needle!=false && isset($include_needle)){
    die('PHP: Error in function '.chr(39).'$strstrbi'. chr(39).' : parameter '. chr(39).'$include_needle'.chr(39). ' is not a supplied as a boolean.');
  } // END BOOLEAN CHECK '$include_needle'

  if($case_sensitive!=true && $case_sensitive!=false && isset($case_sensitive)){
    die('PHP: Error in function '.chr(39).'$strstrbi' .chr(39).' : parameter '. chr(39).'$case_sensitive'.chr(39).' is not a supplied as a boolean.');
  } // END BOOLEAN CHECK '$case_sensitive'

  if(!isset($before_needle)){
    $before_needle=false;
  }

  if(!isset($include_needle)){
    $include_needle=true;
  }

  if(!isset($case_sensitive)){
    $case_sensitive=false;
  }

  switch($before_needle){
    case true:
      switch($include_needle){
        case true:
          $temp=strrev($haystack);
          $ret=strrev(substr($strstr($temp,$needle),0));
          break;
        // END case true : $include_needle
        case false:
          $temp=strrev($haystack);
          $ret=strrev(substr($strstr($temp,$needle),1));
          break;
        // END case false : $include_needle
      }
      break;
    // END case true : $before_needle
    case false:
      switch($include_needle){
        case true:
          $ret=$strstr($haystack,$needle);
          break;
        // END case true: $include_needle
        case false:
          $ret=substr($strstr($haystack,$needle),1);
          break;
        // END case false: $include_needle
    }
    break;
    // END case false : $before_needle
  }

  if(!empty($ret)){
    return $ret;
  }else{
    return false;
  }
}
// === END FUNCTION 'strstrbi' 

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

//************************ URL Functions END **********************

//Get Client IP Address
function get_ip_address() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}

//file_get_contents Alternative
function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function isValidURL($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function validateURL($URL) {
    $v = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
    return (bool)preg_match($v, $URL);
}

//*****************************************************************

//Password Generater with length specifications

function generatePassword($length = 8)
  {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

  }
 
  // == send email function == //
/*function sendMail($to_address, $email_subject, $email_text, $email_type,$from_address, $attachment_file, $attachment_type) 
				{
					$header .= " \nMIME-Version: 1.0\n";
					$headers .= "X-Priority: 1\n"; // Urgent message!
				if($attachment_file!="")
				{
					$attachment_type=TypeOfAttachment($attachment_type);
					
					$content = fread(fopen($attachment_file,"r"),filesize($attachment_file));
					$content = chunk_split(base64_encode($content));
					$uid = strtoupper(md5(uniqid(time())));
					$name = basename($attachment_file);
					$header .= "Content-Type: multipart/mixed; boundary=$uid\n";
					$header .= "--$uid\n";
				}
				if($email_type==0)
			   {
					$header .= "Content-Type: text/plain\n";
					$header .= "Content-Transfer-Encoding: 8bit\n\n";
					$header .= strip_tags($email_text)."\n";
				}
				else 
				{
					$header .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type
					$header .= "Content-Transfer-Encoding: 8bit\n\n";
					$header .= "$email_text\n";
			//	 	echo "<br>TYPE : $email_type ";
				}
				  if($uid!="") $header .= "--$uid\n";
				   
				if($attachment_file!="")
				{
					$header .= "Content-Type: $attachment_type; name=\"$name\"\n";
					$header .= "Content-Transfer-Encoding: base64\n";
					$header .= "Content-Disposition: attachment; filename=\"$name\"\n\n";
					$header .= "$content\n";
			//		echo "Att: $attachment_type";
				}
			   if(mail($to_address, $email_subject, "", $header)) return true;
			   else return false;
}*/
// this function copies a file from a folder to another
/*
   copies everything from directory $fromDir to directory $toDir
   and sets up files mode $chmod
*/
function copydirr($fromDir,$toDir)
{
	if(file_exists($fromDir))
       $res = copy($fromDir,$toDir);
	  // unlink($fromDir);
	   return $res;
}
function uploadImage_createThumb($file_id,$destination,$thumb_destination,$name_image)
{
 define ("MAX_SIZE","4000");
 $errors=0; 	
 $image =$_FILES[$file_id]["name"];
 $uploadedfile = $_FILES[$file_id]['tmp_name'];
  if ($image) 
  {
    $filename = stripslashes($_FILES[$file_id]['name']);
    $extension = getExtension($filename);
    $extension = strtolower($extension);
    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
     {
      // echo ' Unknown Image extension ';
       $errors=1;
	   return array("","");
     }
    else
    {
     $size=filesize($_FILES[$file_id]['tmp_name']); 
     if ($size > MAX_SIZE*1024)
     {
      // echo "You have exceeded the size limit";
       $errors=1;
     }
 
	  if($extension=="jpg" || $extension=="jpeg" )
	  {
	  $uploadedfile = $_FILES[$file_id]['tmp_name'];
	  $src = imagecreatefromjpeg($uploadedfile);
	  }
	  else if($extension=="png")
	  {
	  $uploadedfile = $_FILES[$file_id]['tmp_name'];
	  $src = imagecreatefrompng($uploadedfile);
	  }
	  else 
	  {
	  $src = imagecreatefromgif($uploadedfile);
	  }
 
	  list($width,$height)=getimagesize($uploadedfile);
	  
	  $newwidth=520;
	  $newheight=($height/$width)*$newwidth;
	  $tmp=imagecreatetruecolor($newwidth,$newheight);
	  
	  $newwidth1=120;
	  $newheight1=($height/$width)*$newwidth1;
	  $tmp1=imagecreatetruecolor($newwidth1,$newheight1);
	  
	  imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	  
	  imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1, 
	  
	  $width,$height);
	  
	  $filename = $destination. $name_image.".".$extension;
	  $filename1 = $thumb_destination. "thumb".$name_image.".".$extension;
	  
	  imagejpeg($tmp,$filename,100);
	  imagejpeg($tmp1,$filename1,100);
	  
	  imagedestroy($src);
	  imagedestroy($tmp);
	  imagedestroy($tmp1);
	  return array($name_image.".".$extension,"thumb".$name_image.".".$extension);
   }
  }

}
// this function explode text that was contained in string format to be output in array form using array of delimiters 
function explodeX($delimiters,$string)
{
 
	$return_array = Array($string); // The array to return
	 
	$d_count = 0;
	 
	while (isset($delimiters[$d_count])) // Loop to loop through all delimiters
	 
	{
	 
	$new_return_array = Array();
	 
	foreach($return_array as $el_to_split) // Explode all returned elements by the next delimiter
	 
	{
	 
	$put_in_new_return_array = explode($delimiters[$d_count],$el_to_split);
	 
	foreach($put_in_new_return_array as $substr) // Put all the exploded elements in array to return
	 
	{
	 
	$new_return_array[] = $substr;
	 
	}
	 
	}
	 
	$return_array = $new_return_array; // Replace the previous return array by the next version
	 
	$d_count++;
	 
	}
	 
	return $return_array; // Return the exploded elements 
}

//****************************

/*function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}*/
function isValidEmail($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$/",
$domain_array[$i])) {
        return false;
      }
    }
  }
// Check if mail exchange exists.
if (!getmxrr($email_array[1], $mxrecords))
return false;
  
  return true;
}  

function createTinyUrl($strURL) {
    $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$strURL);
    return $tinyurl;
}

?>