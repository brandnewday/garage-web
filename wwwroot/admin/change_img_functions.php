<?php
/**
 * @args img_name	the original file name
 *       img_tmp	full temp path name of the uploaded file
 *       img_path	full dest path to put the file in
 **/
function upload_no_overwrite($img_name, $img_tmp, $img_path)
{
	chdir($img_path);

	$img_dest_name = "";
	preg_match("/(.*)(\..*)/", $img_name, $match_results);
	$img_name_no_ext = $match_results[1];
	$img_ext = $match_results[2];

	// if uploaded file has the same name as an existing file, add a suffix so it 
	// would not overwrite the existing one.
	if(file_exists($img_name))
	{
		$count = 1;
		$img_dest_name = $img_name_no_ext . "_$count" . $img_ext;

		// check again if the new file name already exists
		while(file_exists($img_dest_name))
		{
			++$count;
			$img_dest_name = $img_name_no_ext . "_$count" . $img_ext;
		}
	}
	else
		$img_dest_name = $img_name;

	// move file from tmp dir. do check
	if(move_uploaded_file($img_tmp, $img_dest_name))
	{
		if(file_exists($img_dest_name))
			// this is the only SUCCESSFUL branch 
			log_msg("Debug: File moved from $img_tmp to $img_dest_name");
		else
		{
			log_msg("Error: move_uploaded_file() succeeded, but $img_dest_name does not exist.");
			exit("Upload failed. Please retry. If failed after 2 attempts, please contact Web Admin");
		}
	}
	else
	{
		log_msg("Error: uploading file from $img_tmp to $img_dest_name");
		exit("Upload failed. Please retry. If failed after 2 attempts, please contact Web Admin");
	}

	return $img_dest_name;
}
?>