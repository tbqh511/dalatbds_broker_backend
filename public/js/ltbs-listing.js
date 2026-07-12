/* LTBS listing page: segment/room filters, list<->map toggle, Google Maps pins. */
window.LTBSList = (function () {
    function submitForm() { document.getElementById('plForm').requestSubmit?.() || document.getElementById('plForm').submit(); }

    function setType(btn, val) {
        document.querySelectorAll('.segbar .seg').forEach((s) => s.classList.remove('on'));
        btn.classList.add('on');
        document.getElementById('plPtype').value = val;
        submitForm();
    }

    function setRoom(btn, val) {
        btn.parentElement.querySelectorAll('.seg').forEach((s) => s.classList.remove('on'));
        btn.classList.add('on');
        document.getElementById('plRoom').value = val;
    }

    let mapReady = false;
    function view(mode) {
        const list = document.getElementById('viewList');
        const map = document.getElementById('viewMap');
        const lb = document.getElementById('viewListBtn');
        const mb = document.getElementById('viewMapBtn');
        if (mode === 'map') {
            list.style.display = 'none'; map.style.display = 'block';
            lb.classList.remove('on'); mb.classList.add('on');
            if (!mapReady) initMap();
        } else {
            list.style.display = 'block'; map.style.display = 'none';
            mb.classList.remove('on'); lb.classList.add('on');
        }
    }

    function initMap() {
        if (!window.google || !google.maps) return;
        const el = document.getElementById('plMap');
        const center = { lat: 11.9404, lng: 108.4583 }; // Đà Lạt
        const map = new google.maps.Map(el, { center, zoom: 13, mapId: 'DEMO_MAP_ID' });
        const props = (window.LTBS_PROPS || []).filter((p) => p.lat && p.lng);
        const bounds = new google.maps.LatLngBounds();
        props.forEach((p) => {
            const pos = { lat: p.lat, lng: p.lng };
            const marker = new google.maps.Marker({ position: pos, map, title: p.title });
            const info = new google.maps.InfoWindow({
                content: `<div style="font-weight:600;max-width:200px">${p.title}</div><div style="color:#3270fc;font-weight:700;margin:4px 0">${p.price}</div><a href="${p.url}" style="color:#3270fc">Xem chi tiết →</a>`,
            });
            marker.addListener('click', () => info.open(map, marker));
            bounds.extend(pos);
        });
        if (props.length) map.fitBounds(bounds);
        mapReady = true;
    }

    return { setType, setRoom, view, initMap };
})();

/* Google Maps async callback (no-op until user opens map view) */
window.LTBSListMapInit = function () { /* map inits lazily on view('map') */ };
