(function($, d, w) {


	var EditableMap = function(params) {
		var me = this,
			options = $.extend({
				editShape: null
			}, params || {});
		this.shapeCoords = [];
		this.editable = false;

		if (options.editShape) {
			for (var i = 0; i < options.editShape.length; i++) {
				if (!options.editShape[i] || typeof options.editShape[i] != 'object') {
					continue;
				}

				var keys = Object.keys(options.editShape[i]),
					lat = keys[0],
					lon = keys[1];
				if (!options.editShape[i][lat] || !options.editShape[i][lon]) {
					continue;
				}
				
				this.shapeCoords.push(
					new google.maps.LatLng(options.editShape[i][lat], options.editShape[i][lon])
				);
			}
		}


		this.getOptions = function() {
			return me.map.getOptions();
		};

		this.renderShape = function(editable) {
			me.removeShape();

			var color = '#0000CC',
				options = {
				    paths: me.shapeCoords,
				    strokeColor: color,
				    strokeOpacity: 0.8,
				    strokeWeight: 2,
				    fillColor: color,
				    fillOpacity: 0.35
				  };

			if (editable) {
				options.editable = true;
				options.draggable = true;
			}

			me.shape = new google.maps.Polygon(options);

			me.shape.setMap(me.getMap());
			return me;
		};

		this.getShapeCoords = function() {
			return this.shapeCoords;
		};

		this.getMap = function() {
			return me.map.map;
		};

		this.removeShape = function() {
			if (!me.shape) return;

			me.shape.setMap(null);
			me.shape = null;
		};

		function onMapClick(event) {
			if (!me.editable) {
				me.renderShape(false);
				return;
			}

			me.shapeCoords.push(event.latLng);
			me.renderShape(me.editable);
		};

		function ResetControl(controlDiv, map) {
			// Set CSS styles for the DIV containing the control
			// Setting padding to 5 px will offset the control
			// from the edge of the map.
			controlDiv.style.padding = '5px';

			// Set CSS for the control border.
			var controlUI = document.createElement('div');
			controlUI.style.backgroundColor = 'white';
			controlUI.style.borderStyle = 'solid';
			controlUI.style.borderWidth = '2px';
			controlUI.style.cursor = 'pointer';
			controlUI.style.textAlign = 'center';
			controlUI.title = 'Click to set the map to Home';
			controlDiv.appendChild(controlUI);

			// Set CSS for the control interior.
			var controlText = document.createElement('div');
			controlText.style.fontFamily = 'Arial,sans-serif';
			controlText.style.fontSize = '12px';
			controlText.style.paddingLeft = '4px';
			controlText.style.paddingRight = '4px';
			controlText.innerHTML = '<strong>Reset</strong>';
			controlUI.appendChild(controlText);

			// Setup the click event listeners: simply set the map to Chicago.
			google.maps.event.addDomListener(controlUI, 'click', resetButtonClick);
		};

		function resetButtonClick() {
			me.shapeCoords = [];
			me.removeShape();
		};

		function EditControl(controlDiv, map) {
			// Set CSS styles for the DIV containing the control
			// Setting padding to 5 px will offset the control
			// from the edge of the map.
			controlDiv.style.padding = '5px';

			// Set CSS for the control border.
			var controlUI = document.createElement('div');
			controlUI.style.backgroundColor = 'white';
			controlUI.style.borderStyle = 'solid';
			controlUI.style.borderWidth = '2px';
			controlUI.style.cursor = 'pointer';
			controlUI.style.textAlign = 'center';
			controlUI.title = 'Click to set the map to Home';
			controlDiv.appendChild(controlUI);

			// Set CSS for the control interior.
			var controlText = document.createElement('div');
			controlText.style.fontFamily = 'Arial,sans-serif';
			controlText.style.fontSize = '12px';
			controlText.style.paddingLeft = '4px';
			controlText.style.paddingRight = '4px';
			controlText.innerHTML = '<strong>Edit</strong>';
			controlUI.appendChild(controlText);

			function setText(text) {
			  	controlText.innerHTML = '<strong>' + text + '</strong>';
			};

			this.toggleText = function() {
				setText((me.editable) ? 'Done' : 'Edit');
			};

			google.maps.event.addDomListener(controlUI, 'click', editButtonClick);
		};

		function editButtonClick() {
			me.toggleEdit();
			me.editControl.toggleText();
		};

		this.toggleEdit = function() {
			me.editable = (me.editable) ? false : true;
			me.renderShape(me.editable);
			return me;
		};

		this.getEl = function() {
			return me.el;
		};

		function afterMapLoad() {
			options = me.map.getOptions();
			// Setup the click event listeners: simply set the map to Chicago.
			var editControlDiv = document.createElement('div');
	  		me.editControl = new EditControl(editControlDiv, me.getMap());

	  		editControlDiv.index = 1;
	  		me.getMap().controls[google.maps.ControlPosition.TOP_RIGHT].push(editControlDiv);

	  		var resetControlDiv = document.createElement('div');
	  		me.resetControl = new ResetControl(resetControlDiv, me.getMap());

	  		resetControlDiv.index = 2;
	  		me.getMap().controls[google.maps.ControlPosition.TOP_RIGHT].push(resetControlDiv);

			google.maps.event.addListener(me.map.map, 'click', onMapClick);

			if (me.shapeCoords.length > 0)
				me.renderShape();
		};

		params.afterLoad = afterMapLoad;
		this.map = new Map(params);
  		/*if (coords && coords.length > 0) {
			for (var i = 0; i < coords.length; i++) {
				this.shapeCoords.push(new google.maps.LatLng(coords[i].lb, coords[i].mb));
			}
			this.renderShape(false);
		}*/

		return this;
	};

	var Map = function(params) {
		var me = this,
			options = $.extend({
				sel: null,
				shapes: {},
				center: null,
				afterLoad: null,
				mapCenter: null,
				pinnable: false
			}, params || {}),
			mapOptions = {
			    zoom: 12,
			    mapTypeId: google.maps.MapTypeId.ROADMAP
			};

		if (!options.sel) {
			throw "Option 'sel' must be set to target selector";
		}

		this.el = $(options.sel);	
		this.markers = [];

		function handleNoGeoLocation(browserSupport) {
			if (browserSupport) {
				console.log("Unable to determine your location");
			} else {
				console.log("This brower doesn't support geo location");
			}
		};

		this.getOptions = function() {
			return options;
		};

		// Try W3C Geolocation (Preferred)
		/*this.whereAmI = function(done, fail) {
			if(navigator.geolocation) {
			    browserSupportFlag = true;
			    navigator.geolocation.getCurrentPosition(function(position) {
			      return done(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));
			    }, function() {
			      handleNoGeolocation(browserSupportFlag);
			      return fail();
			    });
			  }
			  	// Browser doesn't support Geolocation
			  	else {
			    browserSupportFlag = false;
			    handleNoGeolocation(browserSupportFlag);
			    fail();
			}
		};*/

		this.addTerritories = function(territories) {
			for (var key in territories) {
				var terr = territories[key],
					coords = [];

				for (var n = 0; n < terr.length; n++) {
					var keys = Object.keys(terr[n]),
						lat = keys[0],
						lon = keys[1];
					coords.push(new google.maps.LatLng(terr[n][lat], terr[n][lon]));
				}

				var options = {
			    		paths: coords,
			    		strokeColor: '#FF0000',
			    		strokeOpacity: 0.8,
			    		strokeWeight: 2,
			    		fillColor: '#FF0000',
			    		fillOpacity: 0.35
					},
					shape = new google.maps.Polygon(options);

				shape.setMap(me.getMap());

				var center = centerOf(terr);
				var marker = new google.maps.Marker({
				    position: new google.maps.LatLng(center.lb, center.mb),
				    map: me.getMap(),
				    title: key
				});
			}
		};

		this.getMap = function() {
			return me.map;
		};

		this.onMarkerClick = function(marker) {
			if (options.onMarkerClick)
				options.onMarkerClick.call(me, marker);
		};

		this.addMarker = function(def) {
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(def.lb, def.mb),
				title: def.title,
				map: me.getMap()
			});

			google.maps.event.addListener(marker, 'click', function() {
				me.onMarkerClick(marker);
			});

			marker.set('info', def);
			me.markers.push(marker);

			return me;
		};

		this.dropMarker = function(def) {
			var marker = new google.maps.Marker(def);
			me.markers.push(marker);
		};

		this.getMarkers = function() {
			return me.markers;
		};

		function onMapClick(evt) {
			if (me.markers.length == 0) {
				me.dropMarker({
					position: evt.latLng,
					draggable: true,
					map: me.getMap()
				});
			}
		};

		function loadMap(opts) {
			me.map = new google.maps.Map(document.getElementById(me.el.attr('id')), opts);

			if (options.pinnable) {
				google.maps.event.addListener(me.map, 'click', onMapClick);
			}

			if (options.shapes && Object.keys(options.shapes).length > 0) {
				me.addTerritories(options.shapes);
			}

			if (options.markers) {
				for (var i = options.markers.length - 1; i >= 0; i--) {
					var marker = options.markers[i];
					me.addMarker(marker);
				};
			}

			if (options.afterLoad)
				options.afterLoad.call(me);
		};

		if (options.mapCenter) {
			mapOptions.center = options.mapCenter();
			loadMap(mapOptions);
		} else {
			whereAmI(function(pos) {
				mapOptions.center = pos;
				loadMap(mapOptions);
			}, function() {
				mapOptions.center = new google.maps.LatLng(50.00,-90.00);
				loadMap(mapOptions);
			});
		}
	};

	function centerOf(coords) {
		var sumOfLat = 0.0, 
			sumOfLon = 0.0, 
			total = coords.length;
		for (var i = 0; i < total; i++) {
			var keys = Object.keys(coords[i]),
				lat = keys[0],
				lon = keys[1];
			sumOfLat += parseFloat(coords[i][lat]);
			sumOfLon += parseFloat(coords[i][lon]);
		}

		return {
			lb: sumOfLat/total,
			mb: sumOfLon/total
		};
	};

	function whereAmI(done, fail) {
		if(navigator.geolocation) {
		    browserSupportFlag = true;
		    navigator.geolocation.getCurrentPosition(function(position) {
		      return done(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));
		    }, function() {
		      return fail();
		    });
		  } else {
		  	// Browser doesn't support Geolocation
		    browserSupportFlag = false;
		    fail();
		}
	};

	w.FLT = {
		Map: Map,
		EditableMap: EditableMap,
		Utility: {
			centerOf: centerOf,
			whereAmI: whereAmI
		}
	};

	$(document).ready(function() {
		// w.Map = new Map('#territory_map');
	}); 

})(jQuery, document, window);