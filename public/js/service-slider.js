/**
 * Slider bidang usaha di beranda: berjalan otomatis, bisa di-drag / swipe /
 * digeser dengan trackpad, dan berhenti sejenak saat kursor di atasnya.
 */
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('bidangScroll');
    if (!track || !track.children.length) return;

    // Pengguna yang mematikan animasi: geser manual saja
    if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
        track.classList.replace('overflow-hidden', 'overflow-x-auto');
        return;
    }

    const SPEED = 40; // piksel per detik (kecilkan = lebih pelan)

    // Gandakan kartu agar putarannya tidak putus
    const originals = [...track.children];
    originals.forEach(card => {
        const clone = card.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        clone.querySelectorAll('a').forEach(a => (a.tabIndex = -1));
        track.append(clone);
    });

    // Gambar tidak ikut ter-drag browser; scroll vertikal halaman tetap normal
    track.querySelectorAll('img').forEach(img => (img.draggable = false));
    Object.assign(track.style, { touchAction: 'pan-y', cursor: 'grab' });

    let pos = 0, loopWidth = 0, last = null;
    let hovering = false, dragging = false, moved = false, startX = 0, startPos = 0;

    const measure = () => {
        loopWidth = track.children[originals.length].offsetLeft - track.children[0].offsetLeft;
    };
    const moveTo = value => {
        if (!loopWidth) return;
        pos = ((value % loopWidth) + loopWidth) % loopWidth; // selalu 0 .. loopWidth
        track.scrollLeft = pos;
    };
    measure();
    addEventListener('resize', measure);

    // Jeda saat kursor (mouse) di atas slider
    [['pointerenter', true], ['pointerleave', false]].forEach(([type, state]) =>
        track.addEventListener(type, e => { if (e.pointerType === 'mouse') hovering = state; }));

    // Drag mouse / swipe jari
    track.addEventListener('pointerdown', e => {
        if (e.pointerType === 'mouse' && e.button !== 0) return;
        dragging = true;
        moved = false;
        startX = e.clientX;
        startPos = pos;
    });
    track.addEventListener('pointermove', e => {
        if (!dragging) return;
        const dx = e.clientX - startX;
        if (!moved && Math.abs(dx) > 5) {
            moved = true;
            track.setPointerCapture(e.pointerId);
            track.style.cursor = 'grabbing';
        }
        if (moved) moveTo(startPos - dx);
    });
    ['pointerup', 'pointercancel'].forEach(type =>
        track.addEventListener(type, () => {
            dragging = false;
            track.style.cursor = 'grab';
        }));

    // Cegah klik link yang tidak sengaja setelah drag
    track.addEventListener('click', e => {
        if (!moved) return;
        e.preventDefault();
        e.stopPropagation();
        moved = false;
    }, true);

    // Scroll horizontal trackpad / shift + scroll mouse
    track.addEventListener('wheel', e => {
        const dx = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : (e.shiftKey ? e.deltaY : 0);
        if (!dx) return;
        e.preventDefault();
        moveTo(pos + dx);
    }, { passive: false });

    // Gerak otomatis
    const step = time => {
        last ??= time;
        if (!hovering && !dragging) moveTo(pos + SPEED * (time - last) / 1000);
        last = time;
        requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
});
