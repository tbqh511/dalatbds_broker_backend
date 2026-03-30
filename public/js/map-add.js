function singleMap() {
    var myLatLng = {
        lng: $('#singleMap').data('longitude'),
        lat: $('#singleMap').data('latitude'),
    };
    var single_map = new google.maps.Map(document.getElementById('singleMap'), {
        zoom: 14,
        center: myLatLng,
        scrollwheel: false,
        zoomControl: false,
        fullscreenControl: true,
        mapTypeControl: false,
        scaleControl: false,
        panControl: false,
        navigationControl: false,
        streetViewControl: true,
        mapId: 'DEMO_MAP_ID',
        styles: [{
            "featureType": "landscape",
            "elementType": "all",
            "stylers": [{
                "color": "#f2f2f2"
            }]
        }]
    });

    var markerEl = document.createElement('img');
    markerEl.src = 'images/marker2.png';
    markerEl.style.width = '35px';
    markerEl.style.height = '35px';

    var marker = new google.maps.marker.AdvancedMarkerElement({
        position: myLatLng,
        map: single_map,
        content: markerEl,
        gmpDraggable: true,
        title: 'Your location'
    });

    marker.addListener('gmp-dragend', function () {
        var pos = marker.position;
        document.getElementById("lat").value = typeof pos.lat === 'function' ? pos.lat() : pos.lat;
        document.getElementById("long").value = typeof pos.lng === 'function' ? pos.lng() : pos.lng;
    });

    var zoomControlDiv = document.createElement('div');
    var zoomControl = new ZoomControl(zoomControlDiv, single_map);

    function ZoomControl(controlDiv, single_map) {
        zoomControlDiv.index = 1;
        single_map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(zoomControlDiv);
        controlDiv.style.padding = '5px';
        var controlWrapper = document.createElement('div');
        controlDiv.appendChild(controlWrapper);
        var zoomInButton = document.createElement('div');
        zoomInButton.className = "mapzoom-in";
        controlWrapper.appendChild(zoomInButton);
        var zoomOutButton = document.createElement('div');
        zoomOutButton.className = "mapzoom-out";
        controlWrapper.appendChild(zoomOutButton);
        zoomInButton.addEventListener('click', function () {
            single_map.setZoom(single_map.getZoom() + 1);
        });
        zoomOutButton.addEventListener('click', function () {
            single_map.setZoom(single_map.getZoom() - 1);
        });
    }
}
var head = document.getElementsByTagName('head')[0];
var insertBefore = head.insertBefore;
head.insertBefore = function (newElement, referenceElement) {
    if (newElement.href && newElement.href.indexOf('https://fonts.googleapis.com/css?family=Roboto') === 0) {
        return;
    }
    insertBefore.call(head, newElement, referenceElement);
};
var single_map = document.getElementById('singleMap');
if (typeof (single_map) != 'undefined' && single_map != null) {
    window.addEventListener('load', singleMap);
}
