/**
 * JavaScript Custom - Moda Feminina E-commerce Premium
 * 
 * Premium interactions: announcement bar, header scroll, search, mobile menu,
 * mini-cart drawer, AJAX add to cart, filtering, product gallery, 
 * sticky add-to-cart, infinite scroll, scroll animations, toast notifications.
 */

(function($) {
    'use strict';
    
    // Wait for jQuery to be ready
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available, retrying...');
        setTimeout(function() {
            if (typeof jQuery !== 'undefined') {
                initModaFeminina(jQuery);
            }
        }, 100);
        return;
    }
    
    initModaFeminina($);
    
    function initModaFeminina($) {
        console.log('%c🎀 Moda Feminina Premium JS carregado com sucesso!', 'color:#1a1a1a; font-weight:bold');
        
        // Initialize all components
        initAnnouncementBar($);
        initHeaderScroll($);
        initSearch($);
        initMobileMenu($);
        initMiniCartDrawer($);
        initAjaxAddToCart($);
        initFilterSidebar($);
        initColorSwatches($);
        initProductGallery($);
        initStickyAddToCart($);
        initInfiniteScroll($);
        initSmoothScroll($);
        initIntersectionObserver($);
        initLocalStorageFeatures($);
        initToastNotifications($);
        initLoadingSkeletons($);
        initHeroScroll($);
        initQuantityButtons($);
                initQuickViewModal($);
        
                // Global state
        window.ModaFeminina = {
            cart: { items: [], total: 0 },
            wishlist: [],
            recentlyViewed: [],
            filters: {},
            isLoading: false
        };
        
        // Event listeners
        $(document).on('wc_fragments_refreshed', function() {
            updateMiniCart($);
        });
        
        $(document).on('added_to_cart', function(event, fragments, total, $button) {
            showToast('Produto adicionado ao carrinho!', 'success');
            updateMiniCart($);
            updateWishlistButton($button);
        });
    }
    
    // Announcement Bar
    function initAnnouncementBar($) {
        const $bar = $('#mf-announcement-bar');
        const $dismiss = $bar.find('.mf-announcement-bar__dismiss');
        
        // Check if dismissed in session storage
        if (sessionStorage.getItem('mf_announcement_dismissed')) {
            $bar.addClass('is-hidden');
            adjustHeaderPosition();
        }
        
        $dismiss.on('click', function() {
            $bar.addClass('is-hidden');
            sessionStorage.setItem('mf_announcement_dismissed', 'true');
            adjustHeaderPosition();
        });
    }
    
    function adjustHeaderPosition() {
        const $header = $('#mf-header');
        const $nav = $('#mf-nav');
        const announcementHeight = $('#mf-announcement-bar').hasClass('is-hidden') ? 0 : 40;
        
        $header.css('top', announcementHeight + 'px');
        $nav.css('top', (announcementHeight + 88) + 'px');
    }
    
    // Header Scroll Effect
    function initHeaderScroll($) {
        const $header = $('#mf-header');
        const $nav = $('#mf-nav');
        let lastScroll = 0;
        let ticking = false;
        
        function onScroll() {
            const scrollY = window.scrollY;
            const announcementHeight = $('#mf-announcement-bar').hasClass('is-hidden') ? 0 : 40;
            
            if (scrollY > announcementHeight + 50) {
                $header.addClass('is-scrolled');
            } else {
                $header.removeClass('is-scrolled');
            }
            
            lastScroll = scrollY;
            ticking = false;
        }
        
        $(window).on('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(onScroll);
                ticking = true;
            }
        });
    }
    
    // Search Enhancement
    function initSearch($) {
        const $search = $('.mf-search');
        const $input = $search.find('.mf-search__input');
        const $form = $search.find('.mf-search__form');
        
        // Focus handling
        $input.on('focus', function() {
            $search.addClass('is-focused');
        });
        
        $input.on('blur', function() {
            $search.removeClass('is-focused');
        });
        
        // Live search suggestions (placeholder for future enhancement)
        let searchTimeout;
        $input.on('input', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val().trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(function() {
                    // Future: implement AJAX search suggestions
                    console.log('Search query:', query);
                }, 300);
            }
        });
    }
    
    // Mobile Menu
    function initMobileMenu($) {
        const $toggle = $('#mf-mobile-menu-toggle');
        const $nav = $('#mf-nav');
        const $navList = $nav.find('.mf-nav__list');
        
        $toggle.on('click', function() {
            const isOpen = $nav.hasClass('is-open');
            
            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
        
        // Close on link click
        $navList.on('click', '.mf-nav__link', function() {
            closeMobileMenu();
        });
        
        // Close on escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $nav.hasClass('is-open')) {
                closeMobileMenu();
            }
        });
        
        // Close on overlay click
        $nav.on('click', function(e) {
            if (e.target === this) {
                closeMobileMenu();
            }
        });
        
        function openMobileMenu() {
            $nav.addClass('is-open');
            $toggle.attr('aria-expanded', 'true');
            $('body').addClass('mf-nav-open');
            $toggle.find('svg').attr('d', 'M18 6L6 18M6 6l12 12');
        }
        
        function closeMobileMenu() {
            $nav.removeClass('is-open');
            $toggle.attr('aria-expanded', 'false');
            $('body').removeClass('mf-nav-open');
            $toggle.find('svg').attr('d', 'M3 6h18M3 12h18M3 18h18');
        }
    }
    
    // Mini Cart Drawer
    function initMiniCartDrawer($) {
        const $drawer = $('#mf-mini-cart-drawer');
        const $overlay = $('#mf-cart-overlay');
        const $toggle = $('#mf-mini-cart-toggle');
        const $close = $drawer.find('[data-cart-close]');
        
        // Toggle drawer
        $toggle.on('click', function(e) {
            e.preventDefault();
            toggleCartDrawer(true);
        });
        
        $close.add($overlay).on('click', function() {
            toggleCartDrawer(false);
        });
        
        // Close on escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $drawer.hasClass('is-open')) {
                toggleCartDrawer(false);
            }
        });
        
        function toggleCartDrawer(open) {
            const isOpening = typeof open === 'undefined' ? !$drawer.hasClass('is-open') : open;
            
            if (isOpening) {
                $drawer.addClass('is-open').attr('aria-hidden', 'false');
                $overlay.addClass('is-visible');
                $toggle.attr('aria-expanded', 'true');
                $('body').addClass('mf-cart-open');
                // Focus trap
                $drawer.find('button, a').first().focus();
            } else {
                $drawer.removeClass('is-open').attr('aria-hidden', 'true');
                $overlay.removeClass('is-visible');
                $toggle.attr('aria-expanded', 'false');
                $('body').removeClass('mf-cart-open');
            }
        }
    }
    
    // Update cart content
    function updateMiniCart($) {
        $.ajax({
            url: mf_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_get_refreshed_fragments',
                _wpnonce: mf_ajax_params.add_to_cart_nonce
            },
            success: function(response) {
                if (response && response.fragments) {
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    
                    if (response.cart_hash) {
                        // Cart hash updated
                    }
                }
            },
            error: function() {
                console.warn('Could not refresh cart fragments');
            }
        });
    }
    
    // AJAX Add to Cart
    function initAjaxAddToCart($) {
        $(document).on('click', '.mf-add-to-cart, .mf-sticky-atc-btn, .mf-quick-add, .mf-product-card__quick-btn--quick-add, .mf-product-card__btn--primary', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            const productType = $button.data('product-type');
            const variationId = $button.data('variation-id');
            
            // Get quantity from various possible locations
            let quantity = 1;
            const $qtyInput = $button.closest('.mf-product-actions, .mf-sticky-atc, .mf-product-card').find('.mf-qty-input, .qty');
            if ($qtyInput.length) {
                quantity = parseInt($qtyInput.val()) || 1;
            }
            
            // For variable products, check if variation is selected
            if (productType === 'variable' && !variationId) {
                showToast('Por favor, selecione as opções do produto', 'warning');
                return;
            }
            
            // Show loading state
            $button.addClass('is-loading');
            $button.find('.mf-btn-text').hide();
            $button.find('.mf-btn-loader').show();
            
            // Prepare AJAX data
            const ajaxData = {
                action: 'moda_feminina_add_to_cart',
                product_id: productId,
                quantity: quantity,
                _wpnonce: mf_ajax_params.add_to_cart_nonce
            };
            
            // For variable products, include variation_id and attributes
            if (variationId) {
                ajaxData.variation_id = variationId;
                
                const $form = $button.closest('.variations_form');
                if ($form.length) {
                    const variation = {};
                    $form.find('select[name^="attribute_"]').each(function() {
                        const name = $(this).attr('name');
                        const val = $(this).val();
                        if (val) {
                            variation[name] = val;
                        }
                    });
                    $form.find('input[name^="attribute_"]:checked').each(function() {
                        const name = $(this).attr('name');
                        const val = $(this).val();
                        if (val) {
                            variation[name] = val;
                        }
                    });
                    if (Object.keys(variation).length > 0) {
                        ajaxData.variation = variation;
                    }
                }
            }
            
            // AJAX request
            $.ajax({
                url: mf_ajax_params.ajax_url,
                type: 'POST',
                data: ajaxData,
                success: function(response) {
                    if (response.success) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                        
                        if (response.cart_count !== undefined) {
                            $('.mf-header-action__count, .mf-cart-count').text(response.cart_count);
                        }
                        
                        showToast('Produto adicionado ao carrinho!', 'success');
                        
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                    } else {
                        showToast(response.data.message || 'Erro ao adicionar ao carrinho', 'error');
                    }
                },
                error: function() {
                    showToast('Erro ao adicionar ao carrinho. Tente novamente.', 'error');
                },
                complete: function() {
                    $button.removeClass('is-loading');
                    $button.find('.mf-btn-text').show();
                    $button.find('.mf-btn-loader').hide();
                }
            });
        });
    }
    
    // Quantity Buttons
    function initQuantityButtons($) {
        // Product page quantity
        $(document).on('click', '.mf-qty-btn--plus, .mf-qty-btn[data-action="plus"]', function() {
            const $input = $(this).siblings('.mf-qty-input, .qty');
            const currentVal = parseInt($input.val()) || 1;
            const max = parseInt($input.attr('max')) || 99;
            if (currentVal < max) {
                $input.val(currentVal + 1).trigger('change');
            }
        });
        
        $(document).on('click', '.mf-qty-btn--minus, .mf-qty-btn[data-action="minus"]', function() {
            const $input = $(this).siblings('.mf-qty-input, .qty');
            const currentVal = parseInt($input.val()) || 1;
            const min = parseInt($input.attr('min')) || 1;
            if (currentVal > min) {
                $input.val(currentVal - 1).trigger('change');
            }
        });
        
        // Mini-cart quantity
        $(document).on('click', '.mf-mini-cart-item .mf-qty-btn[data-action="plus"]', function() {
            const $input = $(this).siblings('.mf-qty-input');
            const cartItemKey = $(this).data('cart-item-key');
            const currentVal = parseInt($input.val()) || 1;
            const max = parseInt($input.attr('max')) || 99;
            if (currentVal < max) {
                $input.val(currentVal + 1).trigger('change');
                updateCartItemQuantity(cartItemKey, currentVal + 1, $);
            }
        });
        
        $(document).on('click', '.mf-mini-cart-item .mf-qty-btn[data-action="minus"]', function() {
            const $input = $(this).siblings('.mf-qty-input');
            const cartItemKey = $(this).data('cart-item-key');
            const currentVal = parseInt($input.val()) || 1;
            const min = parseInt($input.attr('min')) || 1;
            if (currentVal > min) {
                $input.val(currentVal - 1).trigger('change');
                updateCartItemQuantity(cartItemKey, currentVal - 1, $);
            }
        });
        
        $(document).on('change', '.mf-mini-cart-item .mf-qty-input', function() {
            const cartItemKey = $(this).data('cart-item-key');
            const quantity = parseInt($(this).val()) || 1;
            updateCartItemQuantity(cartItemKey, quantity, $);
        });
        
        // Remove item from mini-cart
        $(document).on('click', '.mf-mini-cart-item__remove, .mf-cart-item-remove', function() {
            const cartItemKey = $(this).data('cart-item-key');
            removeCartItem(cartItemKey, $);
        });
    }
    
    function updateCartItemQuantity(cartItemKey, quantity, $) {
        $.ajax({
            url: mf_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_update_cart_item',
                cart_item_key: cartItemKey,
                quantity: quantity,
                _wpnonce: mf_ajax_params.add_to_cart_nonce
            },
            success: function(response) {
                if (response.success) {
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    if (response.cart_count !== undefined) {
                        $('.mf-header-action__count, .mf-cart-count').text(response.cart_count);
                    }
                }
            }
        });
    }
    
    function removeCartItem(cartItemKey, $) {
        $.ajax({
            url: mf_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_remove_cart_item',
                cart_item_key: cartItemKey,
                _wpnonce: mf_ajax_params.add_to_cart_nonce
            },
            success: function(response) {
                if (response.success) {
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    if (response.cart_count !== undefined) {
                        $('.mf-header-action__count, .mf-cart-count').text(response.cart_count);
                    }
                    showToast('Produto removido do carrinho', 'info');
                }
            }
        });
    }
    
    // Filter Sidebar
    function initFilterSidebar($) {
        const $sidebar = $('.mf-filter-sidebar');
        const $toggle = $('.mf-filter-toggle');
        const $close = $sidebar.find('.mf-filter-sidebar__close');
        const $overlay = $('.mf-filter-overlay');
        
        $toggle.on('click', function() {
            $sidebar.addClass('is-open');
            $overlay.addClass('is-visible');
            $('body').addClass('mf-filter-open');
        });
        
        $close.add($overlay).on('click', function() {
            $sidebar.removeClass('is-open');
            $overlay.removeClass('is-visible');
            $('body').removeClass('mf-filter-open');
        });
        
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $sidebar.hasClass('is-open')) {
                $sidebar.removeClass('is-open');
                $overlay.removeClass('is-visible');
                $('body').removeClass('mf-filter-open');
            }
        });
        
        // Filter attribute buttons
        $(document).on('click', '.mf-filter-attr', function() {
            $(this).toggleClass('is-active');
            // Future: trigger AJAX filter
        });
        
        // Color swatches in filter
        $(document).on('click', '.mf-filter-color', function() {
            $(this).toggleClass('is-active').siblings().removeClass('is-active');
        });
    }
    
    // Color Swatches (Product Page)
    function initColorSwatches($) {
        $(document).on('click', '.mf-swatch-label', function() {
            const $label = $(this);
            const $input = $label.find('.mf-swatch-input');
            const $fieldset = $label.closest('.mf-attribute-fieldset');
            
            $fieldset.find('.mf-swatch-label').removeClass('is-selected');
            $label.addClass('is-selected');
            $input.prop('checked', true).trigger('change');
        });
        
        // Size selector
        $(document).on('click', '.mf-size-label', function() {
            if ($(this).hasClass('mf-size--unavailable')) return;
            
            const $label = $(this);
            const $input = $label.find('.mf-size-input');
            const $group = $label.closest('.mf-size-group');
            
            $group.find('.mf-size-label').removeClass('is-selected');
            $label.addClass('is-selected');
            $input.prop('checked', true).trigger('change');
        });
    }
    
    // Product Gallery
    function initProductGallery($) {
        const $main = $('.mf-gallery-main img');
        const $thumbs = $('.mf-thumb');
        
        $thumbs.on('click', function() {
            const $thumb = $(this);
            const newSrc = $thumb.data('large-image') || $thumb.attr('src').replace('-80x80', '');
            
            $thumbs.removeClass('is-active');
            $thumb.addClass('is-active');
            
            $main.fadeOut(200, function() {
                $(this).attr('src', newSrc).fadeIn(200);
            });
        });
        
        // Keyboard navigation
        $thumbs.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });
    }
    
    // Sticky Add to Cart
    function initStickyAddToCart($) {
        const $sticky = $('.mf-sticky-atc');
        const $productInfo = $('.mf-product-info');
        
        if (!$sticky.length || !$productInfo.length) return;
        
        const productInfoBottom = $productInfo.offset().top + $productInfo.outerHeight();
        
        $(window).on('scroll', function() {
            if (window.scrollY > productInfoBottom - 100) {
                $sticky.addClass('is-visible');
            } else {
                $sticky.removeClass('is-visible');
            }
        });
    }
    
    // Infinite Scroll
    function initInfiniteScroll($) {
        const $grid = $('.mf-products-grid[data-infinite-scroll]');
        let page = 2;
        let loading = false;
        let maxPages = parseInt($grid.data('max-pages')) || 99;
        
        if (!$grid.length) return;
        
        $(window).on('scroll', function() {
            if (loading || page > maxPages) return;
            
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const gridOffset = $grid.offset().top + $grid.outerHeight();
            
            if (scrollTop + windowHeight > gridOffset - 300) {
                loading = true;
                loadMoreProducts($, $grid, page);
                page++;
            }
        });
    }
    
    function loadMoreProducts($, $grid, page) {
        const $loader = $('<div class="mf-skeleton mf-skeleton--button"></div>').appendTo($grid);
        
        $.ajax({
            url: mf_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'moda_feminina_load_more_products',
                page: page,
                _wpnonce: mf_ajax_params.add_to_cart_nonce
            },
            success: function(response) {
                $loader.remove();
                
                if (response.success && response.html) {
                    $grid.append(response.html);
                    // Re-initialize animations for new items
                    initIntersectionObserver($);
                }
                
                loading = false;
            },
            error: function() {
                $loader.remove();
                loading = false;
            }
        });
    }
    
    // Smooth Scroll
    function initSmoothScroll($) {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this).attr('href');
            if (target === '#') return;
            
            const $target = $(target);
            if ($target.length) {
                e.preventDefault();
                const headerHeight = $('#mf-header').outerHeight() + $('#mf-nav').outerHeight();
                const targetPosition = $target.offset().top - headerHeight - 20;
                
                $('html, body').animate({
                    scrollTop: targetPosition
                }, 600, 'swing');
            }
        });
    }
    
    // Intersection Observer for Scroll Animations
    function initIntersectionObserver($) {
        if (!('IntersectionObserver' in window)) {
            // Fallback: show all immediately
            $('.mf-animate-on-scroll').addClass('is-visible');
            return;
        }
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '0px 0px -50px 0px',
            threshold: 0.1
        });
        
        $('.mf-animate-on-scroll').each(function() {
            observer.observe(this);
        });
    }
    
    // LocalStorage Features
    function initLocalStorageFeatures($) {
        // Recently viewed products
        const viewed = JSON.parse(localStorage.getItem('mf_recently_viewed') || '[]');
        window.ModaFeminina.recentlyViewed = viewed;
        
        // Wishlist
        const wishlist = JSON.parse(localStorage.getItem('mf_wishlist') || '[]');
        window.ModaFeminina.wishlist = wishlist;
        updateWishlistUI();
    }
    
    function updateWishlistUI() {
        const wishlist = window.ModaFeminina.wishlist || [];
        $('.mf-wishlist-btn, .mf-product-card__quick-btn--wishlist, .mf-header-action--wishlist').each(function() {
            const $btn = $(this);
            const productId = $btn.data('product-id');
            if (productId && wishlist.includes(String(productId))) {
                $btn.addClass('is-active is-in-wishlist');
                $btn.find('svg').attr('fill', 'currentColor');
            }
        });
    }
    
    function updateWishlistButton($button) {
        const productId = $button.data('product-id');
        if (!productId) return;
        
        let wishlist = JSON.parse(localStorage.getItem('mf_wishlist') || '[]');
        const isInWishlist = wishlist.includes(String(productId));
        
        if (isInWishlist) {
            $button.addClass('is-active is-in-wishlist');
            $button.find('svg').attr('fill', 'currentColor');
        } else {
            $button.removeClass('is-active is-in-wishlist');
            $button.find('svg').attr('fill', 'none');
        }
    }
    
    // Wishlist toggle
    $(document).on('click', '.mf-wishlist-btn, .mf-product-card__quick-btn--wishlist, .mf-header-action--wishlist', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const productId = $btn.data('product-id');
        if (!productId) return;
        
        let wishlist = JSON.parse(localStorage.getItem('mf_wishlist') || '[]');
        const index = wishlist.indexOf(String(productId));
        
        if (index > -1) {
            wishlist.splice(index, 1);
            $btn.removeClass('is-active is-in-wishlist');
            $btn.find('svg').attr('fill', 'none');
            showToast('Removido da lista de desejos', 'info');
        } else {
            wishlist.push(String(productId));
            $btn.addClass('is-active is-in-wishlist');
            $btn.find('svg').attr('fill', 'currentColor');
            showToast('Adicionado à lista de desejos', 'success');
        }
        
        localStorage.setItem('mf_wishlist', JSON.stringify(wishlist));
        window.ModaFeminina.wishlist = wishlist;
    });
    
    // Toast Notifications
    function initToastNotifications($) {
        window.showToast = function(message, type = 'info', title = '') {
            const $container = $('.mf-toast-container');
            if (!$container.length) {
                $('body').append('<div class="mf-toast-container"></div>');
            }
            
            const icons = {
                success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
                error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
                warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
            };
            
            const titles = {
                success: 'Sucesso',
                error: 'Erro',
                warning: 'Atenção',
                info: 'Informação'
            };
            
            const toastTitle = title || titles[type] || '';
            
            const $toast = $(`
                <div class="mf-toast mf-toast--${type}">
                    <div class="mf-toast__icon">${icons[type]}</div>
                    <div class="mf-toast__content">
                        ${toastTitle ? `<div class="mf-toast__title">${toastTitle}</div>` : ''}
                        <div class="mf-toast__message">${message}</div>
                    </div>
                </div>
            `);
            
            $container.append($toast);
            
            // Trigger animation
            requestAnimationFrame(function() {
                $toast.addClass('is-visible');
            });
            
            // Auto remove
            setTimeout(function() {
                $toast.removeClass('is-visible');
                setTimeout(function() {
                    $toast.remove();
                }, 300);
            }, 5000);
        };
    }
    
    // Loading Skeletons
    function initLoadingSkeletons($) {
        // Show skeletons while content loads
        $('.mf-products-grid:empty').html(`
            <div class="mf-skeleton mf-skeleton--circle" style="width: 100%; aspect-ratio: 3/4; border-radius: var(--radius-xl);"></div>
            <div class="mf-skeleton mf-skeleton--title"></div>
            <div class="mf-skeleton mf-skeleton--text" style="width: 80%;"></div>
            <div class="mf-skeleton mf-skeleton--button"></div>
        `.repeat(4));
    }
    
    // Hero Scroll Button
    function initHeroScroll($) {
        $('.mf-hero__scroll').on('click', function() {
            const $nextSection = $('.mf-hero').next('section');
            if ($nextSection.length) {
                const headerHeight = $('#mf-header').outerHeight() + $('#mf-nav').outerHeight();
                const targetPosition = $nextSection.offset().top - headerHeight - 20;
                
                $('html, body').animate({
                    scrollTop: targetPosition
                }, 800, 'swing');
            }
        });
    }
    
        // Quick View Modal
        function initQuickViewModal($) {
            const $modal = $('#mf-quick-view-modal');
            const $content = $('#mf-quick-view-content');
            let currentProductId = null;
        
            // Open quick view
            $(document).on('click', '.mf-product-card__quick-btn--peek', function(e) {
                e.preventDefault();
                e.stopPropagation();
            
                const productId = $(this).data('product-id');
                if (!productId) return;
            
                currentProductId = productId;
                openQuickView(productId);
            });
        
            // Close quick view
            $(document).on('click', '[data-quick-view-close]', function() {
                closeQuickView();
            });
        
            // Close on Escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $modal.hasClass('is-open')) {
                    closeQuickView();
                }
            });
        
            // Attribute selection
            $(document).on('click', '.mf-quick-view-info__attribute-option', function() {
                const $option = $(this);
                const $attribute = $option.closest('.mf-quick-view-info__attribute');
            
                $attribute.find('.mf-quick-view-info__attribute-option').removeClass('is-selected');
                $option.addClass('is-selected');
            
                // Update variation if all attributes selected
                updateQuickViewVariation();
            });
        
            // Quantity buttons
            $(document).on('click', '.mf-quick-view-info__quantity-btn', function() {
                const $btn = $(this);
                const $input = $btn.siblings('.mf-quick-view-info__quantity-value');
                const isPlus = $btn.hasClass('mf-qty-btn--plus');
                let value = parseInt($input.val()) || 1;
                const max = parseInt($input.attr('max')) || 99;
                const min = parseInt($input.attr('min')) || 1;
            
                if (isPlus && value < max) {
                    value++;
                } else if (!isPlus && value > min) {
                    value--;
                }
            
                $input.val(value);
            });
        
            // Add to cart from quick view
            $(document).on('click', '.mf-quick-view-info__btn--add-to-cart', function() {
                const $btn = $(this);
                const productId = $btn.data('product-id');
                const variationId = $btn.data('variation-id') || 0;
                const quantity = parseInt($('.mf-quick-view-info__quantity-value').val()) || 1;
            
                // Collect selected attributes
                const variation = {};
                $('.mf-quick-view-info__attribute-option.is-selected').each(function() {
                    const attrName = $(this).data('attribute-name');
                    const attrValue = $(this).data('attribute-value');
                    if (attrName && attrValue) {
                        variation['attribute_' + attrName] = attrValue;
                    }
                });
            
                addToCartFromQuickView(productId, variationId, quantity, variation);
            });
        
            function openQuickView(productId) {
                $content.html(`
                    <div class="mf-quick-view-loader">
                        <div class="mf-spinner"></div>
                        <span>Carregando...</span>
                    </div>
                `);
            
                $modal.addClass('is-open');
                $('body').css('overflow', 'hidden');
            
                // Fetch product data via AJAX
                $.ajax({
                    url: mf_ajax_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'moda_feminina_quick_view',
                        product_id: productId,
                        _wpnonce: mf_ajax_params.add_to_cart_nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.html) {
                            $content.html(response.data.html);
                            initQuickViewGallery($);
                        } else {
                            $content.html('<p class="mf-quick-view-error">Erro ao carregar produto.</p>');
                        }
                    },
                    error: function() {
                        $content.html('<p class="mf-quick-view-error">Erro de conexão. Tente novamente.</p>');
                    }
                });
            }
        
            function closeQuickView() {
                $modal.removeClass('is-open');
                $('body').css('overflow', '');
                currentProductId = null;
            
                // Clear content after animation
                setTimeout(function() {
                    $content.html(`
                        <div class="mf-quick-view-loader">
                            <div class="mf-spinner"></div>
                            <span>Carregando...</span>
                        </div>
                    `);
                }, 300);
            }
        
            function initQuickViewGallery($) {
                $('.mf-quick-view-gallery__thumb').on('click', function() {
                    const $thumb = $(this);
                    const src = $thumb.find('img').attr('src');
                
                    $thumb.siblings().removeClass('is-active');
                    $thumb.addClass('is-active');
                
                    $('.mf-quick-view-gallery__main img').attr('src', src);
                });
            }
        
            function updateQuickViewVariation() {
                // Check if all required attributes are selected
                const $attributes = $('.mf-quick-view-info__attribute');
                let allSelected = true;
                const variation = {};
            
                $attributes.each(function() {
                    const $attr = $(this);
                    const $selected = $attr.find('.mf-quick-view-info__attribute-option.is-selected');
                
                    if ($selected.length === 0) {
                        allSelected = false;
                    } else {
                        const attrName = $selected.data('attribute-name');
                        const attrValue = $selected.data('attribute-value');
                        if (attrName && attrValue) {
                            variation['attribute_' + attrName] = attrValue;
                        }
                    }
                });
            
                if (allSelected && currentProductId) {
                    // Find matching variation
                    findMatchingVariation(currentProductId, variation);
                }
            }
        
            function findMatchingVariation(productId, variation) {
                $.ajax({
                    url: mf_ajax_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'moda_feminina_find_variation',
                        product_id: productId,
                        variation: variation,
                        _wpnonce: mf_ajax_params.add_to_cart_nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.variation_id) {
                            $('.mf-quick-view-info__btn--add-to-cart').data('variation-id', response.data.variation_id);
                        
                            // Update price if provided
                            if (response.data.price_html) {
                                $('.mf-quick-view-info__price').html(response.data.price_html);
                            }
                        
                            // Update image if provided
                            if (response.data.image) {
                                $('.mf-quick-view-gallery__main img').attr('src', response.data.image);
                            }
                        }
                    }
                });
            }
        
            function addToCartFromQuickView(productId, variationId, quantity, variation) {
                const $btn = $('.mf-quick-view-info__btn--add-to-cart');
                const originalText = $btn.html();
            
                $btn.prop('disabled', true).html('<span class="mf-spinner" style="width:20px;height:20px;border-width:2px;"></span> Adicionando...');
            
                $.ajax({
                    url: mf_ajax_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'moda_feminina_add_to_cart',
                        product_id: productId,
                        variation_id: variationId,
                        quantity: quantity,
                        variation: variation,
                        _wpnonce: mf_ajax_params.add_to_cart_nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            window.showToast('Produto adicionado ao carrinho!', 'success');
                            closeQuickView();
                        
                            // Trigger cart fragment refresh
                            $(document.body).trigger('wc_fragments_refreshed');
                        } else {
                            window.showToast(response.data.message || 'Erro ao adicionar ao carrinho', 'error');
                        }
                    },
                    error: function() {
                        window.showToast('Erro de conexão. Tente novamente.', 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            }
        }
    
        // Update cart count on page load
    $(document).ready(function() {
        // Trigger initial cart fragment refresh
        $(document.body).trigger('wc_fragments_refreshed');
    });
    
})(jQuery);
