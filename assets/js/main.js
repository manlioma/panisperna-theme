/**
 * Panisperna Libreria - Main JS
 */

document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-bar__menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function () {
            navMenu.classList.toggle('is-open');
            this.classList.toggle('is-active');
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

    // AJAX Add to Cart
    document.querySelectorAll('.ajax-add-to-cart').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var productId = this.dataset.productId;
            if (!productId) return;

            var button = this;
            button.textContent = '...';
            button.style.pointerEvents = 'none';

            var formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            fetch('/?wc-ajax=add_to_cart', {
                method: 'POST',
                body: new URLSearchParams({
                    product_id: productId,
                    quantity: 1,
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
                    button.textContent = '+';
                    button.style.pointerEvents = '';
                }, 1500);
            })
            .catch(function () {
                button.textContent = '+';
                button.style.pointerEvents = '';
            });
        });
    });
});
