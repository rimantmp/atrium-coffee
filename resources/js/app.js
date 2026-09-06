document.addEventListener('change', (event) => {
    const checkbox = event.target.closest('[data-menu-check]');
    if (!checkbox) return;
    const card = checkbox.closest('[data-menu-card]');
    card.querySelectorAll('[data-item-field]').forEach((field) => field.disabled = !checkbox.checked);
    card.classList.toggle('ring-2', checkbox.checked);
    card.classList.toggle('ring-coffee-500', checkbox.checked);
});

const sidebar = document.querySelector('[data-sidebar]');
const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');

const setSidebarOpen = (isOpen) => {
    if (!sidebar || !sidebarBackdrop) return;
    sidebar.classList.toggle('-translate-x-full', !isOpen);
    sidebarBackdrop.classList.toggle('hidden', !isOpen);
    document.body.classList.toggle('overflow-hidden', isOpen && window.innerWidth < 1024);
};

document.querySelectorAll('[data-sidebar-open]').forEach((button) => {
    button.addEventListener('click', () => setSidebarOpen(true));
});

document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
    button.addEventListener('click', () => setSidebarOpen(false));
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) setSidebarOpen(false);
});

const tracker = document.querySelector('[data-tracking-url]');
if (tracker) {
    const poll = async () => {
        try {
            const response = await fetch(tracker.dataset.trackingUrl, {headers: {'Accept': 'application/json'}});
            if (!response.ok) return;
            const {data} = await response.json();
            document.querySelector('[data-status-label]').textContent = data.label;
            if (data.queue_number) document.querySelector('[data-queue-number]').textContent = data.queue_number;
            if (['COMPLETED', 'CANCELLED'].includes(data.status)) clearInterval(timer);
        } catch (_) {}
    };
    const timer = setInterval(poll, 5000);
}
