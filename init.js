$(function () {

    var stateNames = new Array();
    var stateURLs = new Array();
    var stateModes = new Array();
    var stateColors = new Array();
    var stateOverColors = new Array();
    var stateClickedColors = new Array();
    var stateText = new Array();
    var stemOrgs = new Array();

    var offColor;
    var strokeColor;
    var mapWidth;
    var mapHeight;
    var useSideText;
    var textAreaWidth;
    var textAreaPadding;

    var mouseX = 0;
    var mouseY = 0;
    var current = null;

    // Detect if the browser is IE.
    var IE = $.browser.msie ? true : false;
    var map = $("#mapDiv1");
    mapHeight = map.innerHeight();
    mapWidth = map.innerWidth();
    
    var mapViewHeight;
    var mapViewWidth;
    
    $.ajax({
        type: 'GET',
        url: 'usaMapSettings.xml',
        dataType: $.browser.msie ? 'text' : 'xml',
        success: function (data) {


            var xml;
            if ($.browser.msie) {
                xml = new ActiveXObject('Microsoft.XMLDOM');
                xml.async = false;
                xml.loadXML(data);
            } else {
                xml = data;
            }

            var $xml = $(xml);

            offColor = '#' + $xml.find('mapSettings').attr('offColor');
            strokeColor = '#' + $xml.find('mapSettings').attr('strokeColor');
            stemColor = '#' + $xml.find('mapSettings').attr('stemColor');
            mapViewWidth = $xml.find('mapSettings').attr('mapWidth');
            mapViewHeight = $xml.find('mapSettings').attr('mapHeight');


            //Parse xml
            $xml.find('stateData').each(function (i) {

                var $node = $(this);

                stateText.push($node.text());
                stateNames.push($node.attr('stateName'));
                stemOrgs.push($node.attr('stemOrg'));
                stateURLs.push($node.attr('url'));
                stateModes.push($node.attr('stateMode'));
                stateColors.push(stemColor);
                stateOverColors.push(stemColor);
                stateClickedColors.push(stemColor);

            });

            createMap();

        }
    });


    function createMap() {
        $("#mapDiv1 img").hide();
        //start map
        var r = new ScaleRaphael('map', mapHeight, mapWidth),
            attributes = {
                fill: '#d9d9d9',
                cursor: 'pointer',
                stroke: strokeColor,
                'stroke-width': 1,
                'stroke-linejoin': 'round'
            },
            arr = new Array();
        r.setViewBox(0, 0, mapViewWidth, mapViewHeight);

        for (var state in usamappaths) {

            //Create obj
            var obj = r.path(usamappaths[state].path);
            obj.attr(attributes);
            arr[obj.id] = state;

            if (stateModes[obj.id] == 'OFF') {
                obj.attr({
                    fill: offColor,
                    cursor: 'default'
                });
            } else {
                obj.attr({
                    fill: stateColors[obj.id]
                });
                obj.click(function(e) {
                  newwindow=window.open(stateURLs[this.id],'stem');
                  if (window.focus) {newwindow.focus()}
                	return false;
                })
                obj.mouseover(function (e) {
                  //Animate if not already the current state
                  if (this != current) {
                    if (current && current.g) {
                      current.g.remove();
                      current.g = null;
                    }
                    this.animate({
                        fill: stateOverColors[this.id]
                    }, 500);
                    this.g = this.glow({
                      width: 6,
                      offsety: 3,
                      offsetx: 3
                    }).toFront();
                    this.toFront();
                    var title = stateNames[this.id] + " - " + stemOrgs[this.id] + "<br/><a href='" +stateURLs[this.id]+"'>click to go to site</a>";
                    $("#map").attr("title", title).qtip('option', 'content.text', title).qtip('api').show();
                    
                    current = this;
                  }
                });
                obj.mouseout(function (e) {
                    if (this == current) {
                        this.animate({
                            fill: stateColors[this.id]
                        }, 500);
                        if (current && current.g) {
                          current.g.remove();
                          current.g = null;
                        }
                        $(".qtip").hide();
                    }
                });

            }

        }

        resizeMap(r);
        
        $(window).bind("resize", function() {resizeMap(r);});
        
        $("#map").qtip({
        		content: " ",
        		position: {
        			my: 'top left',
        			target: 'mouse',
        			viewport: $(window), // Keep it on-screen at all times if possible
        			adjust: {
        				x: 10,  y: 10
        			}
        		},
        		hide: {
        			fixed: true
        		},
        		style: 'ui-tooltip-shadow'
        	});
    }



    // Set up for mouse capture
    if (document.captureEvents && Event.MOUSEMOVE) {
        document.captureEvents(Event.MOUSEMOVE);
    }

    // Main function to retrieve mouse x-y pos.s

    function getMouseXY(e) {

        if (e && e.pageX) {
            mouseX = e.pageX;
            mouseY = e.pageY;
        } else {
            mouseX = event.clientX + document.body.scrollLeft;
            mouseY = event.clientY + document.body.scrollTop;
        }
        // catch possible negative values
        if (mouseX < 0) {
            mouseX = 0;
        }
        if (mouseY < 0) {
            mouseY = 0;
        }

        $('#map').next('.point').css({
            left: mouseX - 50,
            top: mouseY - 70
        })
    }

    // Set-up to use getMouseXY function onMouseMove
    document.body.onmousemove = getMouseXY;


    function resizeMap(paper) {
    	console.log("resize!");

        mapHeight = map.innerHeight();
        mapWidth = map.innerWidth();
        paper.changeSize(mapWidth, mapHeight, true, false);

           
            $(".mapWrapper").css({
                'width': mapWidth + 'px',
                'height': mapHeight + 'px'
            });
       

    }



});