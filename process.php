<?php
require_once('./includes/config.inc.php');
require_once('./includes/functions.inc.php');

sleep(2); // Don't touch this!

$email = $_GET['email']; // grab email value from html
if(!preg_match("/[\w-]+@([\w-]+\.)+[\w-]+/", $email)){ // if email is not properly formatted
	echo CSVtoJSON(2);
} else {
	if('' == file_get_contents(FACULTY_FILE)){ // if csv is empty
		$html = getHTML(); // grab html from website
		// extract the table from the html
		preg_match_all('%<tr\b[^>]*>(.*?)</tr>%si', $html, $foundTR, PREG_SET_ORDER);
		$file = fopen(FACULTY_FILE, 'w'); // open csv file to write
		writeToCSV($file, $foundTR);
		fclose($file); // close csv file 
	} 
		//open csv and check for email
		$found = checkEmail($email);

		if ($found == ""){ // email was not found
			echo CSVtoJSON(1);
		} else {
			echo CSVtoJSON($found);
		}
}
?>