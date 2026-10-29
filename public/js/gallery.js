/**
 * Galeri foto: filter kategori, susunan grid, bar filter yang menempel di bawah navbar,
 * dan animasi muncul saat di-scroll. Lightbox ada di public/js/lightbox.js.
 * Tanpa JavaScript, semua foto tetap tampil dan bisa dibuka lewat tautannya.
 */
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;

    const tiles = [...grid.querySelectorAll('.gallery-tile')];
    const filter = document.querySelector('.gallery-filter');
    const navbar = document.querySelector('.navbar-bt');
    const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

    /** Tinggi navbar dipakai CSS (--nav-h) agar bar filter pas menempel di bawahnya. */
    const syncNavHeight = () => {
        if (!navbar) return;
        document.documentElement.style.setProperty('--nav-h', navbar.offsetHeight + 'px');
    };
    syncNavHeight();
    addEventListener('resize', syncNavHeight, { passive: true });
    if (navbar && 'ResizeObserver' in window) new ResizeObserver(syncNavHeight).observe(navbar);

    /** Jumlah kolom grid saat ini (4 di komputer, 3 di tablet, 2 di HP; diatur CSS). */
    const columnCount = () => getComputedStyle(grid).gridTemplateColumns.split(' ').length;

    /**
     * Susunan grid:
     *   - semua foto berukuran sama (1 kolom), diisi dari kiri ke kanan lalu turun ke bawah
     *   - foto pertama dibuat 2 kolom sebagai foto utama, khusus bila ada 5 foto atau lebih
     *   - foto yang tersisa di baris terakhir tetap berukuran normal (tidak diregangkan)
     */
    const layout = () => {
        tiles.forEach(tile => { tile.style.gridColumn = ''; });

        const visible = tiles.filter(tile => !tile.hidden);
        if (visible.length >= 5 && columnCount() >= 2) {
            visible[0].style.gridColumn = 'span 2';
        }
    };

    const observer = !reduceMotion && 'IntersectionObserver' in window
        ? new IntersectionObserver(entries => {
              entries.forEach(entry => {
                  if (!entry.isIntersecting) return;
                  entry.target.classList.add('is-in');
                  observer.unobserve(entry.target);
              });
          }, { rootMargin: '0px 0px -8% 0px' })
        : null;

    /** Munculkan foto yang tampil, bergiliran per baris (jeda 70 ms). */
    const reveal = () => {
        let n = 0;
        tiles.forEach(tile => {
            if (tile.hidden) return;
            if (!observer) { tile.classList.add('is-in'); return; }
            tile.classList.remove('is-in');
            tile.style.setProperty('--d', (n++ % 4) * 70 + 'ms');
            observer.observe(tile);
        });
    };

    grid.classList.add('is-ready');
    layout();
    reveal();

    // Susun ulang saat jumlah kolom berubah (putar layar, ubah ukuran jendela)
    let resizeFrame;
    addEventListener('resize', () => {
        cancelAnimationFrame(resizeFrame);
        resizeFrame = requestAnimationFrame(layout);
    }, { passive: true });

    if (!filter) return;
    filter.hidden = false;

    /** Beri garis tipis + bayangan pada bar filter saat sedang menempel. */
    let ticking = false;
    const updateStuck = () => {
        const top = parseFloat(getComputedStyle(filter).top) || 0;
        filter.classList.toggle('is-stuck', filter.getBoundingClientRect().top <= top + 1);
        ticking = false;
    };
    addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(updateStuck);
    }, { passive: true });
    updateStuck();

    /** Setelah filter diganti, naikkan halaman ke awal grid kalau posisinya sudah jauh di bawah. */
    const scrollToGrid = () => {
        const offset = (navbar ? navbar.offsetHeight : 0) + filter.offsetHeight + 16;
        const y = grid.getBoundingClientRect().top + scrollY - offset;
        if (scrollY > y) scrollTo({ top: y, behavior: reduceMotion ? 'auto' : 'smooth' });
    };

    filter.addEventListener('click', event => {
        const chip = event.target.closest('.gallery-chip');
        if (!chip) return;

        filter.querySelectorAll('.gallery-chip').forEach(c => {
            const active = c === chip;
            c.classList.toggle('is-active', active);
            c.setAttribute('aria-pressed', String(active));
        });

        // Di HP, pastikan tombol yang dipilih terlihat penuh di dalam bar yang bisa digeser
        chip.scrollIntoView({ block: 'nearest', inline: 'center', behavior: reduceMotion ? 'auto' : 'smooth' });

        const value = chip.dataset.filter;
        tiles.forEach(tile => {
            tile.hidden = value !== '*' && tile.dataset.category !== value;
        });
        layout();
        reveal();
        scrollToGrid();
    });
});