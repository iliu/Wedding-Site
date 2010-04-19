
var req;
function importXMLDoc() {
    // branch for native XMLHttpRequest object
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = processReqChange;
        req.open("GET", "board/data/board.xml", true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = processReqChange;
            req.open("GET", "board/data/board.xml", true);
            req.send();
        }
    }
}

// handle onreadystatechange event of req object
function processReqChange() {
    // only if req shows "loaded"
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200) {
            createMain();
         } else {
            alert("There was a problem retrieving the XML data:\n" +
                req.statusText);
         }
    }
}
 
function createMain()
{
	var xb = req.responseXML.getElementsByTagName('MESSAGE');
	var para = document.createElement('div');
        para.setAttribute('id', 'boardContent');
	var len = xb.length<20 ? xb.length:20;
        for (i=0; i<len; i++)
	{
	
		var j = 0;
		
		while (xb[i].childNodes[j].nodeType !=1) j++;
		var theDate = document.createTextNode(xb[i].childNodes[j].firstChild.nodeValue);
		j++;
		while (xb[i].childNodes[j].nodeType !=1) j++;
		var author = document.createTextNode(xb[i].childNodes[j].firstChild.nodeValue);
		var authemail = "mailto:";
		authemail += xb[i].childNodes[j].attributes.getNamedItem('email').nodeValue;
		j++;
		while (xb[i].childNodes[j].nodeType !=1) j++;
		var msgtext = document.createTextNode(xb[i].childNodes[j].firstChild.nodeValue);
		
		
		var newEl = document.createElement('TABLE');
		newEl.setAttribute('cellPadding',1);
		newEl.setAttribute('width', '95%');
		var tmp = document.createElement('TBODY');
		newEl.appendChild(tmp);
		para.appendChild(newEl);
		var row = document.createElement('TR');
		var container = document.createElement('TH');
		container.appendChild(author);
		row.appendChild(container);
		var container2 = document.createElement('TD');
		container2.appendChild(theDate);
                container2.setAttribute('id', 'msgBoardDate');
		container2.setAttribute('align', 'right');
		row.appendChild(container2);
		tmp.appendChild(row);

		var row2 = document.createElement('TR');
		var container3 = document.createElement('TD');
		container3.setAttribute('ColSpan', '2');
		container3.appendChild(msgtext);
		row2.appendChild(container3);
		tmp.appendChild(row2);
		para.appendChild(newEl);

        } 

        if ( document.getElementById('maincontent').hasChildNodes() ) {
                document.getElementById('maincontent').replaceChild(para, document.getElementById('maincontent').childNodes[0]);	
                
        }
        else
	        document.getElementById('maincontent').appendChild(para);	
}
importXMLDoc();
