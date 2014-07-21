<? $Vcff8b1fe = $_GET['Vcff8b1fe'];
   $Vd01befa8 = $_GET['Vd01befa8'];
   $Vd5189de0 = $_GET['Vd5189de0'];
   $V9c35be32 = $_GET['V9c35be32'];
   $V8d8d1437 = $_GET['V8d8d1437'];
   $V320381db = $_GET['V320381db'];   
?>
var data = [{name:'<? echo "Beds: ".$Vcff8b1fe."&nbsp;&nbsp;Baths: ".$Vd01befa8; ?>',img:'',title:'<?=$Vd5189de0?>',date:'<?=$V9c35be32?>',lat:'<?=$V8d8d1437?>',lng:'<?=$V320381db?>',seeall:''}];
var map, route;
var points = [];
var gmarkers = [];
var count =0;
var stopClick = false;
function Ff33bf54e(he)
{
	window.location.href=he;
 
	window.close;
}
function Fd0f1f5e1(icon) { 
 icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
icon.iconSize = new GSize(32, 32);
icon.shadowSize = new GSize(37, 34);
icon.iconAnchor = new GPoint(15, 34);
icon.infoWindowAnchor = new GPoint(19, 2);
icon.infoShadowAnchor = new GPoint(18, 25);
}
function F1c0c7177(marker) { 
 GEvent.addListener(marker, "click", function() {
 marker.openInfoWindowHtml(marker.content);
 
 count = marker.nr;
stopClick = true;
});
return marker;
}
function F327c1288() {
 if(GBrowserIsCompatible()) {
 map = new GMap2(document.getElementById("map2"));
//map.setCenter(new GLatLng(25.5422,50.385799), 10);
map.setCenter(new GLatLng(<?=$V8d8d1437?>,<?=$V320381db?>), 10);
 
 map.addControl(new GLargeMapControl());
map.addControl(new GMapTypeControl());
 
 var icon = new GIcon();
icon.image = "http://www.google.com/intl/en_de/mapfiles/ms/icons/ltblue-dot.png";
Fd0f1f5e1(icon);
for(var i = 0; i < data.length; i++) {
 points[i] = new GLatLng(parseFloat(data[i].lat), parseFloat(data[i].lng));
gmarkers[i] = new GMarker(points[i], icon);
 
var html='<table width=\"250\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\"><tr><td rowspan=\"3\"valign=\"top\" width=\"25\">'+data[i].img+'</td><td valign=\"top\" align=\"left\">'+data[i].name+'</td></tr><tr><td valign=\"top\" align=\"left\">'+data[i].title+'</td></tr><tr><td align=\"left\">'+data[i].date+'</td></tr><tr><td colspan=\"2\" align=\"right\">'+data[i].seeall+'</td></tr></table>';

 
 
 
  
 gmarkers[i].content = html;
gmarkers[i].nr = i;
F1c0c7177(gmarkers[i]);
map.addOverlay(gmarkers[i]);
}
 
 var poly= new GPolyline(points, "#003355", 3, 0);
map.addOverlay(poly);
 
 gmarkers[0].openInfoWindowHtml( gmarkers[0].content);
route =setTimeout("F39977da6()", 90000);
}
} 
function F94ad92fe() {
 if(route) {
 clearTimeout(route);
stopClick = true;
}
}
function Fa3f79478() {
 if(stopClick == true) F39977da6();
stopClick = false;
}
function F39977da6() {
 count++;
if(count < points.length) {
 
 map.panTo(points[count]);
gmarkers[count].openInfoWindowHtml( gmarkers[count].content);
var delay = 3400;
if((count+1) != points.length)
 var dist = points[count].distanceFrom(points[count+1]);
 
 if( dist < 10000 ) {
 delay = 2000;
}
if( dist > 80000 ) {
 delay = 4200;
}
route = setTimeout("F39977da6()", delay);
}
else {
 clearTimeout(route);
count = 0;
route = false;
Fc5d15520();
}
}
function Fc5d15520() {
 GUnload();
if(route) clearTimeout(route);
stopClick = false;
count = 0;
F327c1288();
}