<?php

/**
 * A function for easily uploading files. This function will automatically generate a new 
 *        file name so that files are not overwritten.
 * Taken From: http://www.bin-co.com/php/scripts/upload_function/
 * Arguments:    $file_id- The name of the input field contianing the file.
 *                $folder    - The folder to which the file should be uploaded to - it must be writable. OPTIONAL
 *                $types    - A list of comma(,) seperated extensions that can be uploaded. If it is empty, anything goes OPTIONAL
 * Returns  : This is somewhat complicated - this function returns an array with two values...
 *                The first element is randomly generated filename to which the file was uploaded to.
 *                The second element is the status - if the upload failed, it will be 'Error : Cannot upload the file 'name.txt'.' or something like that
 */
 
function upload($file_id, $folder="", $types="", $fname="") {
    if(!$_FILES[$file_id]['name']) return array('','No file specified');
	
	//$result = '';

    $file_title = $_FILES[$file_id]['name'];
    //Get file extension
   // $ext_arr = split("\.",basename($file_title));
    //$ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
	$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $file_title);
	if(!$fname)
	{
		//Not really uniqe - but for all practical reasons, it is
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . $file_title;//Get Unique Name
	}
	else
	{
		$file_name = $fname.".".$ext;
	}
	//echo $file_name;
    $all_types = explode(",",strtolower($types));
    if($types) {
        if(in_array($ext,$all_types));
        else {
            $result = "'".$_FILES[$file_id]['name']."' is not a valid file."; //Show error if any.
            return array('',$result);
        }
    }

    //Where the file must be uploaded to
    if($folder) $folder .= '/'; //Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    //$result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
        $result = "Cannot upload the file '".$_FILES[$file_id]['name']."'"; //Show error if any.
        if(!file_exists($folder)) {
            $result .= " : Folder don't exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } elseif(!is_writable($uploadfile)) {
            $result .= " : File not writable.";
        }
        $file_name = '';
        
    } else {
        if(!$_FILES[$file_id]['size']) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = "Empty file found - please use a valid file."; //Show the error message
        } else {
			$result = "File Upload SuccessFull.";
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }
//echo "result : ".$result;
    return array($file_name,$result);
}

//////////FUNCTION TO UPLOAD THE THUMB IMAGE /////////////////

///////////////////////////////////upload($file_id, $folder="", $types="", $fname="")
function uploadimage($file_id, $destname, $check, $type, $path)
{
		$UploadThumpPath=$path;
		echo "destination name: ".$destname.", Path: ".$UploadThumpPath;
		//path where to store images
		
//$path_thumbs = UploadThumpPath;
//$img_thumb_width = 160; // in pixcel

		if($type=="t")
		{
			$path_thumbs = $path;
			$img_thumb_width = 160; // in pixcel
		}
		else if($type=="b")
		{
			$path_thumbs = $path;
			$img_thumb_width = 246; // in pixcel
		}  
		
		$max_size = 1024000;
		//the new width of the resized image.		
		$extlimit = "yes"; //Do you want to limit the extensions of files uploaded (yes/no)
		//allowed Extensions
		$limitedext = array(".gif",".jpg",".png",".jpeg");//,".bmp");
		//check if folders are Writable or not
		//please CHOMD them 777
		if (!is_writeable($path_thumbs))
		{
		   die ("Error: The directory <b>($path_thumbs)</b> is NOT writable");
		}
		//end of configpart
		//size check
		if($_FILES[$file_id]['size'] > $max_size)
		{
				return "Image File size is too big";
		}
     	  $file_type = $_FILES[$file_id]['type'];
       	  $file_name = $_FILES[$file_id]['name'];
      	  $file_size = $_FILES[$file_id]['size'];
      	  $file_tmp = $_FILES[$file_id]['tmp_name'];
		    //check if you have selected a file.
    	  if(!is_uploaded_file($file_tmp))
	   		{
          return "Error: Please select a file to upload!";
           //exit(); //exit the script and don't do anything else.
       }
       //check file extension
       $ext = strrchr($file_name,'.');
       $ext = strtolower($ext);
       if (($extlimit == "yes") && (!in_array($ext,$limitedext))) {
          return "Wrong file extension.";
         // exit();
       }
       //get the file extension.
       $getExt = explode ('.', $file_name);
       $file_ext = $getExt[count($getExt)-1];

       //create a random file name
       $rand_name = md5(time());
       $rand_name= rand(0,999999999);
       //get the new width variable.
       $ThumbWidth = $img_thumb_width;

       //keep image type
       if($file_size){
          if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
               $new_img = imagecreatefromjpeg($file_tmp);
           }elseif($file_type == "image/x-png" || $file_type == "image/png"){
               $new_img = imagecreatefrompng($file_tmp);
           }elseif($file_type == "image/gif"){
               $new_img = imagecreatefromgif($file_tmp);
           }
           //list width and height and keep height ratio.
           list($width, $height) = getimagesize($file_tmp);
           $imgratio=$width/$height;
           if ($imgratio>1){
              $newwidth = $ThumbWidth;
              $newheight = $ThumbWidth/$imgratio;
           }else{
                 $newheight = $ThumbWidth;
                 $newwidth = $ThumbWidth*$imgratio;
           }
           //function for resize image.
           if (function_exists(imagecreatetruecolor)){
           $resized_img = imagecreatetruecolor($newwidth,$newheight);
           }else{
                return("Error: Please make sure you have GD library ver 2+");
           }
           imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
           if($check)
		   {
				   	return "";
		   }
		   else
		   {
		   //save image
          $pic_uploaded=true;
		   $pic_uploaded=$pic_uploaded & ImageJpeg ($resized_img,"$path_thumbs/$destname.$file_ext");
           if ($pic_uploaded==false)
			{
				return "can't upload file. Please contact admin";
			}
		   ImageDestroy ($resized_img);
           ImageDestroy ($new_img);

        }
      	$GLOBALS['file_ext']= $file_ext;
		if ($pic_uploaded==false)
		{
			return "can't upload file. Please contact admin";
		}
		else
		{
			return "Product Image Upoad Successful";
		}
		//End part of uploading image
	}
}
///////////////////////END OF FUNCTION uploadimage_createthumb ///////////////////////

