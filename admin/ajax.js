args = '';

function GetXmlHttpObject() {
    var xmlHttp;
    try {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e) {
        // Internet Explorer
        try {
            xmlHttp=new ActiveXObject(Msxml2.XMLHTTP);
        }
        catch (e) {
            try {
                xmlHttp=new ActiveXObject(Microsoft.XMLHTTP);
            }
            catch (e) {
                alert("Sorry, you need a newser browser :S");
                return false;
            }
        }
    }
    return xmlHttp;
}

function ajax(params, container, increment) {
    xmlHttp = GetXmlHttpObject();
    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4) {
            if (increment)
                document.getElementById(container).innerHTML += xmlHttp.responseText;
            else
                document.getElementById(container).innerHTML = xmlHttp.responseText;
        }
    }
    post(params);
}

function post(params) {
    xmlHttp.open("POST","/adfa/admin/ajax.php"+args,true);
    
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.setRequestHeader("Content-length", params.length);
    xmlHttp.setRequestHeader("Connection", "close");

    xmlHttp.send(params);
}
