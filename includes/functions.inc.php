<?php
function getHTML(){ 
	$curl = curl_init(FACULTY_WEB); // grab html from site
	curl_setopt($curl, CURLOPT_HEADER, 0); # dont include the header
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // returns the transfer data
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'); // fake the user agent header
	$html = curl_exec($curl); // read in
	curl_close($curl);
	return $html;
} 

function CSVtoJSON($check){
	switch ($check){ // switch chosen as if more error types were introduced, then it can expand
    	case 1: // 1: no error, not found email
    		$temp[0] = $check;
    		break;
    	case 2: // 2: error, bad email formatting
    		$temp[0] = $check;
    		break;
    	default: // no error, found email
			$line = preg_replace('/"/','$1',$check);
			$line = preg_replace('/.\n/','$1',$line);	
			$temp = explode(", ", $line);
    		break;
    }
	return json_encode($temp);
}


function checkEmail($email){
	$file = fopen(FACULTY_FILE, 'r'); // open csv file to write
	$found = "";
	while (!feof($file)) {
	   $line = fgets($file);
	   if(strpos($line, $email) !== false){
	   		$found = $line;
	   }
	}
	fclose($file); // close csv file 
	return $found;
}

function writeToCSV($file, $foundTR){
	for($i = 1; $i < count($foundTR); $i++){ // for each table section
		preg_match_all("/(?<=>)[^><]+?(?=<)/", $foundTR[$i][1], $tempArray); // remove html tags
		for ($p = 4; $p < 7; $p++) // replace * with email
			$tempArray[0][$p] = preg_replace("/\*/", "@uwo.ca", $tempArray[0][$p].trim()); 

		$tempString = "";
		for ($p = 0; $p < 7; $p++){ // for each element of the input array
			$cur = $tempArray[0][$p]; // current element in the for loop
			$cur = trim($cur); // remove new lines
			if ($p < 2){ // switch name placement
				$tempNames = explode(" Dr. ", $cur); // use dr. as reference point for breakup
				$cur = "Dr. " . $tempNames[1] . " " . $tempNames[0]; // reorder name set
				$cur = preg_replace('/(.*),/','$1',$cur); // remove trailing comma
			}

			$cur = preg_replace("/&amp;/","&",$cur); // convert andpersand
			$cur = preg_replace("/,/"," com;",$cur); // convert comma
			$cur = htmlspecialchars($cur); // quotes and andpersands are setup


			$testDr = stripos($cur, "Dr.  "); // remove name brackets

			if ($cur != " " && $cur != "" && $cur != "" && $testDr === false)  
				$tempString .= "\"". $cur . "\", "; // format set for csv
		}
		$tempString = preg_replace('/(.*),/','$1', $tempString); // remove trailing comma
		// "name", "spec", "email"
		$tempEle = explode(", ", $tempString);
		$tempString = $tempEle[2] . ", " . $tempEle[0] . ", " . $tempEle[1];
		// "email", "name", "spec"
		fwrite($file, $tempString . "\n"); // write current set to csv file
	}
}

?>