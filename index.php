<?php
    include 'geoHash.php';
    session_start();

    if (isset($_POST['submit1'])) {

        $_SESSION['key'] = $_POST['key'];
        $_SESSION['loc'] = $_REQUEST['loc'];
        $_SESSION['distance'] = $_REQUEST['distance'];
        $_SESSION['categ'] = $_POST['categ'];
        //echo "inside";

        $segid = "";
        if($_SESSION['categ'] == "default") {
            $segid = "";
        }
        if($_SESSION['categ'] == "music") {
            $segid = "KZFzniwnSyZfZ7v7nJ";
        }
        if($_SESSION['categ'] == "sports") {
            $segid = "KZFzniwnSyZfZ7v7nE";
        }
        if($_SESSION['categ'] == "arts") {
            $segid = "KZFzniwnSyZfZ7v7na";
        }
        if($_SESSION['categ'] == "films") {
            $segid = "KZFzniwnSyZfZ7v7nn";
        }
        if($_SESSION['categ'] == "miscell") {
            $segid = "KZFzniwnSyZfZ7v7n1";
        }


       if ($_REQUEST['loc'] == "Loc") {

           $_SESSION['location'] = $_REQUEST['location'];
           $str = str_replace( " ", "",$_SESSION['location']);
           $georequest = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$str.'&key=AIzaSyDGhuj1wNHdTdGIlkWaQSjxEvSfI9xDytQ';
           $geojson = file_get_contents($georequest);
           $geoobj = json_decode($geojson);
           //var_dump($georequest);
           //var_dump($geojson);
           $geopoint = getgeopoint($geoobj);
           //var_dump($geopoint);

           if ($_REQUEST['distance'] == "") {
               //echo "22222inside";

               $erequest = 'https://app.ticketmaster.com/discovery/v2/events.json?apikey=GGpATGb0u2gXGo7quZeEhO9EHzxbbAYl&keyword='.$_SESSION['key'].'&segmentId='.$segid.'&radius=10&unit=miles&geoPoint='.$geopoint;
               // json formatted obj to string
               $ejson = file_get_contents($erequest);
               $eobj = json_decode($ejson);
               //var_dump($erequest);
               //var_dump($ejson);
               //echo "that is it";
           }else{
               $erequest = 'https://app.ticketmaster.com/discovery/v2/events.json?apikey=GGpATGb0u2gXGo7quZeEhO9EHzxbbAYl&keyword='.$_SESSION['key'].'&segmentId='.$segid.'&radius='.$_SESSION['distance'].'&unit=miles&geoPoint='.$geopoint;
               // json formatted obj to string
               $ejson = file_get_contents($erequest);
               $eobj = json_decode($ejson);
               //var_dump($erequest);
               //var_dump($ejson);
           }
       } 
       else if($_REQUEST['loc'] == "Here") {
           //$_SESSION['loc'] = $_REQUEST['loc'];
           $latit = $_POST['latitude'];
           $long = $_POST['longtitude'];
           $geopoint = encode($latit, $long);

           if ($_REQUEST['distance'] == "") {
               $erequest = 'https://app.ticketmaster.com/discovery/v2/events.json?apikey=GGpATGb0u2gXGo7quZeEhO9EHzxbbAYl&keyword='.$_SESSION['key'].'&segmentId='.$segid.'&radius=10&unit=miles&geoPoint='.$geopoint;
               // json formatted obj to string
               $ejson = file_get_contents($erequest);
               $eobj = json_decode($ejson);
               //var_dump($erequest);
               //var_dump($eobj);
           }else{
               $erequest = 'https://app.ticketmaster.com/discovery/v2/events.json?apikey=GGpATGb0u2gXGo7quZeEhO9EHzxbbAYl&keyword='.$_SESSION['key'].'&segmentId='.$segid.'&radius='.$_SESSION['distance'].'&unit=miles&geoPoint='.$geopoint;
               // json formatted obj to string
               $ejson = file_get_contents($erequest);
               $eobj = json_decode($ejson);
               //var_dump($erequest);
               //var_dump($ejson);
           }
       }

    }
    if(isset($_REQUEST['submit2'])) {
        session_destroy();
        unset($_SESSION);
    }   


    if(isset($_GET['eveid'])) {
        $eveid = $_GET['eveid'];
        //echo $eveid;
        $requestname = 'https://app.ticketmaster.com/discovery/v2/events/'.$eveid.'?apikey=GGpATGb0u2gXGo7quZeEhO9EHzxbbAYl';

        $jsonname = file_get_contents($requestname);
        $nameobj = json_decode($jsonname);

        //var_dump($requestname);
        //var_dump($nameobj);
    }

    if(isset($_GET['venue'])) {
        $venuename = $_GET['venue'];
        //echo $eveid;
        //echo $venuename;
        $venuedetail = urlencode($venuename);
        $requestvenue = 'https://app.ticketmaster.com/discovery/v2/venues?apikey=GGpATGb0u2gXGo7quZeEhO9EHzxbbAYl&keyword='.$venuedetail;

        $jsonvenue = file_get_contents($requestvenue);
        $venueobj = json_decode($jsonvenue);

        //var_dump($requestvenue);
        //var_dump($jsonvenue);

    }

    function getgeopoint($geoobj) {
        $result = $geoobj->{'results'};
        $geome = $result[0]->{'geometry'};
        $location = $geome->{'location'};
        $latit = $location->{'lat'};
        $long = $location->{'lng'};
        $geopoint = encode($latit, $long);
        return $geopoint;
    }

