/**
 * Lightbox foto: klik foto -> tampil layar penuh, bisa di-zoom dan digeser.
 *   - Komputer : roda mouse (zoom ke arah kursor), tombol + / -, klik dua kali, drag untuk geser
 *   - HP       : cubit dua jari untuk zoom, ketuk dua kali, geser dengan satu jari
 *   - Keyboard : + / - zoom, 0 pas layar, Esc tutup, panah kiri / kanan pindah foto
 * Tanpa library. Pasang pada tautan:
 *   <a href="foto-besar.jpg" data-lightbox data-caption="Judul foto">...</a>
 * Beberapa foto bisa dikelompokkan agar dapat dipindah dengan panah / geser:
 *   <a ... data-lightbox data-lightbox-group="galeri">...</a>
 * Tanpa JavaScript, tautan itu tetap membuka foto besar.
 *
 * Catatan: foto TIDAK pecah saat di-zoom karena yang dimuat adalah versi besar
 * (sampai 2400 px). Zoom maksimum dibatasi di 100% ukuran asli foto.
 */
(() => {
    let root, stage, img, spinner, pctLabel, captionEl, opener = null;
    let group = [], index = 0;     // foto sebelah-menyebelah (data-lightbox-group)

    let nw = 0, nh = 0;            // ukuran asli foto (px)
    let sw = 0, sh = 0;            // ukuran area tampil (px)
    let fit = 1;                   // skala agar foto muat di layar (terhadap ukuran asli)
    let max = 2;                   // zoom maksimum, terhadap kondisi "pas layar"
    let s = 1, tx = 0, ty = 0;     // zoom & posisi geser saat ini

    const pointers = new Map();    // jari / kursor yang sedang menyentuh
    let start = { x: 0, y: 0, tx: 0, ty: 0 };
    let pinch = null;
    let moved = false;
    let downTarget = null;
    let lastTap = { t: 0, x: 0, y: 0 };

    const isOpen = () => root && !root.hidden;

    function build() {
        root = document.createElement('div');
        root.className = 'lightbox';
        root.hidden = true;
        root.setAttribute('role', 'dialog');
        root.setAttribute('aria-modal', 'true');
        root.setAttribute('aria-label', 'Pratinjau foto');
        root.innerHTML = `
            <div class="lightbox-bar">
                <span class="lightbox-caption"></span>
                <div class="lightbox-tools">
                    <button type="button" data-act="out" aria-label="Perkecil" title="Perkecil (-)"><i class="bi bi-dash-lg"></i></button>
                    <span class="lightbox-pct" aria-live="polite">100%</span>
                    <button type="button" data-act="in" aria-label="Perbesar" title="Perbesar (+)"><i class="bi bi-plus-lg"></i></button>
                    <button type="button" data-act="fit" aria-label="Pas layar" title="Pas layar (0)"><i class="bi bi-arrows-angle-contract"></i></button>
                    <button type="button" data-act="close" aria-label="Tutup" title="Tutup (Esc)"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="lightbox-stage">
                <div class="spinner-border text-light lightbox-spinner" role="status"><span class="visually-hidden">Memuat foto...</span></div>
                <img class="lightbox-img" alt="" draggable="false">
            </div>
            <button type="button" class="lightbox-nav lightbox-prev" data-act="prev" aria-label="Foto sebelumnya"><i class="bi bi-chevron-left"></i></button>
            <button type="button" class="lightbox-nav lightbox-next" data-act="next" aria-label="Foto berikutnya"><i class="bi bi-chevron-right"></i></button>`;
        document.body.append(root);

        stage = root.querySelector('.lightbox-stage');
        img = root.querySelector('.lightbox-img');
        spinner = root.querySelector('.lightbox-spinner');
        pctLabel = root.querySelector('.lightbox-pct');
        captionEl = root.querySelector('.lightbox-caption');

        root.addEventListener('click', e => {
            const act = e.target.closest('button')?.dataset.act;
            if (act === 'close') close();
            else if (act === 'in') zoomBy(1.5);
            else if (act === 'out') zoomBy(1 / 1.5);
            else if (act === 'fit') reset();
            else if (act === 'prev') step(-1);
            else if (act === 'next') step(1);
        });

        stage.addEventListener('wheel', onWheel, { passive: false });
        stage.addEventListener('pointerdown', onDown);
        stage.addEventListener('pointermove', onMove);
        stage.addEventListener('pointerup', onUp);
        stage.addEventListener('pointercancel', onUp);
        document.addEventListener('keydown', onKey);
        window.addEventListener('resize', () => {
            if (!isOpen() || !nw) return;
            const wasFit = s <= 1.001;
            measure();
            if (wasFit) s = 1;
            apply();
        });
    }

    function open(url, caption, trigger) {
        if (!root) build();
        opener = trigger;

        // Foto satu grup bisa dilompati dengan panah; foto yang disembunyikan filter dilewati
        const name = trigger.dataset.lightboxGroup;
        group = name
            ? [...document.querySelectorAll('a[data-lightbox]')].filter(a => a.dataset.lightboxGroup === name && !a.hidden)
            : [trigger];
        index = Math.max(0, group.indexOf(trigger));
        root.classList.toggle('has-nav', group.length > 1);

        root.hidden = false;
        document.body.classList.add('lightbox-open');
        root.querySelector('[data-act="close"]').focus();
        load(url, caption);
    }

    function load(url, caption) {
        captionEl.textContent = (caption || '') + (group.length > 1 ? `  ·  ${index + 1} / ${group.length}` : '');
        pointers.clear();
        pinch = null;
        nw = nh = 0;

        img.style.opacity = '0';
        spinner.hidden = false;

        img.onload = () => {
            nw = img.naturalWidth;
            nh = img.naturalHeight;
            measure();
            s = 1; tx = ty = 0;
            apply();
            spinner.hidden = true;
            img.style.opacity = '1';
        };
        img.onerror = () => {
            spinner.hidden = true;
            captionEl.textContent = 'Foto gagal dimuat.';
        };
        img.src = url;
    }

    function step(dir) {
        if (group.length < 2) return;
        index = (index + dir + group.length) % group.length;
        const next = group[index];
        opener = next;                     // saat ditutup, fokus kembali ke foto terakhir yang dilihat
        load(next.href, next.dataset.caption);
    }

    function close() {
        if (!isOpen()) return;
        root.hidden = true;
        document.body.classList.remove('lightbox-open');
        img.onload = img.onerror = null;
        img.removeAttribute('src');
        pointers.clear();
        if (opener) opener.focus();
    }

    /** Hitung ukuran "pas layar" dan batas zoom. */
    function measure() {
        sw = stage.clientWidth;
        sh = stage.clientHeight;
        fit = Math.min(sw / nw, sh / nh, 1);   // foto kecil tidak diperbesar paksa
        max = Math.max(1 / fit, 2);            // sampai 100% ukuran asli (minimal 2x)
        img.style.width = nw * fit + 'px';
        img.style.height = nh * fit + 'px';
    }

    function clampPan() {
        const w = nw * fit * s;
        const h = nh * fit * s;
        tx = w <= sw ? (sw - w) / 2 : Math.min(0, Math.max(sw - w, tx));
        ty = h <= sh ? (sh - h) / 2 : Math.min(0, Math.max(sh - h, ty));
    }

    function apply() {
        clampPan();
        img.style.transform = `translate(${tx}px, ${ty}px) scale(${s})`;
        pctLabel.textContent = Math.round(fit * s * 100) + '%';   // 100% = ukuran asli
        stage.classList.toggle('is-zoomed', s > 1.001);
    }

    function reset() {
        s = 1; tx = ty = 0;
        apply();
    }

    /** Zoom ke skala baru dengan titik (cx, cy) di layar tetap diam. */
    function zoomAt(cx, cy, next) {
        if (!nw) return;
        next = Math.min(max, Math.max(1, next));
        const r = stage.getBoundingClientRect();
        const x = cx - r.left, y = cy - r.top;
        const ix = (x - tx) / s, iy = (y - ty) / s;   // titik tsb pada foto
        s = next;
        tx = x - ix * s;
        ty = y - iy * s;
        apply();
    }

    function zoomBy(factor) {
        const r = stage.getBoundingClientRect();
        zoomAt(r.left + r.width / 2, r.top + r.height / 2, s * factor);
    }

    function onWheel(e) {
        e.preventDefault();
        const dy = e.deltaMode === 1 ? e.deltaY * 16 : e.deltaY;
        zoomAt(e.clientX, e.clientY, s * Math.exp(-dy * (e.ctrlKey ? 0.01 : 0.0015)));
    }

    const dist = (a, b) => Math.hypot(a.x - b.x, a.y - b.y);
    const mid = (a, b) => ({ x: (a.x + b.x) / 2, y: (a.y + b.y) / 2 });

    function onDown(e) {
        stage.setPointerCapture(e.pointerId);
        pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });

        if (pointers.size === 1) {
            moved = false;
            downTarget = e.target;
            start = { x: e.clientX, y: e.clientY, tx, ty };
        } else if (pointers.size === 2) {
            const [a, b] = [...pointers.values()];
            pinch = { d: dist(a, b) || 1, s, mid: mid(a, b) };
            moved = true;
        }
    }

    function onMove(e) {
        if (!pointers.has(e.pointerId)) return;
        pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });

        if (pointers.size === 2 && pinch) {
            const [a, b] = [...pointers.values()];
            const m = mid(a, b);
            tx += m.x - pinch.mid.x;           // dua jari yang bergeser ikut menggeser foto
            ty += m.y - pinch.mid.y;
            pinch.mid = m;
            zoomAt(m.x, m.y, pinch.s * dist(a, b) / pinch.d);
            return;
        }

        if (pointers.size === 1) {
            const dx = e.clientX - start.x, dy = e.clientY - start.y;
            if (Math.abs(dx) + Math.abs(dy) > 5) moved = true;
            if (s > 1.001) {
                tx = start.tx + dx;
                ty = start.ty + dy;
                apply();
            }
        }
    }

    function onUp(e) {
        if (!pointers.has(e.pointerId)) return;
        pointers.delete(e.pointerId);

        if (pointers.size === 1) {              // sisa satu jari: lanjut geser dari posisi sekarang
            const p = [...pointers.values()][0];
            start = { x: p.x, y: p.y, tx, ty };
            pinch = null;
            moved = true;
            return;
        }
        if (pointers.size === 0 && e.type === 'pointerup') {
            const dx = e.clientX - start.x, dy = e.clientY - start.y;
            if (!moved) onTap(e);
            // Geser satu jari ke samping (saat tidak di-zoom) = foto berikutnya / sebelumnya
            else if (s <= 1.001 && group.length > 1 && Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) step(dx < 0 ? 1 : -1);
        }
    }

    function onTap(e) {
        // Ketuk area gelap di luar foto = tutup
        if (downTarget === stage) { close(); return; }

        // Ketuk dua kali = zoom masuk / kembali pas layar
        const now = Date.now();
        if (now - lastTap.t < 300 && Math.hypot(e.clientX - lastTap.x, e.clientY - lastTap.y) < 30) {
            lastTap.t = 0;
            if (s > 1.001) reset();
            else zoomAt(e.clientX, e.clientY, Math.min(max, 2.5));
        } else {
            lastTap = { t: now, x: e.clientX, y: e.clientY };
        }
    }

    function onKey(e) {
        if (!isOpen()) return;
        if (e.key === 'Escape') close();
        else if (e.key === '+' || e.key === '=') zoomBy(1.5);
        else if (e.key === '-' || e.key === '_') zoomBy(1 / 1.5);
        else if (e.key === '0') reset();
        else if (e.key === 'ArrowLeft') step(-1);
        else if (e.key === 'ArrowRight') step(1);
        else if (e.key === 'Tab') {             // fokus tetap di dalam lightbox
            const btns = [...root.querySelectorAll('button')].filter(b => b.offsetParent !== null);
            const i = btns.indexOf(document.activeElement);
            e.preventDefault();
            btns[(i + (e.shiftKey ? -1 : 1) + btns.length) % btns.length].focus();
        }
    }

    // Satu pendengar untuk semua tautan [data-lightbox], termasuk yang ditambah belakangan
    document.addEventListener('click', e => {
        const link = e.target.closest('a[data-lightbox]');
        if (!link || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey) return;
        e.preventDefault();
        open(link.href, link.dataset.caption, link);
    });
})();