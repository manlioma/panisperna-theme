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

    // AJAX Add to Cart
    document.querySelectorAll('.ajax-add-to-cart').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
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
                    // Update cart count
                    var cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = parseInt(cartCount.textContent || '0') + 1;
                    } else {
                        var cartIcon = document.querySelector('.cart-icon');
                        if (cartIcon) {
                            var span = document.createElement('span');
                            span.className = 'cart-count';
                            span.textContent = '1';
                            cartIcon.appendChild(span);
                        }
                    }
                }
                setTimeout(function () {
                    button.textContent = originalText;
                    button.style.pointerEvents = '';
                }, 1500);
            })
            .catch(function () {
                button.textContent = originalText;
                button.style.pointerEvents = '';
            });
        });
    });
});