function upload_Image($file_id, $destname, $max_size, $upload_dir)
{
	$uploadStatus = false;
	// define a constant for the maximum upload size
	//define ('MAX_FILE_SIZE', 1024 * 50);
	define ('MAX_FILE_SIZE', $max_size);
	
	//if (array_key_exists('upload', $_POST)) {
	  // define constant for upload folder
	  define('UPLOAD_DIR', $upload_dir);
	  
	  // replace any spaces in original filename with underscores
	  //$file = str_replace(' ', '_', $_FILES[$file_id]['name']);
	  $file = $destname;
	  
	  // create an array of permitted MIME types
	  $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
	  
	  // upload if file is OK
	  if (in_array($_FILES[$file_id]['type'], $permitted)
		  && $_FILES[$file_id]['size'] > 0 
		  && $_FILES[$file_id]['size'] <= MAX_FILE_SIZE) {
		switch($_FILES[$file_id]['error']) {
		  case 0:
			// check if a file of the same name has been uploaded
			if (!file_exists(UPLOAD_DIR . $file)) {
			  // move the file to the upload folder and rename it
			  $success = move_uploaded_file($_FILES[$file_id]['tmp_name'], UPLOAD_DIR . $file);
			} else {
			  $result = 'A file of the same name already exists.';
			}
			if ($success) {
				$uploadStatus = true;
			  $result = "$file uploaded successfully.";
			} else {
			  $result = "Error uploading $file. Please try again.";
			}
			break;
		  case 3:
		  case 6:
		  case 7:
		  case 8:
			$result = "Error uploading $file. Please try again.";
			break;
		  case 4:
			$result = "You didn't select a file to be uploaded.";
		}
	  } else {
		$result = "$file is either too big or not an image.";
	  }
	  //return $result;
	  return array($uploadStatus,$result);
	//}  //Closing of if (array_key_exists('upload', $_POST)) {
}

function upload_resizeImage($file_id, $destname, $max_w, $max_h, $upload_dir)
{$uploadStatus = false;
	 ini_set("memory_limit", "200000000"); // for large images so that we do not get "Allowed memory exhausted"
	
	// file needs to be jpg,gif,bmp,x-png and 4 MB max
	if (($_FILES[$file_id]["type"] == "image/jpeg" || $_FILES[$file_id]["type"] == "image/pjpeg" || $_FILES[$file_id]["type"] == "image/gif" || $_FILES[$file_id]["type"] == "image/x-png") && ($_FILES[$file_id]["size"] < 4000000))
	{
		
  
		// some settings
		$max_upload_width = 2592;
		$max_upload_height = 1944;
		  
		// if user chosed properly then scale down the image according to user preferances
		if(isset($max_w) and $max_w!='' and $max_w<=$max_upload_width){
			$max_upload_width = $max_w;
		}    
		if(isset($max_h) and $max_h!='' and $max_h<=$max_upload_height){
			$max_upload_height = $max_h;
		}	

		
		// if uploaded image was JPG/JPEG
		if($_FILES[$file_id]["type"] == "image/jpeg" || $_FILES[$file_id]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES[$file_id]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES[$file_id]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES[$file_id]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES[$file_id]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES[$file_id]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES[$file_id]["type"] == "image/x-png"){
			$image_source = imagecreatefrompng($_FILES[$file_id]["tmp_name"]);
		}
		

		//$remote_file = "image_files/".$_FILES[$file_id]["name"];
		$remote_file = $upload_dir.$destname;
		imagejpeg($image_source,$remote_file,100);
		chmod($remote_file,0644);
	
	

		// get width and height of original image
		list($image_width, $image_height) = getimagesize($remote_file);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			$proportions = $image_width/$image_height;
			
			if($image_width>$image_height){
				$new_width = $max_upload_width;
				$new_height = round($max_upload_width/$proportions);
			}		
			else{
				$new_height = $max_upload_height;
				$new_width = round($max_upload_height*$proportions);
			}		
			
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,100);
			
			imagedestroy($new_image);
		}
		
		imagedestroy($image_source);
		
		$uploadStatus = true;
		//header("Location: submit.php?upload_message=image uploaded&upload_message_type=success&show_image=".$_FILES[$file_id]["name"]);
		$result = "Image uploaded";
		//exit;
	}
	else{
		$result = "Make sure the file is jpg, gif or png and that is smaller than 4MB";
		//exit;	
	}
	return array($uploadStatus, $result);
}
?>