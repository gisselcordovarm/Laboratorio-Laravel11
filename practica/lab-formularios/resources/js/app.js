import './bootstrap';

/**
 * Mejoras progresivas de la vista (solo navegador):
 *  - contador de caracteres
 *  - zona de carga con arrastrar y soltar
 *  - vista previa de la imagen
 *  - confirmación al salir con el formulario sucio
 *  - foco en el primer campo con error
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-product-form]');

    // Contadores de caracteres -------------------------------------------------
    document.querySelectorAll('[data-counter-for]').forEach((output) => {
        const input = document.getElementById(output.dataset.counterFor);
        if (!input) return;

        const max = input.getAttribute('maxlength') ?? '';
        const update = () => {
            output.textContent = `${input.value.length}${max ? `/${max}` : ''}`;
        };

        input.addEventListener('input', update);
        update();
    });

    // Zona de archivo ---------------------------------------------------------
    const dropzone = document.querySelector('[data-dropzone]');
    const fileInput = document.querySelector('[data-file-input]');
    const fileMeta = document.querySelector('[data-file-meta]');
    const fileThumb = document.querySelector('[data-file-thumb]');
    const fileClear = document.querySelector('[data-file-clear]');

    if (dropzone && fileInput) {
        const showFile = () => {
            const file = fileInput.files?.[0];
            dropzone.classList.toggle('has-file', Boolean(file));
            fileClear?.classList.toggle('hidden', !file);
            fileClear?.classList.toggle('grid', Boolean(file));

            if (!file) {
                if (fileMeta) fileMeta.textContent = '';
                if (fileThumb) {
                    fileThumb.removeAttribute('src');
                    fileThumb.classList.add('hidden');
                }
                return;
            }

            const size = file.size < 1024 * 1024
                ? `${Math.max(1, Math.round(file.size / 1024))} KB`
                : `${(file.size / 1024 / 1024).toFixed(2)} MB`;

            if (fileMeta) fileMeta.textContent = `${file.name} · ${size}`;

            if (fileThumb && file.type.startsWith('image/')) {
                fileThumb.src = URL.createObjectURL(file);
                fileThumb.classList.remove('hidden');
            }
        };

        fileInput.addEventListener('change', showFile);

        ['dragenter', 'dragover'].forEach((event) => {
            dropzone.addEventListener(event, (e) => {
                e.preventDefault();
                dropzone.classList.add('is-dragging');
            });
        });

        ['dragleave', 'drop'].forEach((event) => {
            dropzone.addEventListener(event, (e) => {
                e.preventDefault();
                dropzone.classList.remove('is-dragging');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const files = e.dataTransfer?.files;
            if (!files?.length) return;

            const transfer = new DataTransfer();
            transfer.items.add(files[0]);
            fileInput.files = transfer.files;
            showFile();
        });

        fileClear?.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.value = '';
            showFile();
            fileInput.focus();
        });

        showFile();
    }

    // Foco inteligente en el primer error -------------------------------------
    const firstInvalid = document.querySelector('[aria-invalid="true"]');
    if (firstInvalid) {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        firstInvalid.focus({ preventScroll: true });
        firstInvalid.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
    }

    // Evitar pérdida accidental de datos --------------------------------------
    if (form) {
        let dirty = false;
        form.addEventListener('input', () => { dirty = true; });
        form.addEventListener('submit', () => { dirty = false; });

        window.addEventListener('beforeunload', (e) => {
            if (!dirty) return;
            e.preventDefault();
            e.returnValue = '';
        });
    }
});
