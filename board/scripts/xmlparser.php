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
/* xmlparser.php

   requires print.php
*/

$message_id    = 0;
$START_MESSAGE = 0;
$END_MESSAGE   = 0;

/* parse_xml($file)
   string $file will be opened and parsed
*/
function parse_xml($file) {
	if (!($fp = @fopen($file, "r"))) {
		die("/scripts/xmlparser.php function:parse_xml could not open XML input");
	}
	
	$xml_parser = xml_parser_create();
	// use case-folding so we are sure to find the tag
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
	xml_set_element_handler($xml_parser, "start_element", "end_element");
	xml_set_character_data_handler($xml_parser, "character_data");

	while ($data = fread($fp, 4096)) {
		if (!xml_parse($xml_parser, $data, feof($fp))) {
			die(sprintf("XML error: %s at line %d",
				     xml_error_string(xml_get_error_code($xml_parser)),
				     xml_get_current_line_number($xml_parser)));
		}
	}
	
	xml_parser_free($xml_parser);
}

function start_element($parser, $name, $attrs) {
	print_xml_starttag($name, $attrs);
}

function end_element($parser, $name) {
	print_xml_endtag($name, $attrs);
}

function character_data($parser, $data) {
	print_xml_characterdata($data);
}

/*read_messages($file, $start_message, $number_of_messages)
  string $file
  int    $start_message
  int    $number_of_messages

  parses xml-file $file for messages, start output at $start_message,
  displays $number_of_messages including $start_message.

  last added message has id=1
*/
function read_messages($file, $start_message, $number_of_messages) {
	global $START_MESSAGE,
	       $END_MESSAGE;

	$START_MESSAGE = $start_message;
	$END_MESSAGE   = $start_message + $number_of_messages;	
	
	if (!($fp = @fopen($file, "r"))) {
		die("/scripts/xmlparser.php function:read_messages could not open XML input");
	}
	
	$xml_parser = xml_parser_create();
	// use case-folding so we are sure to find the tag
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
	xml_set_element_handler($xml_parser, "start_element2", "end_element2");
	xml_set_character_data_handler($xml_parser, "character_data2");

	while ($data = fread($fp, 4096)) {
		if (!xml_parse($xml_parser, $data, feof($fp))) {
			die(sprintf("XML error: %s at line %d",
				     xml_error_string(xml_get_error_code($xml_parser)),
				     xml_get_current_line_number($xml_parser)));
		}
	}
	
	xml_parser_free($xml_parser);
}

function start_element2($parser, $name, $attrs) {
	global $message_id,
	       $START_MESSAGE,
	       $END_MESSAGE;
	
	if ($name == "MESSAGE") {
		$message_id++;
	}
	if ($START_MESSAGE <= $message_id && $message_id <= $END_MESSAGE) {
		print_xml_starttag($name, $attrs);
	}

}

function end_element2($parser, $name) {
	global $message_id,
	       $START_MESSAGE,
	       $END_MESSAGE;
	
	if ($START_MESSAGE <= $message_id && $message_id < $END_MESSAGE) {
		print_xml_endtag($name, $attrs);
	}
}

function character_data2($parser, $data) {
	global $message_id,
	       $START_MESSAGE,
	       $END_MESSAGE;
	
	if ($START_MESSAGE <= $message_id && $message_id < $END_MESSAGE) {
		print_xml_characterdata($data);
	}
}


?>
