var XMLRequest=Object();
XMLRequest.buildQuery = function (query) {
        var data="";
        var first="?";
        for (i in query) {
                data+=first+escape(i)+"="+escape(query[i]);
                first="&";
        }
        return data;
}
XMLRequest.GET = function (url, query, callback, fallback) {
        var xmlhttp=null;
        if (window.XMLHttpRequest) {
                xmlhttp=new XMLHttpRequest()
        } else if (window.ActiveXObject) {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
        }
        if (xmlhttp!=null) {
                xmlhttp.onreadystatechange=function () {
                        if (xmlhttp.readyState==4) {
                                if (xmlhttp.status==200) callback(xmlhttp);
                                else fallback(xmlhttp);
                        }
                }
                var data = XMLRequest.buildQuery(query);
                xmlhttp.open("GET",url+data,true);
                xmlhttp.send("");
        }else{
                alert("Your browser does not support XMLHTTP.")
        }
}
XMLRequest.POST = function (url, query, form, callback, fallback) {
        var xmlhttp=null;
        if (window.XMLHttpRequest) {
                xmlhttp=new XMLHttpRequest()
        } else if (window.ActiveXObject) {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
        }
        if (xmlhttp!=null) {
                xmlhttp.onreadystatechange=function () {
                        if (xmlhttp.readyState==4) {
                                if (xmlhttp.status==200) {
                                        callback(xmlhttp);
                                } else {
                                        fallback(xmlhttp);
                                }
                                       
                        }
                }
                var urldata = XMLRequest.buildQuery(query);
                var postdata = XMLRequest.buildQuery(form).substr(1);
                xmlhttp.open("POST",url+urldata,true);
                xmlhttp.setRequestHeader("Content-type" , "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("Content-length", postdata.length);
                xmlhttp.setRequestHeader("Connection", "close");
                xmlhttp.send(postdata);
        }else{
                alert("Your browser does not support XMLHTTP.")
        }
}