?>
<html>
    <head>
        <title>events</title>
        <style>
             .background {
                background-color: whitesmoke;
                border:2px solid gainsboro;
                width: 500px;
                height: 185px;
                margin-left:auto;
                margin-right:auto;
                margin-bottom:10px;
            }
            .title {
                font-family:serif;
                font-size: 30;
                text-align: center;
                margin-bottom:20px;
            }
            .line {
                border-bottom:0px;
                border-right:0px;
                border-left:0px;
                border-top: 2px solid gainsboro;
                margin:5px;
                position:relative;
                bottom:5px;
            }
            .txt {
                text-align:left;  
                font-family:serif;
                font-size: 15;
                position: relative;
                left:10px;
                font-weight: 800;
            }
            
            .box {
                position: relative;
                left:10px;
            }
            .boxcp1 {
                position: relative;
                left:315px;
            }
            .boxcp2 {
                position: relative;
                left:315px;
                width: 110px;
            }
            .button {
                position:relative;
                top:10px;
                left: 70px;
            }
            
            .maintable table {
                border-collapse: collapse;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
                width: 1000px;
            }
            .maintable table, .maintable td, .maintable th {
                border: 2px solid gainsboro;
            }
            
            .maintable a {
                text-decoration: none;
                color: black;
            }
            
            .maintable a:hover {
                color: gainsboro;
            }
            
            .mainbtn {
                border: none;
                position: relative;
                top:15px;
                font-size: 14px;
                background-color: white;
            }
            .mainbtn:hover {
                color: gainsboro;
            }
            .evetable table {
                margin-left: auto;
                margin-right: auto;
            }
            .evetable h3 {
                margin-bottom: 0;
                margin-top: 35px;
                text-align: center;
            }
            .evetable p {
                margin-bottom: 0;
                margin-top: 15px;
                text-align: left;
                padding-bottom: 0;
                padding-top: 0;
                white-space: nowrap;
            }
            .evetable h6 {
                padding-left: 0;
                text-align: left;
                margin-bottom: 13px;
                margin-top: 15px;
                padding-bottom: 0;
                padding-top: 0;
                font-size: 16px;
            }
            .evetable a {
                text-decoration: none;
                color: black;
                margin-top: 15px;
            }
            .evetable a:hover {
                color: gainsboro;
            }
            .btn2, .btn1 {
                text-align: center;
            }
            .btn2 p, .btn1 p {
                color: grey;
            }
            .ventable table {
                margin-left: auto;
                margin-right: auto;
                border-collapse: collapse;
                width: 800px;
            }
            .ventable table, .ventable td, .ventable th {
                border: 2px solid gainsboro;
            }
            .ventable td {
                text-align: center;
            }
            .ventable td p {
                font-weight: bold;
                text-align: right;
                color: black;
            }
            .ventable a {
                text-decoration: none;
                color: black;
            }
            .ventable a:hover {
                color: gainsboro;
            }
            .pictable table {
                width: 1000px;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }
            .pictable table, .pictable tr, .pictable td {
                border: 2px solid gainsboro;
                border-collapse: collapse;
            }
            .pictable img {
                width: auto;
                height: auto;
                max-width: 80%;
                max-height: 100%;
            }
            
            #map {
                height: 300px;  /* The height is 400 pixels */
                width: 400px;  /* The width is the width of the web page */
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 10px;
                margin-top: -105px;
                z-index: 9;
            }
            #floating-panel {
                position: relative;
                text-align: left;
                margin-left: 20px;
                margin-top: 60px;
                background-color: #efeeee;
                width: fit-content;
            }
            #floating-panel #r1, #floating-panel #r2, #floating-panel #r3 {
                text-align: center;
                border: none;
                background-color: #efeeee;
            }
            #floating-panel #r1:hover, #floating-panel #r2:hover, #floating-panel #r3:hover {
                color: gray;
            }

            .outside {
                position: relative;
                height: 50px;
            }
            
            .smallmap {
                z-index: 9;
                display: none;
                position: absolute;
                top: 35px;
                border: 3px solid #f1f1f1;
                height: 200px;
                width: 200px;
            }
            
            .mpanel {
                z-index: 9;
                display: none;
                position: absolute;
                top: 35px;
                background-color: #efeeee;
                width: fit-content;
            }
            .mpanel #m1, .mpanel #m2, .mpanel #m3 {
                text-align: center;
                border: none;
                background-color: #efeeee;
            }
            
            .mpanel #m1:hover, .mpanel #m2:hover, .mpanel #m3:hover {
                color: gray;
                background-color: dimgrey;
            }
            
            .btn1 #infobtn {
                margin-bottom: 20px;
            }
            .btn2 #photo {
                margin-bottom: 20px;
            }
            
        </style>
    </head>
    <body>
        <div class = "background">
            <div class = "title">
                <i>Events Search</i>
                <hr class = "line">
            </div>
            
            <form method="POST" action="index.php">
                <span class = "txt">Keyword</span> 
                <input class="box" id="key" type="text" name="key" required value="<?php 
                    if (isset($_SESSION['key'])){
                        echo($_SESSION['key']); 
                    }
                ?>"><br>
                
                <span class = "txt">Category</span> 
                <select class="box" name="categ">
                <option value="default" <?php if (isset($_SESSION['categ']) && $_SESSION['categ']=="default") echo "selected";?>>Default</option>
                <option value="music" <?php if (isset($_SESSION['categ']) && $_SESSION['categ']=="music") echo "selected";?>>Music</option>
                <option value="sports" <?php if (isset($_SESSION['categ']) && $_SESSION['categ']=="sports") echo "selected";?>>Sports</option>
                <option value="arts" <?php if (isset($_SESSION['categ']) && $_SESSION['categ']=="arts") echo "selected";?>>Arts & Theatres</option>
                <option value="films" <?php if (isset($_SESSION['categ']) && $_SESSION['categ']=="films") echo "selected";?>>Film</option>
                <option value="miscell" <?php if (isset($_SESSION['categ']) && $_SESSION['categ']=="miscell") echo "selected";?>>Miscellaneous</option></select>
                <br>
                
                <span class = "txt">Distance (miles)</span> 
                <input class="box" type="text" name="distance" id="distance" placeholder="10" value="<?php 
                    if (isset($_SESSION['distance'])){
                        echo($_SESSION['distance']); 
                    }
                ?>">
                
                <span class = "txt">from</span> 
                <input class="box" type="radio" name="loc" id="here" checked value="Here" <?php if (isset($_SESSION['loc']) && $_SESSION['loc']=="Here") echo "checked";?> onclick="disInp()"><span class = "txt" style="font-weight: normal;">Here</span><br>
                <input class="boxcp1" type="radio" value="Loc" name="loc" id="locat" <?php if (isset($_SESSION['loc']) && $_SESSION['loc']=="Loc") echo "checked";?> onclick="enInp()"><input class="boxcp2" type="text" name="location" id="location" placeholder="location" disabled value="<?php 
                    if (isset($_SESSION['location'])){
                        echo($_SESSION['location']); 
                    }
                ?>" /><br>
                
                <input type="hidden" name="latitude" id="latitude" value="">
                <input type="hidden" name="longtitude" id="longtitude" value="">
                
                <input class = "button" type="submit"  id="search" name = "submit1" value = "Search" disabled>
                <input class = "button" type="submit"  name = "submit2" value="Clear">
            </form>
        </div>

        

        
        <div class="maintable" id="maintable">
        </div>
        
        <div class="evetable" id="evetable">
        </div>
        
        <div class="btn1" id="btn1"></div>
        <br>

        <div class="btn2" id="btn2"></div>
        <br>


        <script Language = "JavaScript">
            
            function loadJSON () {
                var url = "http://ip-api.com/json";
                var jsonDoc;
                if (window.XMLHttpRequest){
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }else{
                    // code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.open("GET", url, false); //open, send, responseText are
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        jsonDoc = JSON.parse(xmlhttp.responseText);
                        //console.log(jsonDoc);
                    }
                };
                xmlhttp.send();
                return jsonDoc;
            }
            
            var jsondoc = loadJSON();
            console.log(jsondoc)
            latitude = jsondoc.lat;
            longtitude = jsondoc.lon;
            console.log(latitude, longtitude);
            document.getElementById('latitude').value = latitude;
            document.getElementById('longtitude').value = longtitude;
            
            if (jsondoc != ""){
                document.getElementById("search").disabled = false;
            }
            
            function gentable1(jsondata) {
                if(jsondata._embedded) {
                    table1 = "<table id='table1'><tr><th>Date</th><th>Icon</th><th>Event</th><th>Genre</th><th>Venue</th></tr>";
                    event = jsondata._embedded.events;
                    for (var i=0; i<event.length; i++) {

                        eveid = event[i].id;
                        date = event[i].dates;
                        start = date.start;
                        icon = event[i].images;
                        eve = event[i].name;
                        genre = event[i].classifications;
                        venue = event[i]._embedded.venues[0];
                        if(start.localDate) {
                            table1 += "<tr><td>" + start.localDate + "<br>";
                            if(start.localTime) {
                                table1 += start.localTime + "</td><td>";
                            }else{
                                table1 += "</td><td>";
                            }
                        }
                        table1 += "<img src = \""+ icon[0].url +"\"height=\"50\" width=\"80\">" + "</td><td>";
                        table1 += "<a href = \"index.php?eveid=" + eveid + "&venue=" + venue.name + "\">" + eve + "</a>"
                        if(genre){
                            table1 += "</td><td>" + genre[0].segment.name + "</td><td>";
                        }else{
                            table1 += "</td><td>N / A</td><td>";
                        }
                        table1 += "<div class='outside'><input class='mainbtn' type='button' onclick='getmap(this.nextElementSibling)' value=\"" + venue.name + "\"><input type='hidden' value=\"" + event[i]._embedded.venues[0].location.latitude + "\"><input type='hidden' value=\"" + event[i]._embedded.venues[0].location.longitude + "\"><div class='smallmap'></div><div class='mpanel' id='main-panel'><button id='m1' value='WALKING'>Walk there</button><br><button id='m2' value='BICYCLING'>Bike there</button><br><button id='m3' value='DRIVING'>Drive there</button></div></div>"
                        table1 += "</td></tr>";
                    }
                    table1 += "</table>";
                }else{
                    table1 = "<table id='table1' style='background-color:whitesmoke;width:700px;'><tr><td>No Record has been found</td></tr></table>";
                }
                
                
                document.getElementById("maintable").innerHTML = table1;
            }
                

            
            function enInp() {
                document.getElementById("location").disabled = false;
                document.getElementById("location").required = true;
            }

            function disInp() {
                document.getElementById("location").value = "";
                document.getElementById("location").disabled = true;
                 
            }
            
            function clearAll() {

                  

            }

            
            var jsonData = <?php echo json_encode($eobj); ?>;
            console.log(jsonData);
            if (jsonData) {
                gentable1(jsonData);
            }
            
            
            function getmap(mynode) {

                var venlat = mynode.value;
                var venlon = mynode.nextElementSibling.value;
                var mymap =  mynode.nextElementSibling.nextElementSibling;
                var mybtn =  mynode.nextElementSibling.nextElementSibling.nextElementSibling;
                var lat = parseFloat(venlat);
                var lon = parseFloat(venlon);
                var dest = {lat: lat, lng: lon};
                if(mymap.style.display == "block") {
                    mymap.style.display = "none";
                    mybtn.style.display = "none";
                } else {
                    mymap.style.display = "block";
                    mybtn.style.display = "block";
                }
                var startlat = parseFloat(latitude);
                var startlon = parseFloat(longtitude);
                //console.log(startlat);
                //console.log(startlon);
                //console.log(dest);
                initMap0(dest,mymap,startlat,startlon);
                
            }
            
            function initMap0(dest,mymap,stlat,stlon) {
                //alert("inside");
                //console.log(lat);
                //console.log(lon);
                var startlat = stlat;
                var startlon = stlon;
                //var x = mymap.nextElementSibling.childNodes[1].value;
                //alert(x);
                
                
                var directionsDisplay = new google.maps.DirectionsRenderer;
                var directionsService = new google.maps.DirectionsService;
                var map0 = new google.maps.Map(
                    mymap, {zoom: 14, center: dest});
                var marker = new google.maps.Marker({position: dest, map: map0});
                directionsDisplay.setMap(map0);
                
                mymap.nextElementSibling.childNodes[0].addEventListener("click", function() {
                  calculateAndDisplayRoute(directionsService, directionsDisplay, "WALKING", startlat,startlon, dest);
                });

                mymap.nextElementSibling.childNodes[2].addEventListener("click", function() {
                  calculateAndDisplayRoute(directionsService, directionsDisplay, "BICYCLING", startlat,startlon, dest);
                });
                mymap.nextElementSibling.childNodes[4].addEventListener("click", function() {
                  calculateAndDisplayRoute(directionsService, directionsDisplay, "DRIVING", startlat,startlon, dest);
                });
            }
            
            function calculateAndDisplayRoute(directionsService, directionsDisplay, mode, startlat,startlon, dest) {

                var rate_value = mode;
                //alert(rate_value);
                directionsService.route({
                origin: {lat: startlat, lng: startlon},
                destination: dest,
                travelMode: google.maps.TravelMode[rate_value]
                }, function(response, status) {
                if (status == 'OK') {
                directionsDisplay.setDirections(response);
                } else {
                window.alert('Directions request failed due to ' + status);
                }
                });
            }
            
            
            function gentable2(eventdata) {
                table2 = "<h3>" + eventdata.name + "</h3>";
                table2 += "<table id='table2' border='0'><tr><td>";
                if(eventdata.dates) {
                    if(eventdata.dates.start) {
                        table2 += "<h6>Date</h6>";
                        table2 += "<p>" + eventdata.dates.start.localDate + " " + eventdata.dates.start.localTime + "</p>";
                    }
                }
                if(eventdata._embedded) {
                    if(eventdata._embedded.attractions) {
                        table2 += "<h6>Artist/Team</h6>";
                        table2 += "<a href = \"" + eventdata._embedded.attractions[0].url + "\" target='_blank'>" + eventdata._embedded.attractions[0].name + "</a>";
                        if(eventdata._embedded.attractions[1]) {
                            table2 += " | " + "<a href = \"" + eventdata._embedded.attractions[1].url + "\" target='_blank'>" + eventdata._embedded.attractions[1].name + "</a>";
                        }
                        
                    }
                }
                if(eventdata._embedded.venues[0]) {
                    table2 += "<h6>Venues</h6>";
                    table2 += "<p>" + eventdata._embedded.venues[0].name + "</p>";
                }
                if(eventdata.classifications) {
                    table2 += "<h6>Genre</h6>";
                    if(eventdata.classifications[0].subGenre) {
                        table2 += "<p>" + eventdata.classifications[0].subGenre.name + " | ";
                    }
                    if(eventdata.classifications[0].genre) {
                        table2 += eventdata.classifications[0].genre.name + " | ";
                    }
                    if(eventdata.classifications[0].segment) {
                        table2 += eventdata.classifications[0].segment.name + " | ";
                    }
                    if(eventdata.classifications[0].subType) {
                        table2 += eventdata.classifications[0].subType.name + " | ";
                    }
                    if(eventdata.classifications[0].type) {
                        table2 += eventdata.classifications[0].type.name;
                    }
                }
                if(eventdata.priceRanges) {
                    table2 += "</p><h6>Price Ranges</h6>";
                    if(eventdata.priceRanges[0].min == false) {
                        table2 += "<p>(Max_price)" + eventdata.priceRanges[0].max + " " + eventdata.priceRanges[0].currency + "</p>";
                    } else if(eventdata.priceRanges[0].max == false) {
                        table2 += "<p>(Min_price)" + eventdata.priceRanges[0].min + " " + eventdata.priceRanges[0].currency + "</p>";
                    } else {
                        table2 += "<p>" + eventdata.priceRanges[0].min + " - " + eventdata.priceRanges[0].max + " " + eventdata.priceRanges[0].currency + "</p>";
                    }
                }
                if(eventdata.dates) {
                    if(eventdata.dates.status) {
                        table2 += "<h6>Ticket Status</h6>";
                        table2 += "<p>" + eventdata.dates.status.code + "</p>";
                    }
                }
                if(eventdata.url) {
                    table2 += "<h6>Buy Ticket At</h6>";
                    table2 += "<a href = \"" + eventdata.url + "\" target='_blank'>" + "Ticketmaster" + "</a>";
                }
                
                table2 += "</td>";
                if(eventdata.seatmap) {
                    table2 += "<td>";
                    table2 += "<img src = \""+ eventdata.seatmap.staticUrl +"\"height=\"250\" width=\"400\">";
                    table2 += "</tr></table>";
                } else {
                    table2 += "</tr></table>";
                }
                document.getElementById("evetable").innerHTML = table2;
            }
            
            
            
            function gentable3(venuedata) {
                table3 = "<table id='table3'>";
                ven = venuedata;
                if(ven._embedded.venues[0].name) {
                    table3 += "<tr><td><p>Name</p></td>";
                    table3 += "<td>" + ven._embedded.venues[0].name + "</td></tr>";
                }
                if(ven._embedded.venues[0].location) {
                    table3 += "<tr><td><p>Map</p></td><td>";
                    table3 += "<div id='floating-panel'><button id='r1' value='WALKING'>Walk there</button><br><button id='r2' value='BICYCLING'>Bike there</button><br><button id='r3' value='DRIVING'>Drive there</button></div>";
                    table3 += "<div id='map'></div></td></tr>";
                }
                if(ven._embedded.venues[0].address) {
                    table3 += "<tr><td><p>Address</p></td>";
                    table3 += "<td>" + ven._embedded.venues[0].address.line1 + "</td></tr>";
                }
                if(ven._embedded.venues[0].city) {
                    table3 += "<tr><td><p>City</p></td>";
                    table3 += "<td>" + ven._embedded.venues[0].city.name + ", " + ven._embedded.venues[0].state.stateCode + "</td></tr>";
                }
                if(ven._embedded.venues[0].postalCode) {
                    table3 += "<tr><td><p>Postal Code</p></td>";
                    table3 += "<td>" + ven._embedded.venues[0].postalCode + "</td></tr>";
                }
                if(ven._embedded.venues[0].url) {
                    table3 += "<tr><td><p>Upcoming Events</p></td>";
                    table3 += "<td><a href = \"" + ven._embedded.venues[0].url + "\" target='_blank'>" + ven._embedded.venues[0].name + " Tickets" + "</a></td></tr>";
                }
                table3 += "</table>";
                document.getElementById("ventable").innerHTML = table3;
            }

            
            
            
            
            function gentable4(venuedata) {
                table4 = "<table id='table4'>";
                
                if (venuedata._embedded.venues[0].images) {
                    for(var i=0; i<venuedata._embedded.venues[0].images.length; i++) {
                        table4 += "<tr><td><img src = \""+ venuedata._embedded.venues[0].images[i].url +"\"height=\""+venuedata._embedded.venues[0].images[i].height+"\" width=\""+venuedata._embedded.venues[0].images[i].width+"\"></td></tr>";
                    }

                } else {
                    table4 += "<tr><td><p>No Venue Photos Found</p></td></tr>"
                }
                table4 += "</table>";
                document.getElementById("pictable").innerHTML = table4;
            }
            
            
            
            function getinfobtn() {
                insidehtml = "<p id='infoText'>click to show venue info</p><input type='image' id='infobtn' ";
                insidehtml += "src = \"" + "http://csci571.com/hw/hw6/images/arrow_down.png" + "\"height=\"30px\" width=\"50px\"/>"
                insidehtml += "<div id='infodiv' style='display:none'><div class='ventable' id='ventable'></div></div>";
                document.getElementById("btn1").innerHTML = insidehtml;
            }
            
            function getphotobtn() {
                insidehtml = "<p id='photoText'>click to show venue photos</p><input type='image' id='photo' ";
                insidehtml += "src = \"" + "http://csci571.com/hw/hw6/images/arrow_down.png" + "\"height=\"30px\" width=\"50px\"/>"
                insidehtml += "<div id='photodiv' style='display:none'><div class='pictable' id='pictable'></div></div>";
                document.getElementById("btn2").innerHTML = insidehtml;
            }
            
            
            
            
            
            
            var eventData = <?php echo json_encode($nameobj); ?>;
            if(eventData) {
                console.log(eventData);
                gentable2(eventData);
                getinfobtn();
                getphotobtn();
                
                var venphoto = document.getElementById("photo");
                var divphoto = document.getElementById("photodiv");
            
                var veninfo = document.getElementById("infobtn");
                var divinfo = document.getElementById("infodiv");
            
                venphoto.onclick = function photofunc() {
                    if(divphoto.style.display == "block"){
                        divphoto.style.display="none";
                        venphoto.src="http://csci571.com/hw/hw6/images/arrow_down.png";
                        document.getElementById('photoText').innerHTML = "click to show venue photos";
                    } else {
                        divphoto.style.display="block";
                        venphoto.src="http://csci571.com/hw/hw6/images/arrow_up.png";
                        document.getElementById('photoText').innerHTML = "click to hide venue photos";
                        divinfo.style.display="none";
                        veninfo.src="http://csci571.com/hw/hw6/images/arrow_down.png";
                        document.getElementById('infoText').innerHTML = "click to show venue info";
                    }
                    
                }

                veninfo.onclick = function infofunc() {
                    if(divinfo.style.display == "block"){
                        divinfo.style.display="none";
                        veninfo.src="http://csci571.com/hw/hw6/images/arrow_down.png";
                        document.getElementById('infoText').innerHTML = "click to show venue info";
                        
                    } else {
                        divinfo.style.display="block";
                        veninfo.src="http://csci571.com/hw/hw6/images/arrow_up.png";
                        document.getElementById('infoText').innerHTML = "click to hide venue info";
                        divphoto.style.display="none";
                        venphoto.src="http://csci571.com/hw/hw6/images/arrow_down.png";
                        document.getElementById('photoText').innerHTML = "click to show venue info";
                        initMap();
                    }
                }
                
                
            }
            
            var venueData = <?php echo json_encode($venueobj); ?>;
            if(venueData) {
                console.log("followed by venue");
                console.log(venueData);
                var venlat = venueData._embedded.venues[0].location.latitude;
                var venlon = venueData._embedded.venues[0].location.longitude;
                var lat = parseFloat(venlat);
                var lon = parseFloat(venlon);
                var dest = {lat: lat, lng: lon};
                var startlat = parseFloat(latitude);
                var startlon = parseFloat(longtitude);
                function initMap() {
                    //alert("inside");
                    var directionsDisplay = new google.maps.DirectionsRenderer;
                    var directionsService = new google.maps.DirectionsService;
                    var map = new google.maps.Map(
                        document.getElementById('map'), {zoom: 14, center: dest});
                    var marker = new google.maps.Marker({position: dest, map: map});
                    directionsDisplay.setMap(map);
 
                    document.getElementById('r1').addEventListener("click", function() {
                      calculateAndDisplayRoute(directionsService, directionsDisplay, "WALKING");
                    });

                    document.getElementById('r2').addEventListener("click", function() {
                      calculateAndDisplayRoute(directionsService, directionsDisplay, "BICYCLING");
                    });
                    document.getElementById('r3').addEventListener("click", function() {
                      calculateAndDisplayRoute(directionsService, directionsDisplay, "DRIVING");
                    });
                }
                
                function calculateAndDisplayRoute(directionsService, directionsDisplay, mode) {

                    var rate_value = mode;
                    //alert(rate_value);
                    directionsService.route({
                    origin: {lat: startlat, lng: startlon},
                    destination: dest,
                    travelMode: google.maps.TravelMode[rate_value]
                    }, function(response, status) {
                    if (status == 'OK') {
                    directionsDisplay.setDirections(response);
                    } else {
                    window.alert('Directions request failed due to ' + status);
                    }
                    });
                }
                
                
                gentable3(venueData);
                
                gentable4(venueData);
                initMap();
            }
            //initMap();
            
                
    
    
        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGhuj1wNHdTdGIlkWaQSjxEvSfI9xDytQ">
        </script>
    </body>
</html>
