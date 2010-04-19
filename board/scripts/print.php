<?php
/****************************************
* XMB v1.0 (XmlMessageBoard)
* http://xmboard.sourceforge.net
*****************************************
* written by erwin vrolijk,
* aierwin@users.sourceforge.net
*****************************************
* this script is released under the GPL
* http://www.gnu.org/copyleft/gpl.html
****************************************/

/* print.php

   requires conf.php

this file containts print functions
*/

//set global array 
$temp;

/* print_from_file($file)
   string $file will be opened en be print out,
   returns false if $file cannot be opened.
*/
function print_from_file($file) {
	$infile = @fopen ($file, "r");
	if (!$infile) {
		return false;
	}
	
	while(!feof($infile)) {
		$line = fgets($infile, 4096); 
		print($line);
	}
	fclose($infile);
}

/* print_xml_starttag($tag, $attrs)
   string $tag
   array $attrs

   transforms XML to HTML and prints
*/
function print_xml_starttag($tag, $attrs) {
	global $temp;

	if ($tag == "MESSAGE") {
		print("<p id='boardContent'>");
	}
	
	if ($tag == "IDENTIFIER") {
		print("<div id='boardDate'>");
	}

	if ($tag == "AUTHOR") {
		if ($attrs[EMAIL] != "") {
			print("<div id='boardAuthor'>[[");
			// remember we have opened a <A> tag so we can clos it later
			$temp[OPEN_A_TAG] = $attrs[EMAIL];
		}else{
			print("<div id='boardAuthor>");
		}
	}

	if ($tag == "TEXT") { 
		// remember we are in the TEXT-tag
	        print("<div id='boardText'>");
		$temp[IN_TEXT_TAG] = true;
	}
}
	
/* print_xml_endtag($tag, $attrs)
   string $tag
   array $attrs

   transforms XML to HTML and prints
*/	
function print_xml_endtag($tag, $attrs) {
	global $temp;


	if ($tag == "MESSAGE") {
		print("</p>");
	}
	
	if ($tag == "IDENTIFIER") {
		print("</div>");
	}

	if ($tag == "AUTHOR") {
		if (isset($temp[OPEN_A_TAG])) {
			print("| $temp[OPEN_A_TAG] ]]</div>");
			unset($temp[OPEN_A_TAG]);
		}else{
			print("</div>");
		}
	}

	if ($tag == "TEXT") {
		print("</div>");
		unset($temp[IN_TEXT_TAG]);
	}
}


/* print_xml_characterdata($data)
   string $data

   formats and prints $data
*/	
function print_xml_characterdata($data) {
	global $temp;
	global $OUTPUT_LINE_WIDTH;

	if (isset($temp[IN_TEXT_TAG])) {
		print(wordwrap(nl2br($data), $OUTPUT_LINE_WIDTH, '<BR>', 1));
	} else {
		print($data);
	}
}

/* print_redirect($location)

   prints HTML-headers to redirect browser to $location
*/
function print_redirect($location) {
	header ("Location: ".$location);
	exit;
}

/* print_message_navigation($start, $messages_per_page)

   prints line which indicates which messages are displayed,
   and generate links to nex and previous pages
*/
function print_message_navigation($start, $messages_per_page){
	global $BASEFILE;

	print("<P>message ".$start." to ".($start+$messages_per_page)." ");
	if($start > 1) {
		print("<a href='".$BASEFILE."?start=".($start-$messages_per_page)."'>&lt;- newer</a> ");
	}
	print("<a href='".$BASEFILE."?start=".($start+$messages_per_page)."'>older -&gt;</a></P>");
}
?>
