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

/* add.php

   requires conf.php
   requires print.php
*/

require("./conf.php");
require("./print.php");
/*
require_once('recaptchalib.php');
$privatekey = "6LdlXAAAAAAAAFpb741ZeEgK-ni7xvrNfXH5yNNa";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
  die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
       "(reCAPTCHA said: " . $resp->error . ")");
}
*/

$naam = $_GET['naam'];
$email = $_GET['email'];
$tekst = $_GET['tekst'];
$val = $_GET['validate'];
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

$banlist = array("81.95.", "91.76.14", "85.140.23", "24.136.65.148", "87.118.106.41", "140.127.139.", "17.149.4.", "80.58.205.", "84.19.176.", "69.147.76", "204.39.95", "203.160.1.", "76.197.155", "200.21.238", "67.175.139", "208.179.21.", "125.46.61.", "210.176.2.", "122.203.23.");

for ($i = 0; $i < sizeof($banlist); $i++)
{
	if (strstr($REMOTE_ADDR, $banlist[$i]))
		die ("You have been banned from posting!");
}

// Check required fields
if ($naam  == "" ||
    $tekst == "") {
	die ("please fill out all fields");
}

if ($email)
if (!is_valid_email($email)) {
    die ("$email is not a valid emailaddress");
}

$naam  = htmlspecialchars(strip_tags(stripslashes($naam)));
$email = htmlspecialchars(strip_tags(stripslashes($email)));
$tekst = htmlspecialchars(strip_tags(stripslashes($tekst)));

add_message($XML_INPUTFILE, $naam, $email, $tekst);

if ($EMAIL_WEBMASTER == "true") { 
	$message = @fdate()." ip: ".$REMOTE_ADDR."\nAuteur: ".$naam."\n".$tekst; 
	mail($EMAILADDRESS_WEBMASTER, "XMLboard addition to wedding site" , $message, "From: ".$email);
}
echo "Message posted!";

/* add_message($infile, $author, $email, $text)
   all args are string.

   adds a new message to the spcified xml file.
*/

function add_message($file, $author, $email, $text) {
	global $REMOTE_ADDR,
           $LOCALE;

	$infile  = fopen("../".$file, "r");
    if(file_exists("../".$file.".out")) {
        die ("filelocking problem, please try again");
    }
	$outfile = fopen("../".$file.".out", "w");
	

	while(!feof($infile)) {
		$line = fgets($infile, 4096); 
		if (!isset($MESSAGE_ADDED) & strtoupper(substr(ltrim($line),0,9)) ==  "<MESSAGE>") {
			fputs($outfile, "<MESSAGE>\n");
			fputs($outfile, "<identifier>(" .@fdate($LOCALE).")</identifier>\n");
			fputs($outfile, "<author email='" .$email. "'>" .$author. "</author>\n");
			fputs($outfile, "<text>" .$text. "</text>\n");
			fputs($outfile, "</MESSAGE>\n\n"); 
			fputs($outfile, $line);

			$MESSAGE_ADDED = true;
		} else {
			fputs($outfile, $line);
		}
	}
	
	fclose($outfile);
	fclose($infile);
	copy("../".$file.".out", "../".$file);
    unlink("../".$file.".out");
}

function fdate($country){
	if ($country == "") {
		$country = "en_US";
	}
	setlocale("LC_ALL", $country); 
	$time = strtolower(strftime("%d %B %Y, %H.%M"));
	return $time;
}

function is_valid_email ($address) {
    return (preg_match(
        '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.   // the user name
        '@'.                                     // the ubiquitous at-sign
        '([-0-9A-Z]+\.)+' .                      // host, sub-, and domain names
        '([0-9A-Z]){2,4}$/i',                    // top-level domain (TLD)
        trim($address)));
}
?>
