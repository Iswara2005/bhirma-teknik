/**
 * Form kontak: memvalidasi field wajib lalu membuka WhatsApp dengan pesan terisi.
 * Nomor WA dan nama perusahaan dibaca dari atribut data-* pada <form>.
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('waForm');
    if (!form) return;

    const required = [...form.querySelectorAll('[required]')];

    // Hilangkan tanda merah begitu pengunjung mulai mengetik
    required.forEach(el => el.addEventListener('input', () => el.classList.remove('is-invalid')));

    form.addEventListener('submit', e => {
        e.preventDefault();

        const empty = required.filter(el => !el.value.trim());
        required.forEach(el => el.classList.toggle('is-invalid', empty.includes(el)));
        if (empty.length) return;

        const { nama, topik, pesan } = form.elements;
        const lines = [`Halo ${form.dataset.company}, saya ${nama.value.trim()}.`];
        if (topik.value) lines.push(`Bidang: ${topik.value}`);
        lines.push('', pesan.value.trim());
        const text = lines.join('\n');

        open(`https://wa.me/${form.dataset.wa}?text=${encodeURIComponent(text)}`, '_blank', 'noopener');
    });
});
