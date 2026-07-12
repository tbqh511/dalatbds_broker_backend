/* ===================================================================
   LTBS core JS — shared runtime for the new desktop frontend.
   Vanilla JS (no framework). Page-specific logic lives in ltbs-*.js.
   =================================================================== */
window.LTBS = (function () {
    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ---- Toast ---- */
    function toast(msg, ms = 2600) {
        const wrap = document.getElementById('ltbsToastWrap');
        if (!wrap) { alert(msg); return; }
        const el = document.createElement('div');
        el.className = 'ltbs-toast';
        el.textContent = msg;
        wrap.appendChild(el);
        requestAnimationFrame(() => el.classList.add('on'));
        setTimeout(() => {
            el.classList.remove('on');
            setTimeout(() => el.remove(), 300);
        }, ms);
    }

    /* ---- Favourite toggle (webapp session only) ---- */
    async function toggleFav(id, el) {
        try {
            const res = await fetch('/webapp/favourite/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ property_id: id }),
            });
            if (res.status === 401 || res.redirected) {
                toast('Vui lòng mở app để lưu tin.');
                return;
            }
            const data = await res.json().catch(() => ({}));
            const on = data.liked ?? el.classList.toggle('on');
            el.classList.toggle('on', !!on);
            toast(on ? 'Đã lưu tin' : 'Đã bỏ lưu');
        } catch (e) {
            toast('Không lưu được, thử lại sau.');
        }
    }

    /* ---- Horizontal carousel (prev/next) ---- */
    function carousel(trackSel, opts = {}) {
        const track = document.querySelector(trackSel);
        if (!track) return;
        const step = opts.step || 320;
        const prev = opts.prev && document.querySelector(opts.prev);
        const next = opts.next && document.querySelector(opts.next);
        const sync = () => {
            if (prev) prev.disabled = track.scrollLeft <= 4;
            if (next) next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
        };
        prev && prev.addEventListener('click', () => track.scrollBy({ left: -step, behavior: 'smooth' }));
        next && next.addEventListener('click', () => track.scrollBy({ left: step, behavior: 'smooth' }));
        track.addEventListener('scroll', sync, { passive: true });
        sync();
    }

    /* ---- Number formatting (VN) ---- */
    function fmtMoney(n) {
        if (n >= 1e9) return (n / 1e9).toFixed(n % 1e9 === 0 ? 0 : 1).replace('.', ',') + ' tỷ';
        if (n >= 1e6) return Math.round(n / 1e6) + ' triệu';
        return n.toLocaleString('vi-VN');
    }

    /* ---- Accordion (1-open) ---- */
    function accordion(rootSel) {
        document.querySelectorAll(rootSel + ' .faq-q').forEach((btn) => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.faq-item');
                const wrap = item.querySelector('.faq-a-wrap');
                const chev = btn.querySelector('.faq-chev');
                const open = wrap.classList.contains('open');
                document.querySelectorAll(rootSel + ' .faq-a-wrap.open').forEach((w) => w.classList.remove('open'));
                document.querySelectorAll(rootSel + ' .faq-chev.open').forEach((c) => c.classList.remove('open'));
                if (!open) { wrap.classList.add('open'); chev.classList.add('open'); }
            });
        });
    }

    return { toast, toggleFav, carousel, fmtMoney, accordion, csrf };
})();
