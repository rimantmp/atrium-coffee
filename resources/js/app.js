import Alpine from 'alpinejs';

/**
 * Batas paradigma (jangan dilanggar):
 * - Vanilla `data-*` untuk modul yang sudah jalan dan dikunci test: cart pelanggan
 *   (`data-order-form`), polling tracking, dialog modal admin, toggle kata sandi.
 * - Alpine hanya untuk state presentasional: dropdown akun, drawer mobile, filter,
 *   dan interaksi baru. Satu elemen tidak boleh memakai keduanya sekaligus.
 */
window.Alpine = Alpine;

const orderForm = document.querySelector('[data-order-form]');

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const password = document.getElementById(button.dataset.passwordTarget);
        if (!password) return;

        const isVisible = password.type === 'text';
        password.type = isVisible ? 'password' : 'text';
        button.setAttribute('aria-pressed', String(!isVisible));
        button.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
        button.querySelector('[data-password-visible-icon]').hidden = !isVisible;
        button.querySelector('[data-password-hidden-icon]').hidden = isVisible;
    });
});

const closeModal = (modal) => {
    modal.close();
    document.body.classList.remove('overflow-hidden');
};

document.querySelectorAll('[data-modal-open]').forEach((button) => {
    button.addEventListener('click', () => {
        const modal = document.querySelector(`[data-modal="${button.dataset.modalOpen}"]`);
        if (!modal) return;

        modal.showModal();
        document.body.classList.add('overflow-hidden');
    });
});

document.querySelectorAll('[data-modal]').forEach((modal) => {
    modal.querySelectorAll('[data-modal-close]').forEach((button) => button.addEventListener('click', () => closeModal(modal)));
    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal(modal);
    });
    modal.addEventListener('close', () => document.body.classList.remove('overflow-hidden'));
});

