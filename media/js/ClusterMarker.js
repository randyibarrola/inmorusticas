/*
	ClusterMarker Version 1.3.2
	
	A marker manager for the Google Maps API
	http://googlemapsapi.martinpearman.co.uk/clustermarker
	
	Copyright Martin Pearman 2008
	Last updated 29th September 2008

	This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

	You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
*/
function ClusterMarker(_1,_2){this._map=_1;this._mapMarkers=[];this._iconBounds=[];this._clusterMarkers=[];this._eventListeners=[];if(typeof (_2)==="undefined"){_2={};}this.borderPadding=(_2.borderPadding)?_2.borderPadding:256;this.clusteringEnabled=(_2.clusteringEnabled===false)?false:true;if(_2.clusterMarkerClick){this.clusterMarkerClick=_2.clusterMarkerClick;}if(_2.clusterMarkerIcon){this.clusterMarkerIcon=_2.clusterMarkerIcon;}else{this.clusterMarkerIcon=new GIcon();this.clusterMarkerIcon.image="http://maps.google.com/mapfiles/arrow.png";this.clusterMarkerIcon.iconSize=new GSize(39,34);this.clusterMarkerIcon.iconAnchor=new GPoint(9,31);this.clusterMarkerIcon.infoWindowAnchor=new GPoint(9,31);this.clusterMarkerIcon.shadow="http://www.google.com/intl/en_us/mapfiles/arrowshadow.png";this.clusterMarkerIcon.shadowSize=new GSize(39,34);}this.clusterMarkerTitle=(_2.clusterMarkerTitle)?_2.clusterMarkerTitle:"Click to zoom in and see %count markers";if(_2.fitMapMaxZoom){this.fitMapMaxZoom=_2.fitMapMaxZoom;}this.intersectPadding=(_2.intersectPadding)?_2.intersectPadding:0;if(_2.markers){this.addMarkers(_2.markers);}GEvent.bind(this._map,"moveend",this,this._moveEnd);GEvent.bind(this._map,"zoomend",this,this._zoomEnd);GEvent.bind(this._map,"maptypechanged",this,this._mapTypeChanged);};ClusterMarker.prototype.addMarkers=function(_3){var i;if(!_3[0]){var _5=[];for(i in _3){_5.push(_3[i]);}_3=_5;}for(i=_3.length-1;i>=0;i--){_3[i]._isVisible=false;_3[i]._isActive=false;_3[i]._makeVisible=false;}this._mapMarkers=this._mapMarkers.concat(_3);};ClusterMarker.prototype._clusterMarker=function(_6){function $newClusterMarker(_7,_8,_9){return new GMarker(_7,{icon:_8,title:_9});};var _a=new GLatLngBounds(),i,_c,_d=[],_e,_f=this,_10=this._mapMarkers;for(i=_6.length-1;i>=0;i--){_e=_10[_6[i]];_e.index=_6[i];_a.extend(_e.getLatLng());_d.push(_e);}_c=$newClusterMarker(_a.getCenter(),this.clusterMarkerIcon,this.clusterMarkerTitle.replace(/%count/gi,_6.length));_c.clusterGroupBounds=_a;this._eventListeners.push(GEvent.addListener(_c,"click",function(){_f.clusterMarkerClick({clusterMarker:_c,clusteredMarkers:_d});}));_c._childIndexes=_6;for(i=_6.length-1;i>=0;i--){_10[_6[i]]._parentCluster=_c;}return _c;};ClusterMarker.prototype.clusterMarkerClick=function(_11){this._map.setCenter(_11.clusterMarker.getLatLng(),this._map.getBoundsZoomLevel(_11.clusterMarker.clusterGroupBounds));};ClusterMarker.prototype._filterActiveMapMarkers=function(){var _12=this.borderPadding,_13=this._map.getZoom(),_14=this._map.getCurrentMapType().getProjection(),_15,_16,_17,_18,_19,_1a,_1b=this._map.getBounds(),i,_1d,_1e=[],_1f,_20=this._mapMarkers,_21=this._iconBounds;if(_12){_15=_14.fromLatLngToPixel(_1b.getSouthWest(),_13);_16=new GPoint(_15.x-_12,_15.y+_12);_17=_14.fromPixelToLatLng(_16,_13);_18=_14.fromLatLngToPixel(_1b.getNorthEast(),_13);_19=new GPoint(_18.x+_12,_18.y-_12);_1a=_14.fromPixelToLatLng(_19,_13);_1b.extend(_17);_1b.extend(_1a);}this._activeMarkersChanged=false;if(typeof (_21[_13])==="undefined"){this._iconBounds[_13]=[];this._activeMarkersChanged=true;for(i=_20.length-1;i>=0;i--){_1d=_20[i];_1d._isActive=_1b.containsLatLng(_1d.getLatLng())?true:false;_1d._makeVisible=_1d._isActive;if(_1d._isActive){_1e.push(i);}}}else{for(i=_20.length-1;i>=0;i--){_1d=_20[i];_1f=_1d._isActive;_1d._isActive=_1b.containsLatLng(_1d.getLatLng())?true:false;_1d._makeVisible=_1d._isActive;if(!this._activeMarkersChanged&&_1f!==_1d._isActive){this._activeMarkersChanged=true;}if(_1d._isActive&&typeof (_21[_13][i])==="undefined"){_1e.push(i);}}}return _1e;};ClusterMarker.prototype._filterIntersectingMapMarkers=function(){var _22,i,j,_25=this._map.getZoom(),_26=this._mapMarkers,_27=this._iconBounds;for(i=_26.length-1;i>0;i--){if(_26[i]._makeVisible){_22=[];for(j=i-1;j>=0;j--){if(_26[j]._makeVisible&&_27[_25][i].intersects(_27[_25][j])){_22.push(j);}}if(_22.length!==0){_22.push(i);for(j=_22.length-1;j>=0;j--){_26[_22[j]]._makeVisible=false;}this._clusterMarkers.push(this._clusterMarker(_22));}}}};ClusterMarker.prototype.fitMapToMarkers=function(){var _28=this._mapMarkers,_29=new GLatLngBounds(),i;for(i=_28.length-1;i>=0;i--){_29.extend(_28[i].getLatLng());}var _2b=this._map.getBoundsZoomLevel(_29);if(this.fitMapMaxZoom&&_2b>this.fitMapMaxZoom){_2b=this.fitMapMaxZoom;}this._map.setCenter(_29.getCenter(),_2b);this.refresh();};ClusterMarker.prototype._mapTypeChanged=function(){this.refresh(true);};ClusterMarker.prototype._moveEnd=function(){if(!this._cancelMoveEnd){this.refresh();}else{this._cancelMoveEnd=false;}};ClusterMarker.prototype._preCacheIconBounds=function(_2c,_2d){var _2e=this._map.getCurrentMapType().getProjection(),i,_30,_31,_32,_33,_34,_35,_36,_37,_38=this.intersectPadding,_39=this._mapMarkers;for(i=_2c.length-1;i>=0;i--){_30=_39[_2c[i]];_31=_30.getIcon().iconSize;_32=_2e.fromLatLngToPixel(_30.getLatLng(),_2d);_33=_30.getIcon().iconAnchor;_34=new GPoint(_32.x-_33.x-_38,_32.y-_33.y+_31.height+_38);_35=new GPoint(_32.x-_33.x+_31.width+_38,_32.y-_33.y-_38);_36=_2e.fromPixelToLatLng(_34,_2d);_37=_2e.fromPixelToLatLng(_35,_2d);this._iconBounds[_2d][_2c[i]]=new GLatLngBounds(_36,_37);}};ClusterMarker.prototype.refresh=function(_3a){var i,_3c,_3d=this._map.getZoom(),_3e=this._filterActiveMapMarkers();if(this._activeMarkersChanged||_3a){this._removeClusterMarkers();if(this.clusteringEnabled&&_3d<this._map.getCurrentMapType().getMaximumResolution()){if(_3e.length>0){this._preCacheIconBounds(_3e,_3d);}this._filterIntersectingMapMarkers();}for(i=this._clusterMarkers.length-1;i>=0;i--){this._map.addOverlay(this._clusterMarkers[i]);}for(i=this._mapMarkers.length-1;i>=0;i--){_3c=this._mapMarkers[i];if(!_3c._isVisible&&_3c._makeVisible){this._map.addOverlay(_3c);_3c._isVisible=true;}if(_3c._isVisible&&!_3c._makeVisible){this._map.removeOverlay(_3c);_3c._isVisible=false;}}}};ClusterMarker.prototype._removeClusterMarkers=function(){var i,j,_41=this._map,_42=this._eventListeners,_43=this._clusterMarkers,_44,_45=this._mapMarkers;for(i=_43.length-1;i>=0;i--){_44=_43[i]._childIndexes;for(j=_44.length-1;j>=0;j--){delete _45[_44[j]]._parentCluster;}_41.removeOverlay(_43[i]);}for(i=_42.length-1;i>=0;i--){GEvent.removeListener(_42[i]);}this._clusterMarkers=[];this._eventListeners=[];};ClusterMarker.prototype.removeMarkers=function(){var i,_47=this._mapMarkers,_48=this._map;for(i=_47.length-1;i>=0;i--){if(_47[i]._isVisible){_48.removeOverlay(_47[i]);}delete _47[i]._isVisible;delete _47[i]._isActive;delete _47[i]._makeVisible;}this._removeClusterMarkers();this._mapMarkers=[];this._iconBounds=[];};ClusterMarker.prototype.triggerClick=function(_49){var _4a=this._mapMarkers[_49];if(_4a._isVisible){GEvent.trigger(_4a,"click");}else{if(_4a._isActive){var _4b=_4a._parentCluster._childIndexes,_4c=true,_4d,i,_4f=this._map.getZoom(),_50,_51=this._iconBounds,_52=this._map.getCurrentMapType().getMaximumResolution();while(_4c&&_4f<_52){_4c=false;_4f++;if(typeof (_51[_4f])==="undefined"){_51[_4f]=[];this._preCacheIconBounds(_4b,_4f);}else{_4d=[];for(i=_4b.length-1;i>=0;i--){if(typeof (_51[_4f][_4b[i]])==="undefined"){_4d.push(_4b[i]);}}if(_4d.length>=1){this._preCacheIconBounds(_4d,_4f);}}for(i=_4b.length-1;i>=0;i--){_50=_4b[i];if(_50!==_49&&_51[_4f][_50].intersects(_51[_4f][_49])){_4c=true;break;}}}this._map.setCenter(_4a.getLatLng(),_4f);this.triggerClick(_49);}else{this._map.setCenter(_4a.getLatLng());this.triggerClick(_49);}}};ClusterMarker.prototype._zoomEnd=function(){this._cancelMoveEnd=true;this.refresh(true);};