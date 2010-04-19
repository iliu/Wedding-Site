<?php
/****************************************
* XMB v1.0 (XmlMessageBoard)
*****************************************
* written by erwin vrolijk,
* erwin@redant.nl
*****************************************
* this script is released under the GPL
* http://www.gnu.org/copyleft/gpl.html
****************************************/

require("./scripts/conf.php");
require("./scripts/print.php");
require("./scripts/xmlparser.php");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Content-Type: text/xml; charset=utf-8");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($start)) {
	$start = 1;
}

print_from_file("./data/board.xml");

?>
