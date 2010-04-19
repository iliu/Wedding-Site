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
	$OUTPUT_LINE_WIDTH      = 50;
	$MESSAGES_PER_PAGE      = 20;

	$BASEFILE               = "../../board.html";
 	$XML_INPUTFILE          = "./data/board.xml";
	$XML_OUTPUTFILE         = "./data/board.xml.out";

	$EMAIL_WEBMASTER        = "true"; //if 'true'_the script mails a copy of every writeup to $EMAILADDRESS_WEBMASTER
	$EMAILADDRESS_WEBMASTER = "liu.isaac@gmail.com";
 
    $LOCALE                 = "en_US" //for english/american date format
//  $LOCALE                 = "nl_NL" //for dutch date format
?>