if (orderForm) {
    const cards = [...orderForm.querySelectorAll('[data-menu-card]')];
    const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
    let activeCategory = 'all';

    const formatPrice = (amount) => currency.format(amount).replace(/\s/g, '');
    const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character]);
    const cardFor = (menuId) => cards.find((card) => card.dataset.menuId === menuId);

    const renderCart = () => {
        const selectedCards = cards.filter((card) => card.querySelector('[data-menu-check]')?.checked);
        const cartList = orderForm.querySelector('[data-cart-list]');
        const itemCount = selectedCards.reduce((count, card) => count + Number(card.querySelector('[data-quantity-input]').value), 0);
        const total = selectedCards.reduce((sum, card) => sum + Number(card.dataset.itemPrice) * Number(card.querySelector('[data-quantity-input]').value), 0);

        cartList.innerHTML = selectedCards.map((card) => {
            const quantity = card.querySelector('[data-quantity-input]').value;
            const image = card.dataset.itemImage
                ? `<img src="${escapeHtml(card.dataset.itemImage)}" alt="" class="size-14 rounded-xl object-cover">`
                : `<span class="size-14 shrink-0 rounded-xl bg-[url('/images/atrium-menu-sprite.png')] bg-[length:400%_200%] bg-no-repeat" style="background-position:${card.dataset.itemPosition}"></span>`;

            return `<div class="flex items-center gap-3" data-cart-item="${card.dataset.menuId}">
                ${image}
                <div class="min-w-0 flex-1"><strong class="block truncate text-sm text-slate-900">${escapeHtml(card.dataset.itemName)}</strong><span class="text-sm font-semibold text-indigo-900">${formatPrice(Number(card.dataset.itemPrice))}</span></div>
                <div class="flex h-9 items-center overflow-hidden rounded-xl border border-slate-200">
                    <button type="button" data-cart-decrease class="grid h-full w-8 place-items-center text-slate-700">−</button>
                    <span class="grid h-full min-w-8 place-items-center border-x border-slate-200 text-sm">${quantity}</span>
                    <button type="button" data-cart-increase class="grid h-full w-8 place-items-center text-slate-700">+</button>
                </div>
                <button type="button" data-cart-remove class="grid size-8 place-items-center text-slate-600 hover:text-red-600" aria-label="Hapus ${escapeHtml(card.dataset.itemName)}">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M10 11v6M14 11v6M9 7l1-3h4l1 3m-9 0 1 14h10l1-14"/></svg>
                </button>
            </div>`;
        }).join('');

        orderForm.querySelector('[data-cart-empty]').hidden = selectedCards.length > 0;
        document.querySelectorAll('[data-cart-count], [data-cart-badge]').forEach((element) => element.textContent = itemCount);
        document.querySelectorAll('[data-cart-total], [data-header-total], [data-button-total]').forEach((element) => element.textContent = formatPrice(total));
        document.querySelectorAll('[data-mobile-cart-bar]').forEach((element) => element.hidden = itemCount === 0);
    };

    const setSelected = (card, selected) => {
        const checkbox = card.querySelector('[data-menu-check]');
        checkbox.checked = selected;
        card.querySelectorAll('[data-item-field]').forEach((field) => field.disabled = !selected);
        card.classList.toggle('border-coffee-500', selected);
        card.classList.toggle('ring-1', selected);
        card.classList.toggle('ring-coffee-500', selected);
        card.querySelector('[data-add-label]').textContent = selected ? 'Ditambahkan' : 'Tambah';
        renderCart();
    };

    const updateQuantity = (card, change) => {
        const input = card.querySelector('[data-quantity-input]');
        input.value = Math.min(20, Math.max(1, Number(input.value || 1) + change));
        if (!card.querySelector('[data-menu-check]').checked) setSelected(card, true);
        else renderCart();
    };

    const applyFilters = () => {
        const query = document.querySelector('[data-menu-search]')?.value.trim().toLocaleLowerCase('id') ?? '';
        let visibleCount = 0;

        cards.forEach((card) => {
            const categoryMatches = activeCategory === 'all' || card.closest('[data-menu-category]').dataset.menuCategory === activeCategory;
            const searchMatches = card.dataset.menuName.includes(query);
            card.hidden = !(categoryMatches && searchMatches);
            if (!card.hidden) visibleCount += 1;
        });

        orderForm.querySelectorAll('[data-menu-category]').forEach((section) => {
            section.hidden = ![...section.querySelectorAll('[data-menu-card]')].some((card) => !card.hidden);
        });
        orderForm.querySelector('[data-no-results]').hidden = visibleCount !== 0;
        orderForm.querySelector('[data-menu-count]').textContent = visibleCount;
    };

    orderForm.addEventListener('click', (event) => {
        const card = event.target.closest('[data-menu-card]');
        if (event.target.closest('[data-quantity-decrease]')) updateQuantity(card, -1);
        if (event.target.closest('[data-quantity-increase]')) updateQuantity(card, 1);

        const cartItem = event.target.closest('[data-cart-item]');
        if (cartItem) {
            const sourceCard = cardFor(cartItem.dataset.cartItem);
            if (event.target.closest('[data-cart-decrease]')) updateQuantity(sourceCard, -1);
            if (event.target.closest('[data-cart-increase]')) updateQuantity(sourceCard, 1);
            if (event.target.closest('[data-cart-remove]')) setSelected(sourceCard, false);
        }
    });

    orderForm.addEventListener('change', (event) => {
        const checkbox = event.target.closest('[data-menu-check]');
        if (checkbox) setSelected(checkbox.closest('[data-menu-card]'), checkbox.checked);
        if (event.target.matches('[data-quantity-input]')) renderCart();
    });

    document.querySelectorAll('[data-menu-search]').forEach((input) => input.addEventListener('input', (event) => {
        document.querySelectorAll('[data-menu-search]').forEach((otherInput) => {
            if (otherInput !== event.target) otherInput.value = event.target.value;
        });
        applyFilters();
    }));

    orderForm.querySelectorAll('[data-category-filter]').forEach((button) => button.addEventListener('click', () => {
        activeCategory = button.dataset.categoryFilter;
        orderForm.querySelectorAll('[data-category-filter]').forEach((filter) => filter.classList.toggle('is-active', filter === button));
        applyFilters();
    }));

    cards.forEach((card) => {
        const checkbox = card.querySelector('[data-menu-check]');
        if (checkbox?.checked) setSelected(card, true);
    });
    renderCart();
}

/**
 * Saklar status baris (ketersediaan menu, status akses pengguna) memakai checkbox
 * asli di dalam form PATCH. Tanpa ini, saklar hanya bergerak secara visual dan
 * perubahan tidak pernah tersimpan — lihat antislop C-2.
 */
document.querySelectorAll('[data-quick-toggle] [type="checkbox"]').forEach((checkbox) => {
    checkbox.addEventListener('change', () => checkbox.closest('form').requestSubmit());
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
    const errorBanner = document.querySelector('[data-tracking-error]');

    const poll = async () => {
        try {
            const response = await fetch(tracker.dataset.trackingUrl, {headers: {'Accept': 'application/json'}});
            if (!response.ok) throw new Error('unexpected status');
            const {data} = await response.json();
            document.querySelector('[data-status-label]').textContent = data.label;
            if (data.queue_number) document.querySelector('[data-queue-number]').textContent = data.queue_number;
            if (errorBanner) errorBanner.hidden = true;
            if (['COMPLETED', 'CANCELLED'].includes(data.status)) clearInterval(timer);
        } catch (_) {
            // Tampilkan banner sekali; percobaan berikutnya tetap berjalan.
            if (errorBanner) errorBanner.hidden = false;
        }
    };
    const timer = setInterval(poll, 5000);
    window.addEventListener('pagehide', () => clearInterval(timer));
}

Alpine.start();
