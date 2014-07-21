<?
if($_REQUEST['agent']=="")
$_REQUEST['agent']="Not avaliable";
 
if($_REQUEST['exp']=="")
$_REQUEST['exp']="Not avaliable";

include('../../../wp-config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
window.moveTo(150,0);
</script>
<link rel="stylesheet" href="<? echo get_bloginfo('stylesheet_url'); ?>" TYPE="text/css" MEDIA="screen">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Property details</title>

<style type="text/css">
div#popup {
background:#EFEFEF;
border:1px solid #999999;
margin:0px;
padding:7px;
width:270px;
}
div#popup1 {
background:#EFEFEF;
border:1px solid #999999;
margin:0px;
padding:7px;
width:270px;
}
</style>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAxOo3f2vUyGaVoh2Bnrq8fRR30t44xBfs1aMIbBJZgpas7PegChR2Q4_enKeV-EAGT59TXdsYG0clRw" type="text/javascript"></script>


</head>

<body onload="buildMap()">
<h2 style="padding-left:100px">Detail page of the property</h2>
<div style="margin-top:05px;">

<div style="margin-left:30px; float:left; width:160px; height:160px;"><img src="<? echo $_REQUEST['image'];?>" align="" width="150" height="160"/></div>

<div style="float:left; margin-left:10px; width:300px; height:160px;">
<table>
<tr><td><a onclick="openhref('<?=$_REQUEST['href']?>')"><?=$_REQUEST['location']?></a></td></tr>
<tr><td><strong>Price:</strong>$<? echo $_REQUEST['price'];?></td></tr>
<tr><td>br<? echo $_REQUEST['br'];?>&nbsp;ba&nbsp;<? echo $_REQUEST['ba'];?></td></tr>
<tr><td>interior:<? echo $_REQUEST['inter'];?></td></tr>
<tr><td>Expiration date:<? echo $_REQUEST['exp'];?></td></tr>
<tr><td>Agent name:<? echo $_REQUEST['agent'];?></td></tr>
<tr><td><? echo $_REQUEST['prop'];?></td></tr>
</table>
</div>
</div>
<br/><br/><br/><br/>
<div style="margin-left:20px; width:300px; overflow:visible">
<p><strong>Features</strong><br/><? echo $_REQUEST['feat'];?></p>
</div>
<?
$url="http://maps.google.com/maps/geo?q=islamabad+pakistan&output=xml&oe=utf8&sensor=true_or_false&key=ABQIAAAAaaJ97QCHveiroH26U24d7BRfYGmQ698QgkVAcOobY7ZKFFhPFhTrg1VrOFD0QVNUxEQ0twCXSuQy9w";
$str=file_get_contents($url);
//print_r($str);
$obj=simplexml_load_string($str);
echo "<pre>";
//print_r($obj);

$north=($obj->Response->Placemark->ExtendedData->LatLonBox[0]['north']);
$east=($obj->Response->Placemark->ExtendedData->LatLonBox[0]['east']);
$image_linck=$_REQUEST['image'];
$price=$_REQUEST['price'];
$agent_name=$_REQUEST['agent'];
$location=$_REQUEST['location'];
$site_name=$_REQUEST['href'];
$ineter=$_REQUEST['inter'];
$price="$".$price
///////////////end php//////////
?>

<script type="text/javascript">
var data = [{name:'<?=$agent_name?>',img:'',title:'<?=$location?>',date:'<?=$price?>',lat:'25.5422',lng:'50.385799',seeall:'<?=$ineter?>'}];
var map, route;
var points = [];
var gmarkers = [];
var count =0;
var stopClick = false;

function openhref(he)
{
	window.location.href=he;
	//window.open(he,menubar=no,width=700,height=1024,toolbar=no,scrollbars=yes);
	window.close;

}

function addIcon(icon) { // Add icon attributes

 icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
 icon.iconSize = new GSize(32, 32);
 icon.shadowSize = new GSize(37, 34);
 icon.iconAnchor = new GPoint(15, 34);
 icon.infoWindowAnchor = new GPoint(19, 2);
 icon.infoShadowAnchor = new GPoint(18, 25);
}


function addClickevent(marker) { // Add a click listener to the markers

 GEvent.addListener(marker, "click", function() {
  marker.openInfoWindowHtml(marker.content);
  /* Change count to continue from the last manually clicked marker
  *  Better syntax since Javascript 1.6 - Unfortunately not implemented in IE.
  *  count = gmarkers.indexOf(marker);
  */
  count = marker.nr;
  stopClick = true;
 });
 return marker;
}


function buildMap() {

 if(GBrowserIsCompatible()) {
  map = new GMap2(document.getElementById("map2"));
  map.setCenter(new GLatLng(25.5422,50.385799), 4);
  //map.addControl(new GSmallMapControl());
  map.addControl(new GLargeMapControl());
  map.addControl(new GMapTypeControl());

  // Light blue marker icons
  var icon = new GIcon();
  icon.image = "http://www.google.com/intl/en_de/mapfiles/ms/icons/ltblue-dot.png";
  addIcon(icon);

  for(var i = 0; i < data.length; i++) {
   points[i] = new GLatLng(parseFloat(data[i].lat), parseFloat(data[i].lng));
   gmarkers[i] = new GMarker(points[i], icon);

   // Store data attributes as property of gmarkers
var html='<table width=\"250\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\"><tr><td rowspan=\"3\"valign=\"top\" width=\"25\">'+data[i].img+'</td><td valign=\"top\"  align=\"left\">'+data[i].name+'</td></tr><tr><td valign=\"top\"  align=\"left\">'+data[i].title+'</td></tr><tr><td  align=\"left\">'+data[i].date+'</td></tr><tr><td colspan=\"2\" align=\"right\">'+data[i].seeall+'</td></tr></table>';
   
   
   
   
   //var html ="<table>" +
   //""+ data[i].name + "<\/table>";
   gmarkers[i].content = html;
   gmarkers[i].nr = i;
   addClickevent(gmarkers[i]);
   map.addOverlay(gmarkers[i]);
  }
  // Draw polylines between marker points
  var poly= new GPolyline(points, "#003355", 3, 0);
  map.addOverlay(poly);

  // Open infowindow of first marker
  gmarkers[0].openInfoWindowHtml( gmarkers[0].content);

  route =setTimeout("anim()", 90000);
 }
} 


function haltAnim() {

 if(route) {
  clearTimeout(route);
  stopClick = true;
 }
}


function carryOn() {

 if(stopClick == true) anim();
 stopClick = false;
}


function anim() {

 count++;
 if(count < points.length) {
  // Use counter as array index
  map.panTo(points[count]);
  gmarkers[count].openInfoWindowHtml( gmarkers[count].content);
  var delay = 3400;
  if((count+1) != points.length)
   var dist = points[count].distanceFrom(points[count+1]);

  // Adjust delay
  if( dist < 10000 ) {
   delay = 2000;
  }
  if( dist > 80000 ) {
   delay = 4200;
  }
  route = setTimeout("anim()", delay);
 }
  else {
  clearTimeout(route);
  count = 0;
  route = false;
  playAgain();
 }
}


function playAgain() {

 GUnload();
 if(route) clearTimeout(route);
 stopClick = false;
 count = 0;
 buildMap();
}

//]]>
</script>
<div id="map2" style="height:230px;width:94%;vertical-align:middle;border:1px solid gray;margin-top:0px;margin-left:30px; margin-bottom:0px;"></div>
    <div id="side_bar"></div>	
	<p><a onclick="openhref('<?=$_REQUEST['href']?>')" style="font-size:12px;">Visit Site</a></p>	
</body>
</html>
