/* LTBS detail page: gallery lightbox, share, admin map. */
window.LTBSDetail = (function () {
    function share() {
        const data = { title: document.title, url: location.href };
        if (navigator.share) navigator.share(data).catch(() => {});
        else { navigator.clipboard?.writeText(location.href); LTBS.toast('Đã sao chép liên kết'); }
    }

    let lb;
    function lightbox(idx) {
        const imgs = window.LTBS_GALLERY || [];
        if (!imgs.length) return;
        if (!lb) {
            lb = document.createElement('div');
            lb.className = 'lightbox';
            lb.innerHTML = '<button class="lb-close" aria-label="Đóng">✕</button><img class="lb-img" alt="">';
            lb.addEventListener('click', (e) => { if (e.target === lb || e.target.classList.contains('lb-close')) close(); });
            document.body.appendChild(lb);
        }
        lb.querySelector('.lb-img').src = imgs[Math.min(idx, imgs.length - 1)];
        lb.style.display = 'flex';
        document.addEventListener('keydown', onKey);
    }
    function close() { if (lb) lb.style.display = 'none'; document.removeEventListener('keydown', onKey); }
    function onKey(e) { if (e.key === 'Escape') close(); }

    return { share, lightbox };
})();

/* Admin-only exact map */
window.LTBSDetailMapInit = function () {
    const c = window.LTBS_DETAIL_LATLNG;
    const el = document.getElementById('detailMap');
    if (!c || !el || !window.google) return;
    const map = new google.maps.Map(el, { center: { lat: c.lat, lng: c.lng }, zoom: 16, mapId: 'DEMO_MAP_ID' });
    new google.maps.Marker({ position: { lat: c.lat, lng: c.lng }, map, title: c.title });
};
