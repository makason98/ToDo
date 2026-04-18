import './bootstrap';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import Sortable from 'sortablejs';

window.Alpine = Alpine;
window.Sortable = Sortable;

Alpine.store('darkMode', {
    on: localStorage.getItem('darkMode') === 'true',
    toggle() {
        this.on = !this.on;
        localStorage.setItem('darkMode', this.on);
        document.documentElement.classList.toggle('dark', this.on);
    }
});

Alpine.directive('datepicker', (el) => {
    flatpickr(el, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'M d, Y',
        allowInput: true,
        disableMobile: true,
    });
});

Alpine.directive('sortable', (el) => {
    Sortable.create(el, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'opacity-30',
        onEnd() {
            const ids = Array.from(el.children).map(item => item.dataset.id).filter(Boolean);
            fetch('/tasks/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ ids }),
            });
        }
    });
});

Alpine.directive('sortable-kanban', (el) => {
    Sortable.create(el, {
        animation: 150,
        group: 'kanban',
        ghostClass: 'opacity-30',
        onEnd(evt) {
            const taskId = evt.item.dataset.id;
            const newStatus = evt.to.dataset.status;
            const oldStatus = evt.from.dataset.status;
            if (!taskId || !newStatus) return;
            if (newStatus === oldStatus) return; // same column, skip

            fetch('/tasks/' + taskId + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status: newStatus }),
            }).then(() => {
                const select = evt.item.querySelector('select[name="status"]');
                if (select) select.value = newStatus;
            });
        }
    });
});

Alpine.start();
