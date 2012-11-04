<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="en-US" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Jonathan Demelo | CS3336 HW3</title>
<link rel="stylesheet" href="./css/hw3.css" type="text/css" media="screen" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var search = null;

		$('#button').click(function(){
			$('#loading').show();
			var inputVal = $('#emailfield').val();

		   	$('#headers').hide();
    		$('#name').hide();
	  		$('#spec').hide();
	  		$('#email').hide();
	  		$('#error').hide();

			inputVal.replace(/[^a-z]/gi,"");
			if(search != null)
				search.abort();
			search = $.get(
			  	"process.php", 
			  	{email: inputVal}, 
			  	function(result){
			  		$('#loading').hide();
			    	var response = jQuery.parseJSON(result);
			    	if(response == 1){ // email not found
			    		$('#error').show();
			    		$('#error').text("Email not found!");
			    	} else if (response == 2){ // invalid email format
			    		$('#error').show();
			    		$('#error').text("Invalid Email address!");
			    	} else { // found email
			    		$('#error').hide();
			    		var temp = String(response).split(',');
				//<![CDATA[
				    	temp[1] = temp[1].replace(/&quot;/g, '"');
				    	temp[1] = temp[1].replace(/&amp;/g, '&');
				    	temp[2] = temp[2].replace(/&quot;/g, '"');
				    	temp[2] = temp[2].replace(/&amp;/g, '&');
				    	temp[2] = temp[2].replace(/ com;/g, ',');
				//]]>
						$("#name").text(temp[1]);
						$("#spec").text(temp[2]);
			    		$("#email").text(temp[0]);
			    		$('#headers').show();
			    		$('#name').show();
				  		$('#spec').show();
				  		$('#email').show();
			    	}
			    }
			);
		});
	});
</script>

</head>

<body>
<div id="mainbody">
	<div id="leftside">
		<div id="leftText">Email:</div> 
		<input id="emailfield" type="text"/>
		<input id="button" type="button" value="Find"/>	
	</div>
	<div id="rightside">
		<img id="loading" src="./images/loading.gif" alt="LOADING"/>
		<div id="headers">Name: <br />Area: <br />Email: <br /></div>
		<div id="info">
			<div id="error"></div>
			<div id="name"></div>
			<div id="spec"></div>
			<div id="email"></div>
		</div>
	</div>
</div>
</body>
</html>