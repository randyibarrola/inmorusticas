
<script type="text/javascript">
var data = [{name:'<?=$agent_name?>',img:'',title:'<?=$location?>',date:'<?=$price?>',lat:'25.5422',lng:'50.385799',seeall:'555'}];
var map, route;
var points = [];
var gmarkers = [];
var count =0;
var stopClick = false;


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