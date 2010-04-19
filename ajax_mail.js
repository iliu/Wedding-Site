
var xmlHttp;
var text;
var textb;
var areq;

function importXMLDoc() {

    if ( document.getElementById("boardstatus")  ) {
        if ( document.getElementById("boardstatus").hasChildNodes() )
            {
                document.getElementById("boardstatus").removeChild(textb);
            }
        textb=document.createTextNode("Refreshing board....");
        document.getElementById("boardstatus").appendChild(textb);
    }
    areq=GetXmlHttpObject();
    if (areq==null)
        {
        if (document.getElementById("boardstatus")  ) {
            if (  document.getElementById("boardstatus").hasChildNodes() )
                {
                    document.getElementById("boardstatus").removeChild(textb);
                }
            textb=document.createTextNode("Your browser doesn't support this script. Please use IE/firefox/Safari.");
            document.getElementById("boardstatus").appendChild(textb);
	} 
      }
      else
      {
        areq.onreadystatechange = processReqChange;
        areq.open("GET", "board/board.php", true);
        areq.send(null);


      }
}


// handle onreadystatechange event of req object
function processReqChange() {
    // only if req shows "loaded"
    if (areq.readyState == 4) {
        // only if "OK"
        if (areq.status == 200) {
            createMain();
            if (document.getElementById("boardstatus") ) {
                if (  document.getElementById("boardstatus").hasChildNodes() )
                    {
                        document.getElementById("boardstatus").removeChild(textb);
                    }
		var currentTime = new Date();
		var month = currentTime.getMonth() + 1;
		var year = currentTime.getFullYear();
		var day = currentTime.getDate(); 
		var min = currentTime.getMinutes();
		var sec = currentTime.getSeconds();
		var hour = currentTime.getHours();
		var ampm;
		if (min < 10)
			min = "0"+min;
		if (sec < 10 )
			sec = "0"+sec;
		if ( hour <= 12 ){
			ampm = "am";
                        if (hour == 0)
				hour = 12;
		}
		else{
			ampm = "pm";
			hour = hour - 12;
		}	
                textb=document.createTextNode(month+"/"+day+" "+hour+":"+min+":"+sec+" "+ampm);
                document.getElementById("boardstatus").appendChild(textb);
            }
        } else {
            alert("There was a problem retrieving the XML data:\n" +
                  areq.statusText);
        }
    }
}
 
function createMain()
{
	var xb = areq.responseXML.getElementsByTagName('MESSAGE');
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

    if ( document.getElementById('maincontent')) {
        if ( document.getElementById('maincontent').hasChildNodes() ) {
            document.getElementById('maincontent').replaceChild(para, document.getElementById('maincontent').childNodes[0]);	
        }
        else
            document.getElementById('maincontent').appendChild(para);	
    }
}



    
function mailform()
{
    xmlHttp=GetXmlHttpObject();
    if (xmlHttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return
                } 
    if ( document.getElementById("formstatus") ) {
        if (  document.getElementById("formstatus").hasChildNodes() )
            {
                document.getElementById("formstatus").removeChild(text);
            }
        text=document.createTextNode("Sending....");
        document.getElementById("formstatus").appendChild(text);
    }
                    
    var name = document.email_form.yourname.value;
    var email= document.email_form.email_address.value;
    var comments = document.email_form.comments.value;
    var url ="board/scripts/add.php";
    url= url+"?naam="+name+"&email="+email+"&tekst="+comments;	
    xmlHttp.onreadystatechange=stateChanged;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
} 
                                                        
                                                            
function stateChanged() 
{ 
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
        { 
            if (xmlHttp.status==200)
                {
                    document.email_form.yourname.value="";
                    document.email_form.email_address.value="";
                    document.email_form.comments.value="";
                    if ( document.getElementById("formstatus") ) {
                        if ( document.getElementById("formstatus").hasChildNodes() )
                            {
                                document.getElementById("formstatus").removeChild(text);
                            }
                        text=document.createTextNode(xmlHttp.responseText);
                        document.getElementById("formstatus").appendChild(text);
                    }
                }
        } 
    importXMLDoc();
} 
    
function GetXmlHttpObject() { 
    var objXMLHttp=null;
    if (window.XMLHttpRequest)
        {
            objXMLHttp=new XMLHttpRequest();
        }
    else if (window.ActiveXObject)
        {
            objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");

        }
    return objXMLHttp;
} 

importXMLDoc();
