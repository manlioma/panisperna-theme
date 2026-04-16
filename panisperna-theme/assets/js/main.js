/**
 * Panisperna Libreria - Main JS
 */

document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu
    var menuToggle = document.querySelector('.menu-toggle');
    var mobileMenu = document.getElementById('mobile-menu');
    var menuClose = document.querySelector('.mobile-menu__close');

    function openMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', openMobileMenu);
    }

    if (menuClose) {
        menuClose.addEventListener('click', closeMobileMenu);
    }

    // Close mobile menu on link click
    if (mobileMenu) {
        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMobileMenu);
        });
    }

    // Hero Swiper
    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            slidesPerView: 1,
            spaceBetween: 4,
            grabCursor: true,
            loop: true,
            breakpoints: {
                480: {
                    slidesPerView: 1.5,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3.5,
                },
            },
        });
    }

    // Libreria Swiper (photo carousels)
    document.querySelectorAll('.libreria-swiper').forEach(function (el) {
        new Swiper(el, {
            slidesPerView: 1,
            spaceBetween: 4,
            grabCursor: true,
            loop: true,
            breakpoints: {
                768: {
                    slidesPerView: 1.2,
                },
            },
        });
    });

    // Quantity +/- buttons
    document.querySelectorAll('.product-hero__qty').forEach(function (qtyWrap) {
        var minus = qtyWrap.querySelector('.qty-minus');
        var plus = qtyWrap.querySelector('.qty-plus');
        var value = qtyWrap.querySelector('.qty-value');
        if (!minus || !plus || !value) return;

        minus.addEventListener('click', function () {
            var current = parseInt(value.textContent) || 1;
            if (current > 1) {
                value.textContent = current - 1;
            }
        });

        plus.addEventListener('click', function () {
            var current = parseInt(value.textContent) || 1;
            value.textContent = current + 1;
        });
    });

    // AJAX Add to Cart — named handler so it can be rebound on dynamically loaded cards
    function handleAjaxAddToCart(e) {
        e.preventDefault();
        e.stopPropagation();

        var productId = this.dataset.productId;
        if (!productId) return;

        var button = this;
        var originalText = button.textContent;
        button.textContent = '...';
        button.style.pointerEvents = 'none';

        // Get quantity from qty selector if present
        var qtyValue = 1;
        var qtyEl = document.querySelector('.qty-value');
        if (qtyEl) {
            qtyValue = parseInt(qtyEl.textContent) || 1;
        }

        fetch('/?wc-ajax=add_to_cart', {
            method: 'POST',
            body: new URLSearchParams({
                product_id: productId,
                quantity: qtyValue,
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.error) {
                button.textContent = '!';
            } else {
                button.textContent = '✓';
                // Trigger WC cart fragments refresh (updates badge + sidebar)
                jQuery(document.body).trigger('wc_fragment_refresh');
            }
            // Open sidebar cart after fragments have refreshed
            jQuery(document.body).one('wc_fragments_refreshed', function () {
                openSidebarCart();
            });
            setTimeout(function () {
                button.textContent = originalText;
                button.style.pointerEvents = '';
            }, 1500);
        })
        .catch(function () {
            button.textContent = originalText;
            button.style.pointerEvents = '';
        });
    }

    document.querySelectorAll('.ajax-add-to-cart').forEach(function (btn) {
        btn.addEventListener('click', handleAjaxAddToCart);
    });

    // Collezione — Category filter chips (SHOP-05)
    var chips = document.querySelectorAll('.btn--chip');
    var grid = document.querySelector('.cards-grid--collezione');
    var loadMoreBtn = document.getElementById('collezione-load-more');

    if (chips.length && grid) {
        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                // Update active state (D-12)
                chips.forEach(function (c) { c.classList.remove('is-active'); });
                this.classList.add('is-active');

                var category = this.dataset.category;
                grid.dataset.category = category;
                grid.dataset.currentPage = '1';

                // Fetch page 1 for selected category
                fetchCollezione(category, 1, false);
            });
        });
    }

    // Collezione — Load more (SHOP-06)
    if (loadMoreBtn && grid) {
        loadMoreBtn.addEventListener('click', function () {
            var currentPage = parseInt(grid.dataset.currentPage) || 1;
            var category = grid.dataset.category || 'tutti';
            fetchCollezione(category, currentPage + 1, true);
        });
    }

    // Pacchetti — Load more
    var pacchettiGrid = document.getElementById('pacchetti-grid');
    var pacchettiLoadMore = document.getElementById('pacchetti-load-more');

    if (pacchettiLoadMore && pacchettiGrid) {
        pacchettiLoadMore.addEventListener('click', function () {
            var currentPage = parseInt(pacchettiGrid.dataset.currentPage) || 1;
            pacchettiLoadMore.textContent = '...';
            pacchettiLoadMore.disabled = true;

            var body = new URLSearchParams({
                action: 'panisperna_pacchetti_loadmore',
                nonce: panisperna_ajax.nonce,
                page: currentPage + 1,
            });

            fetch(panisperna_ajax.ajax_url, { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (response) {
                if (response.success) {
                    pacchettiGrid.insertAdjacentHTML('beforeend', response.data.html);
                    pacchettiGrid.dataset.currentPage = String(currentPage + 1);

                    if (!response.data.has_more) {
                        var wrap = pacchettiLoadMore.closest('.load-more-wrap');
                        if (wrap) wrap.style.display = 'none';
                    }
                }
            })
            .finally(function () {
                pacchettiLoadMore.textContent = 'Carica altri contenuti';
                pacchettiLoadMore.disabled = false;
            });
        });
    }

    // Consiglio (Parola di) — Load more (PH8-PAROLA)
    var consiglioGrid = document.querySelector('.cards-grid--consiglio');
    var consiglioLoadMore = document.getElementById('consiglio-load-more');

    if (consiglioLoadMore && consiglioGrid) {
        consiglioLoadMore.addEventListener('click', function () {
            var currentPage = parseInt(consiglioGrid.dataset.currentPage) || 1;
            consiglioLoadMore.textContent = '...';
            consiglioLoadMore.disabled = true;

            var body = new URLSearchParams({
                action: 'panisperna_consiglio_loadmore',
                nonce: panisperna_ajax.nonce,
                page: currentPage + 1,
            });

            fetch(panisperna_ajax.ajax_url, {
                method: 'POST',
                body: body,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
            .then(function (res) { return res.json(); })
            .then(function (response) {
                if (response.success) {
                    consiglioGrid.insertAdjacentHTML('beforeend', response.data.html);
                    consiglioGrid.dataset.currentPage = String(currentPage + 1);

                    if (currentPage + 1 >= response.data.max_pages) {
                        var wrap = consiglioLoadMore.closest('.load-more-wrap');
                        if (wrap) wrap.style.display = 'none';
                    }
                }
            })
            .finally(function () {
                consiglioLoadMore.textContent = 'Carica altri contenuti';
                consiglioLoadMore.disabled = false;
            });
        });
    }

    // Consiglio video placeholder — lazy load iframe on click
    var videoPlaceholders = document.querySelectorAll('.consiglio-video__placeholder');
    videoPlaceholders.forEach(function (el) {
        el.addEventListener('click', function () {
            var embed = el.getAttribute('data-embed');
            if (embed) {
                el.parentNode.innerHTML = embed;
            }
        });
    });

    function fetchCollezione(category, page, append) {
        if (loadMoreBtn) {
            loadMoreBtn.textContent = '...';
            loadMoreBtn.disabled = true;
        }

        var body = new URLSearchParams({
            action: 'panisperna_collezione',
            nonce: panisperna_ajax.nonce,
            category: category,
            page: page,
        });

        fetch(panisperna_ajax.ajax_url, {
            method: 'POST',
            body: body,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        })
        .then(function (res) { return res.json(); })
        .then(function (response) {
            if (response.success) {
                if (append) {
                    grid.insertAdjacentHTML('beforeend', response.data.html);
                } else {
                    grid.innerHTML = response.data.html;
                }

                grid.dataset.currentPage = String(page);
                grid.dataset.maxPages = String(response.data.max_pages);

                // Re-bind ajax-add-to-cart on new cards
                grid.querySelectorAll('.ajax-add-to-cart').forEach(function (btn) {
                    if (!btn.dataset.bound) {
                        btn.dataset.bound = '1';
                        btn.addEventListener('click', handleAjaxAddToCart);
                    }
                });

                // Show/hide load more
                if (loadMoreBtn) {
                    var wrap = loadMoreBtn.closest('.load-more-wrap');
                    if (page >= response.data.max_pages) {
                        if (wrap) wrap.style.display = 'none';
                    } else {
                        if (wrap) wrap.style.display = '';
                    }
                }
            }
        })
        .finally(function () {
            if (loadMoreBtn) {
                loadMoreBtn.textContent = 'Carica altri contenuti';
                loadMoreBtn.disabled = false;
            }
        });
    }

    // Eventi (Incontri) — Filter chips + Load more (PH8-INCONTRI)
    var eventiChips = document.querySelectorAll('.btn--chip-evento');
    var eventiGrid = document.querySelector('.cards-row--eventi');
    var eventiLoadMore = document.getElementById('evento-load-more');

    if (eventiChips.length && eventiGrid) {
        eventiChips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                eventiChips.forEach(function (c) { c.classList.remove('is-active'); });
                this.classList.add('is-active');

                var category = this.dataset.category;
                eventiGrid.dataset.category = category;
                eventiGrid.dataset.currentPage = '1';

                fetchEventi(category, 1, false);
            });
        });
    }

    if (eventiLoadMore && eventiGrid) {
        eventiLoadMore.addEventListener('click', function () {
            var currentPage = parseInt(eventiGrid.dataset.currentPage) || 1;
            var category = eventiGrid.dataset.category || 'tutti';
            fetchEventi(category, currentPage + 1, true);
        });
    }

    function fetchEventi(category, page, append) {
        if (eventiLoadMore) {
            eventiLoadMore.textContent = '...';
            eventiLoadMore.disabled = true;
        }

        var body = new URLSearchParams({
            action: 'panisperna_eventi',
            nonce: panisperna_ajax.nonce,
            category: category,
            page: page,
        });

        fetch(panisperna_ajax.ajax_url, {
            method: 'POST',
            body: body,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        })
        .then(function (res) { return res.json(); })
        .then(function (response) {
            if (response.success) {
                if (append) {
                    eventiGrid.insertAdjacentHTML('beforeend', response.data.html);
                } else {
                    eventiGrid.innerHTML = response.data.html;
                }

                eventiGrid.dataset.currentPage = String(page);
                eventiGrid.dataset.maxPages = String(response.data.max_pages);

                if (eventiLoadMore) {
                    var wrap = eventiLoadMore.closest('.load-more-wrap');
                    if (page >= response.data.max_pages) {
                        if (wrap) wrap.style.display = 'none';
                    } else {
                        if (wrap) wrap.style.display = '';
                    }
                }
            }
        })
        .finally(function () {
            if (eventiLoadMore) {
                eventiLoadMore.textContent = 'Carica altri contenuti';
                eventiLoadMore.disabled = false;
            }
        });
    }

    // Sidebar Cart — open/close (PH10-SIDEBAR)
    var cartToggle = document.getElementById('cart-toggle');
    var sidebarCart = document.getElementById('sidebar-cart');
    var sidebarClose = sidebarCart ? sidebarCart.querySelector('.sidebar-cart__close') : null;
    var sidebarOverlay = sidebarCart ? sidebarCart.querySelector('.sidebar-cart__overlay') : null;

    function openSidebarCart() {
        if (sidebarCart) {
            sidebarCart.classList.add('is-open');
            sidebarCart.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebarCart() {
        if (sidebarCart) {
            sidebarCart.classList.remove('is-open');
            sidebarCart.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }

    if (cartToggle) {
        cartToggle.addEventListener('click', function (e) {
            e.preventDefault();
            openSidebarCart();
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebarCart);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebarCart);
    }

    // Close sidebar on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebarCart && sidebarCart.classList.contains('is-open')) {
            closeSidebarCart();
        }
    });

    // Sidebar Cart — remove single item (delegated for fragment-refreshed HTML)
    if (sidebarCart) {
        sidebarCart.addEventListener('click', function (e) {
            var removeBtn = e.target.closest('.sidebar-cart__item-remove');
            if (!removeBtn) return;
            var key = removeBtn.getAttribute('data-key');
            var item = removeBtn.closest('.sidebar-cart__item');
            if (item) item.style.opacity = '0.4';

            var fd = new FormData();
            fd.append('action', 'panisperna_cart_remove');
            fd.append('nonce', panisperna_ajax.nonce);
            fd.append('cart_key', key);

            fetch(panisperna_ajax.ajax_url, { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function () {
                    jQuery(document.body).trigger('wc_fragment_refresh');
                });
        });
    }

    // Sidebar Cart — clear all (delegated for fragment-refreshed HTML)
    if (sidebarCart) {
        sidebarCart.addEventListener('click', function (e) {
            var btn = e.target.closest('.sidebar-cart__clear');
            if (!btn) return;

            var fd = new FormData();
            fd.append('action', 'panisperna_cart_clear');
            fd.append('nonce', panisperna_ajax.nonce);

            fetch(panisperna_ajax.ajax_url, { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function () {
                    jQuery(document.body).trigger('wc_fragment_refresh');
                });
        });
    }

    // Bridge: WC Blocks store → cart fragments refresh
    // Subscribe to wp.data store (same approach as Elementor Pro / Astra)
    function watchWcBlocksStore() {
        if (!window.wp || !window.wp.data) return;
        var select = window.wp.data.select;
        if (!select || !select('wc/store/cart')) return;

        var lastCount = null;
        window.wp.data.subscribe(function () {
            try {
                var store = select('wc/store/cart');
                var cartData = store.getCartData ? store.getCartData() : null;
                var count = cartData ? cartData.itemsCount : null;
                if (count === null || count === undefined) return;
                if (lastCount !== null && count !== lastCount) {
                    jQuery(document.body).trigger('wc_fragment_refresh');
                }
                lastCount = count;
            } catch (e) {}
        });
    }

    // wp.data may not be ready at DOMContentLoaded, poll briefly
    var watchAttempts = 0;
    var watchInterval = setInterval(function () {
        watchAttempts++;
        if ((window.wp && window.wp.data && window.wp.data.select && window.wp.data.select('wc/store/cart')) || watchAttempts > 20) {
            clearInterval(watchInterval);
            watchWcBlocksStore();
        }
    }, 500);
});
