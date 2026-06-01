/**
 * share-map.js — Bản đồ cho trang gửi BĐS cho khách (/s/{code}).
 *
 * Tái sử dụng đúng luồng của theme (js/map-listing.js): InfoBox popup, nút
 * prev/next, link .map-item cuộn lên bản đồ + mở popup marker tương ứng,
 * ZoomControl, nút bật/tắt cuộn. Khác biệt: dữ liệu lấy từ window.SP_SHARE
 * (BĐS thật) thay vì hardcode, icon marker dùng URL tuyệt đối, và link
 * "Xem chi tiết" trong popup cuộn xuống card thay vì mở trang khác.
 *
 * Phụ thuộc: google.maps (nạp ở master), InfoBox (js/map-plugins.js).
 */
(function ($) {
    "use strict";

    function buildPopup(p) {
        var cat = p.category ? '<div class="map-popup-category mp-cat color-bg">' + p.category + '</div>' : '';
        return '' +
            '<div class="map-popup-wrap"><div class="map-popup">' +
            '<div class="map-popup-status mp-cat color-bg">' + p.type + '</div>' +
            cat +
            '<div class="infoBox-close"><i class="fal fa-times"></i></div>' +
            '<a href="#prop-' + p.idx + '" data-goto="' + p.idx + '" class="listing-img-content" style="background-image: url(' + p.img + ')"></a>' +
            '<div class="listing-content"><div class="listing-title">' +
            '<h4><a href="#prop-' + p.idx + '" data-goto="' + p.idx + '">' + p.title + '</a></h4>' +
            '<span class="map-popup-location-info">' + (p.location || '') + '</span>' +
            '</div><span class="map-popup-price fl-wrap">' + p.price + '</span></div>' +
            '</div></div>';
    }

    function scrollToCard(idx) {
        var $card = $('#prop-' + idx);
        if (!$card.length) return;
        $('html, body').animate({ scrollTop: $card.offset().top - 90 }, 500);
        $card.css('transition', 'box-shadow .3s').css('box-shadow', '0 0 0 3px #4DB7FE');
        setTimeout(function () { $card.css('box-shadow', ''); }, 1400);
    }

    function mainMap() {
        var cfg = window.SP_SHARE || {};
        var data = (cfg.properties || []).filter(function (p) { return p.lat != null && p.lng != null; });

        var markerIcon = { url: cfg.markerIcon, anchor: new google.maps.Point(22, 16) };

        // Tâm mặc định: Đà Lạt. Nếu có toạ độ sẽ fitBounds bên dưới.
        var map = new google.maps.Map(document.getElementById('map-main'), {
            zoom: 13,
            scrollwheel: false,
            center: new google.maps.LatLng(11.9404, 108.4583),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: false,
            mapTypeControl: false,
            scaleControl: false,
            panControl: true,
            fullscreenControl: true,
            navigationControl: false,
            streetViewControl: true,
            gestureHandling: 'cooperative',
            styles: [{ featureType: "administrative", elementType: "geometry.fill", stylers: [{ visibility: "on" }, { color: "#ffffff" }] }, { featureType: "administrative", elementType: "labels.text.fill", stylers: [{ gamma: "0.00" }, { weight: "0.01" }, { visibility: "on" }, { color: "#8c8c8c" }] }, { featureType: "administrative.neighborhood", elementType: "labels.text", stylers: [{ visibility: "on" }] }, { featureType: "administrative.neighborhood", elementType: "labels.text.fill", stylers: [{ color: "#898989" }] }, { featureType: "administrative.neighborhood", elementType: "labels.text.stroke", stylers: [{ color: "#ffffff" }, { weight: "4.00" }] }, { featureType: "landscape", elementType: "all", stylers: [{ color: "#ffffff" }] }, { featureType: "landscape.man_made", elementType: "geometry.fill", stylers: [{ visibility: "simplified" }, { color: "#ffffff" }] }, { featureType: "landscape.natural", elementType: "geometry", stylers: [{ visibility: "on" }] }, { featureType: "landscape.natural", elementType: "labels.text.fill", stylers: [{ color: "#8d8d8d" }] }, { featureType: "landscape.natural.terrain", elementType: "geometry.stroke", stylers: [{ visibility: "on" }] }, { featureType: "poi", elementType: "all", stylers: [{ visibility: "off" }] }, { featureType: "poi.park", elementType: "geometry.fill", stylers: [{ color: "#cef8d5" }, { visibility: "on" }] }, { featureType: "poi.park", elementType: "labels.text.fill", stylers: [{ visibility: "on" }, { color: "#60b36c" }] }, { featureType: "poi.park", elementType: "labels.text.stroke", stylers: [{ visibility: "on" }, { color: "#ffffff" }] }, { featureType: "poi.park", elementType: "labels.icon", stylers: [{ visibility: "off" }] }, { featureType: "road", elementType: "all", stylers: [{ saturation: "-100" }, { lightness: "32" }, { visibility: "on" }] }, { featureType: "road", elementType: "geometry.fill", stylers: [{ color: "#f3f3f3" }] }, { featureType: "road", elementType: "geometry.stroke", stylers: [{ color: "#e1e1e1" }] }, { featureType: "road", elementType: "labels.text", stylers: [{ visibility: "on" }] }, { featureType: "road.highway", elementType: "all", stylers: [{ visibility: "simplified" }] }, { featureType: "road.highway", elementType: "geometry", stylers: [{ visibility: "on" }, { lightness: "63" }] }, { featureType: "road.highway", elementType: "geometry.fill", stylers: [{ color: "#f3f3f3" }] }, { featureType: "road.highway", elementType: "geometry.stroke", stylers: [{ color: "#e1e1e1" }] }, { featureType: "road.highway", elementType: "labels.text", stylers: [{ visibility: "off" }] }, { featureType: "road.highway", elementType: "labels.icon", stylers: [{ visibility: "off" }] }, { featureType: "road.arterial", elementType: "labels.icon", stylers: [{ visibility: "off" }] }, { featureType: "transit", elementType: "all", stylers: [{ visibility: "off" }] }, { featureType: "transit.station", elementType: "all", stylers: [{ visibility: "off" }] }, { featureType: "water", elementType: "all", stylers: [{ visibility: "on" }, { color: "#eeeeee" }] }, { featureType: "water", elementType: "geometry.fill", stylers: [{ color: "#cce4ff" }] }, { featureType: "water", elementType: "labels.text.fill", stylers: [{ visibility: "on" }, { color: "#6095a5" }] }]
        });

        var boxText = document.createElement("div");
        boxText.className = 'map-box';
        var currentInfobox;
        var boxOptions = {
            content: boxText,
            disableAutoPan: true,
            alignBottom: true,
            maxWidth: 0,
            pixelOffset: new google.maps.Size(-110, -45),
            zIndex: null,
            boxStyle: { width: "260px" },
            closeBoxMargin: "0",
            closeBoxURL: "",
            infoBoxClearance: new google.maps.Size(1, 1),
            isHidden: false,
            pane: "floatPane",
            enableEventPropagation: false
        };

        var allMarkers = [];
        var bounds = new google.maps.LatLngBounds();
        var ib = new InfoBox();

        // marker.idx = số thứ tự (1-based) khớp id="prop-N" và href="#N" của card.
        data.forEach(function (p) {
            var pos = new google.maps.LatLng(p.lat, p.lng);
            var marker = new google.maps.Marker({ position: pos, icon: markerIcon, id: p.idx });
            marker.setMap(map);
            bounds.extend(pos);
            allMarkers.push(marker);

            google.maps.event.addListener(marker, 'click', (function (marker, p, pos) {
                return function () {
                    ib.setOptions(boxOptions);
                    boxText.innerHTML = buildPopup(p);
                    ib.close();
                    ib.open(map, marker);
                    currentInfobox = p.idx;
                    map.panTo(pos);
                    map.panBy(0, -160);
                    google.maps.event.addListener(ib, 'domready', function () {
                        $('.infoBox-close').off('click').on('click', function (e) { e.preventDefault(); ib.close(); });
                        $('.map-box [data-goto]').off('click').on('click', function (e) {
                            e.preventDefault();
                            scrollToCard($(this).data('goto'));
                        });
                    });
                };
            })(marker, p, pos));
        });

        if (allMarkers.length === 1) {
            map.setCenter(allMarkers[0].getPosition());
            map.setZoom(15);
        } else if (allMarkers.length > 1) {
            map.fitBounds(bounds);
        }

        google.maps.event.addDomListener(window, "resize", function () {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);
        });

        // Điều hướng marker kế/trước (theo thứ tự markers có toạ độ).
        function triggerAt(i) {
            if (!allMarkers.length) return;
            var n = allMarkers.length;
            var k = ((i % n) + n) % n;
            google.maps.event.trigger(allMarkers[k], 'click');
        }
        function indexOfCurrent() {
            for (var i = 0; i < allMarkers.length; i++) {
                if (allMarkers[i].id === currentInfobox) return i;
            }
            return -1;
        }
        $('.nextmap-nav').on("click", function (e) { e.preventDefault(); map.setZoom(15); triggerAt(indexOfCurrent() + 1); });
        $('.prevmap-nav').on("click", function (e) {
            e.preventDefault(); map.setZoom(15);
            var cur = indexOfCurrent();
            triggerAt(cur < 0 ? allMarkers.length - 1 : cur - 1);
        });

        // Click địa chỉ trong card → cuộn lên bản đồ + mở popup marker tương ứng.
        $('.map-item').on("click", function (e) {
            e.preventDefault();
            var idx = parseInt($(this).attr('href').split('#')[1], 10);
            map.setZoom(15);
            for (var i = 0; i < allMarkers.length; i++) {
                if (allMarkers[i].id === idx) { google.maps.event.trigger(allMarkers[i], "click"); break; }
            }
            $('html, body').animate({ scrollTop: $(".map-container").offset().top - 70 }, 500);
        });

        // Nút bật/tắt cuộn bản đồ (giống theme).
        var scrollEnabling = $('.scrollContorl');
        $(scrollEnabling).click(function (e) {
            e.preventDefault();
            $(this).toggleClass("enabledsroll");
            map.setOptions({ 'scrollwheel': $(this).is(".enabledsroll") });
        });

        // ZoomControl tuỳ chỉnh (đặt trong scope như map-listing/map-single của theme).
        var zoomControlDiv = document.createElement('div');
        (function ZoomControl(controlDiv, map) {
            zoomControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(zoomControlDiv);
            controlDiv.style.padding = '5px';
            var controlWrapper = document.createElement('div');
            controlDiv.appendChild(controlWrapper);
            var zoomInButton = document.createElement('div');
            zoomInButton.className = "mapzoom-in";
            controlWrapper.appendChild(zoomInButton);
            var zoomOutButton = document.createElement('div');
            zoomOutButton.className = "mapzoom-out";
            controlWrapper.appendChild(zoomOutButton);
            google.maps.event.addDomListener(zoomInButton, 'click', function () { map.setZoom(map.getZoom() + 1); });
            google.maps.event.addDomListener(zoomOutButton, 'click', function () { map.setZoom(map.getZoom() - 1); });
        })(zoomControlDiv, map);
    }

    var mapEl = document.getElementById('map-main');
    // Chỉ init khi có toạ độ; tránh khởi tạo map rỗng.
    var hasGeo = (window.SP_SHARE && (window.SP_SHARE.properties || []).some(function (p) { return p.lat != null && p.lng != null; }));
    if (mapEl != null && hasGeo) {
        if (window.google && google.maps && google.maps.Map) {
            mainMap();
        } else {
            // Maps nạp async ở master: chờ window load như map-single.js của theme.
            window.addEventListener('load', function () {
                if (window.google && google.maps && google.maps.Map) { mainMap(); }
            });
        }
    }
})(window.jQuery);
