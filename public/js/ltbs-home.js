/* LTBS home page interactions: search tabs, AI toggle, carousel, zoning stub. */
window.LTBSHome = (function () {
    /* ---- Search: Mua/Thuê tabs ---- */
    function setSearchType(btn, ptype) {
        document.querySelectorAll('#heroSearch .tab').forEach((t) => t.classList.remove('on'));
        btn.classList.add('on');
        const f = document.getElementById('heroPtype');
        if (f) f.value = ptype;
    }

    /* ---- Search: classic <-> AI ---- */
    function toggleAi() {
        const form = document.getElementById('heroSearchForm');
        const nlp = document.getElementById('heroNlp');
        const sw = document.getElementById('aiSwitch');
        const on = nlp.style.display === 'none';
        nlp.style.display = on ? 'flex' : 'none';
        form.style.display = on ? 'none' : 'grid';
        sw.textContent = on ? '← Quay lại bộ lọc thường' : '✨ Thử tìm kiếm bằng AI';
    }

    /* ---- AI search: naive parse text -> classic query params.
       TODO(backend): thay bằng NLP thật ở server. ---- */
    function aiSearch() {
        const q = (document.getElementById('nlpInput').value || '').trim();
        const params = new URLSearchParams();
        if (q) params.set('text', q);
        const ptype = document.getElementById('heroPtype').value;
        params.set('property_type', ptype);
        // crude price extraction: "3 tỷ"
        const m = q.match(/(\d+([.,]\d+)?)\s*t[ỷy]/i);
        if (m) {
            const bil = parseFloat(m[1].replace(',', '.')) * 1e9;
            params.set('price-range2', Math.max(0, bil - 1e9) + ';' + (bil + 1e9));
        }
        window.location = '/properties?' + params.toString();
    }

    function mic(btn) {
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SR) { LTBS.toast('Trình duyệt không hỗ trợ nhập giọng nói.'); return; }
        const rec = new SR();
        rec.lang = 'vi-VN';
        btn.classList.add('listening');
        rec.onresult = (e) => { document.getElementById('nlpInput').value = e.results[0][0].transcript; };
        rec.onend = () => btn.classList.remove('listening');
        rec.onerror = () => { btn.classList.remove('listening'); LTBS.toast('Không nhận được giọng nói.'); };
        rec.start();
    }

    /* ---- Zoning check (client stub) ---- */
    function qhTab(btn, tab) {
        document.querySelectorAll('#quyhoach .tab').forEach((t) => t.classList.remove('on'));
        btn.classList.add('on');
        document.querySelectorAll('#quyhoach [data-qhpane]').forEach((p) => {
            p.style.display = p.dataset.qhpane === tab ? '' : 'none';
        });
    }

    function qhCheck() {
        const to = (document.getElementById('qhTo').value || '').trim();
        const thua = (document.getElementById('qhThua').value || '').trim();
        const ward = document.getElementById('qhWard').value || '';
        document.getElementById('qhsToThua').textContent = (to || '—') + ' – ' + (thua || '—');
        document.getElementById('qhsWardVal').textContent = ward || '—';
        // open sheet, show loading, then mock a "clean" result (TODO: real API)
        const overlay = document.getElementById('qhsOverlay');
        overlay.style.display = 'flex';
        document.getElementById('qhsLoading').style.display = 'flex';
        document.getElementById('qhsResult').style.display = 'none';
        setTimeout(() => {
            document.getElementById('qhsLoading').style.display = 'none';
            document.getElementById('qhsResult').style.display = 'block';
        }, 1600);
    }

    function qhClose(e) {
        if (e && e.type === 'click' && e.target.id !== 'qhsOverlay') return;
        document.getElementById('qhsOverlay').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        LTBS.carousel('#hotTrack', { prev: '#hotPrev', next: '#hotNext', step: 340 });
    });

    return { setSearchType, toggleAi, aiSearch, mic, qhTab, qhCheck, qhClose };
})();
