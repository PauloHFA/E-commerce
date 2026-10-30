/**
 * JavaScript Custom - Moda Feminina E-commerce
 * 
 * SPA interactions: mini-cart drawer, AJAX add to cart, filtering,
 * product gallery, sticky add-to-cart, infinite scroll, animations.
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
        console.log('%c🎀 Moda Feminina Custom JS carregado com sucesso!', 'color:#c48cb3; font-weight:bold');
        
        // Initialize all components
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
    
    // Mini Cart Drawer
    function initMiniCartDrawer($) {
        const $drawer = $('.mf-mini-cart-drawer');
        const $overlay = $('.mf-cart-overlay');
        const $toggle = $('.mf-mini-cart-toggle');
        const $close = $('.mf-cart-close');
        
        // Toggle drawer
        $toggle.on('click', function(e) {
            e.preventDefault();
            toggleCartDrawer(true, $);
        });
        
        $close.add($overlay).on('click', function() {
            toggleCartDrawer(false, $);
        });
        
        // Close on escape
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $drawer.hasClass('is-open')) {
            toggleCartDrawer(false, $);
        }
    });
    
    function toggleCartDrawer(open, $) {
        const isOpening = typeof open === 'undefined' ? !$drawer.hasClass('is-open') : open;
        
        if (isOpening) {
            $drawer.addClass('is-open');
            $overlay.show();
            $('body').addClass('mf-cart-open');
            $toggle.attr('aria-expanded', 'true');
        } else {
            $drawer.removeClass('is-open');
            $overlay.hide();
            $('body').removeClass('mf-cart-open');
            $toggle.attr('aria-expanded', 'false');
        }
    }
    
    // Update cart content
    function updateMiniCart($) {
        // This would fetch cart fragments via AJAX
        // For now, simulate with localStorage
        const cart = JSON.parse(localStorage.getItem('moda_feminina_cart') || '{}');
        const $itemsContainer = $('.mf-cart-items');
        const $totalElement = $('.mf-cart-total');
        
        if (cart.items && cart.items.length > 0) {
            $itemsContainer.empty();
            cart.items.forEach(item => {
                $itemsContainer.append(`
                    <div class="mf-cart-item" data-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}" class="mf-cart-item-image">
                        <div class="mf-cart-item-info">
                            <h4 class="mf-cart-item-name">${item.name}</h4>
                            <p class="mf-cart-item-details">Tamanho: ${item.size} | Cor: ${item.color}</p>
                            <div class="mf-cart-item-price">${item.price}</div>
                        </div>
                        <div class="mf-cart-item-quantity">
                            <button class="mf-qty-btn mf-qty-btn--minus" data-id="${item.id}" aria-label="Diminuir quantidade">−</button>
                            <input type="number" value="${item.quantity}" min="1" class="mf-qty-input" data-id="${item.id}">
                            <button class="mf-qty-btn mf-qty-btn--plus" data-id="${item.id}" aria-label="Aumentar quantidade">+</button>
                        </div>
                        <button class="mf-cart-item-remove" data-id="${item.id}" aria-label="Remover item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                `);
            });
            
            $totalElement.text(cart.total || '0.00');
            $itemsContainer.removeAttr('hidden');
            $('.mf-cart-empty').hide();
        } else {
            $itemsContainer.empty().attr('hidden', true);
            $('.mf-cart-empty').show();
        }
    }
}

// AJAX Add to Cart
function initAjaxAddToCart($) {
    $(document).on('click', '.mf-add-to-cart, .mf-sticky-atc-btn, .mf-quick-add', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        const productType = $button.data('product-type');
        const variationId = $button.data('variation-id');
        const quantity = parseInt($button.closest('.mf-product-actions').find('.mf-qty-input').val()) || 1;
        
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
            product_id: variationId || productId,
            quantity: quantity,
            _wpnonce: wc_add_to_cart_params.add_to_cart_nonce
        };
        
        // For variable products, include variation_id
        if (variationId) {
            ajaxData.variation_id = variationId;
        }
        
        // AJAX request
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    // Update fragments
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    
                    // Update local cart
                    updateLocalCart(productId, quantity, response.data);
                    
                    // Show toast
                    showToast('Produto adicionado ao carrinho!', 'success');
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
    
    // Update local cart storage
    function updateLocalCart(productId, quantity, productData) {
        let cart = JSON.parse(localStorage.getItem('moda_feminina_cart') || '{}');
        
        if (!cart.items) cart.items = [];
        
        const existingItem = cart.items.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.items.push({
                id: productId,
                name: productData.name || 'Produto',
                price: productData.price || '0.00',
                image: productData.image || '',
                size: productData.size || 'M',
                color: productData.color || 'Padrão',
                quantity: quantity
            });
        }
        
        cart.total = cart.items.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
        localStorage.setItem('moda_feminina_cart', JSON.stringify(cart));
    }
}

// Filter Sidebar
function initFilterSidebar($) {
    const $sidebar = $('#mf-filter-sidebar');
    const $toggle = $('.mf-filter-toggle');
    const $close = $('.mf-filter-close');
    const $overlay = $('.mf-filter-overlay');
    const $applyBtn = $('[data-filter-apply]');
    
    // Toggle sidebar
    $toggle.add($close).add($overlay).on('click', function() {
        $sidebar.toggleClass('is-open');
        $overlay.toggle();
        $toggle.attr('aria-expanded', $sidebar.hasClass('is-open'));
    });
    
    // Apply filters
    $applyBtn.on('click', function() {
        const filters = {};
        
        // Collect checkbox values
        $sidebar.find('input[type="checkbox"]:checked').each(function() {
            const name = $(this).attr('name');
            const value = $(this).val();
            if (!filters[name]) filters[name] = [];
            filters[name].push(value);
        });
        
        // Collect price range
        const minPrice = $('#min-price').val();
        const maxPrice = $('#max-price').val();
        if (minPrice) filters.min_price = minPrice;
        if (maxPrice) filters.max_price = maxPrice;
        
        // Update URL and reload
        updateFiltersURL(filters);
    });
    
    // Price slider
    if ($('.mf-price-slider').length) {
        initPriceSlider($);
    }
    
    function initPriceSlider($) {
        const $slider = $('.mf-price-slider');
        const $minInput = $('#min-price');
        const $maxInput = $('#max-price');
        
        $slider.slider({
            range: true,
            min: 0,
            max: 1000,
            values: [$minInput.val() || 0, $maxInput.val() || 1000],
            slide: function(event, ui) {
                $minInput.val(ui.values[0]);
                $maxInput.val(ui.values[1]);
            }
        });
        
        $minInput.add($maxInput).on('change', function() {
            const min = parseInt($minInput.val()) || 0;
            const max = parseInt($maxInput.val()) || 1000;
            $slider.slider('values', [min, max]);
        });
    }
    
    function updateFiltersURL(filters) {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Clear existing filter params
        urlParams.delete('filter_tamanho');
        urlParams.delete('filter_cor');
        urlParams.delete('min_price');
        urlParams.delete('max_price');
        urlParams.delete('product_cat');
        
        // Add new filters
        Object.keys(filters).forEach(key => {
            if (Array.isArray(filters[key])) {
                filters[key].forEach(value => {
                    urlParams.append(key, value);
                });
            } else {
                urlParams.set(key, filters[key]);
            }
        });
        
        // Navigate to new URL
        window.location.search = urlParams.toString();
    }
}

// Color Swatches & Size Selector - Connect to WooCommerce Variations Form
function initColorSwatches($) {
    $(document).on('change', '.mf-swatch-input', function() {
        const $swatch = $(this).closest('.mf-swatch-label');
        const value = $(this).val();
        const attributeName = $(this).attr('name'); // e.g., attribute_pa_cor
        
        // Update active state
        $swatch.siblings().removeClass('is-selected');
        $swatch.addClass('is-selected');
        
        // Update product image if available
        const newImage = $swatch.data('image');
        if (newImage) {
            $('.mf-gallery-main img').attr('src', newImage);
        }
        
        // Update price if needed
        const price = $swatch.data('price');
        if (price) {
            $('.mf-product-price').text(price);
        }
        
        // Trigger WooCommerce variations form
        triggerVariationChange(attributeName, value, $);
    });
    
    // Size selector radio buttons
    $(document).on('change', '.mf-size-selector input[type="radio"]', function() {
        const $radio = $(this);
        const value = $radio.val();
        const attributeName = $radio.attr('name'); // e.g., attribute_pa_tamanho
        
        // Update active state
        $radio.closest('.mf-size-selector').find('label').removeClass('is-selected');
        $radio.closest('label').addClass('is-selected');
        
        // Trigger WooCommerce variations form
        triggerVariationChange(attributeName, value, $);
    });
    
    function triggerVariationChange(attributeName, value, $) {
        // Find the WooCommerce variations form
        const $form = $('.variations_form');
        if ($form.length) {
            // Find the hidden select in the variations table
            const $select = $form.find('select[name="' + attributeName + '"]');
            if ($select.length) {
                // Set the value and trigger change
                $select.val(value).trigger('change');
                
                // Also trigger on the form for good measure
                $form.trigger('woocommerce_variation_select_change');
                $form.trigger('check_variations', ['', false]);
            }
        }
    }
    
    // Listen for WooCommerce variation found event to update UI
    $(document).on('found_variation', '.variations_form', function(event, variation) {
        // Update price display
        if (variation.price_html) {
            $('.mf-product-price').html(variation.price_html);
        }
        
        // Update availability
        if (variation.availability_html) {
            $('.mf-product-availability').html(variation.availability_html);
        }
        
        // Update SKU
        if (variation.sku) {
            $('.mf-product-sku').text(variation.sku);
        }
        
        // Update weight/dimensions if displayed
        if (variation.weight_html) {
            $('.mf-product-weight').html(variation.weight_html);
        }
        
        // Update variation ID for add to cart
        $('.mf-add-to-cart, .mf-sticky-atc-btn').attr('data-variation-id', variation.variation_id);
        
        // Update gallery image if variation has one
        if (variation.image && variation.image.src) {
            $('.mf-gallery-main img').attr('src', variation.image.src);
        }
        
        // Enable/disable add to cart button
        const $atcButtons = $('.mf-add-to-cart, .mf-sticky-atc-btn');
        if (variation.is_purchasable && variation.is_in_stock) {
            $atcButtons.removeAttr('disabled').removeClass('disabled');
        } else {
            $atcButtons.attr('disabled', 'disabled').addClass('disabled');
        }
    });
    
    // Listen for reset variation event
    $(document).on('reset_data', '.variations_form', function() {
        // Reset price to original
        const $form = $(this);
        const $price = $form.find('.mf-product-price');
        if ($price.data('original-price')) {
            $price.html($price.data('original-price'));
        }
        
        // Reset availability
        const $availability = $('.mf-product-availability');
        if ($availability.data('original-availability')) {
            $availability.html($availability.data('original-availability'));
        }
        
        // Reset SKU
        const $sku = $('.mf-product-sku');
        if ($sku.data('original-sku')) {
            $sku.text($sku.data('original-sku'));
        }
        
        // Reset variation ID
        $('.mf-add-to-cart, .mf-sticky-atc-btn').removeAttr('data-variation-id');
        
        // Reset gallery to first image
        const $firstThumb = $('.mf-gallery-thumbs .mf-thumb').first();
        if ($firstThumb.length) {
            $('.mf-gallery-main img').attr('src', $firstThumb.attr('src'));
        }
        
        // Enable add to cart buttons
        $('.mf-add-to-cart, .mf-sticky-atc-btn').removeAttr('disabled').removeClass('disabled');
    });
}

// Product Gallery
function initProductGallery() {
    const $mainImage = $('.mf-gallery-main img');
    const $thumbs = $('.mf-gallery-thumbs .mf-thumb');
    
    // Thumbnail click
    $thumbs.on('click', function(e) {
        e.preventDefault();
        const newSrc = $(this).attr('src');
        $mainImage.attr('src', newSrc);
        
        // Update active thumb
        $thumbs.removeClass('is-active');
        $(this).addClass('is-active');
    });
    
    // Zoom functionality
    $mainImage.on('click', function() {
        // Open lightbox
        openLightbox($(this).attr('src'));
    });
    
    // Keyboard navigation
    $(document).on('keydown', function(e) {
        if ($('.mf-lightbox').is(':visible')) {
            if (e.key === 'ArrowLeft') {
                navigateLightbox(-1);
            } else if (e.key === 'ArrowRight') {
                navigateLightbox(1);
            }
        }
    });
}

// Sticky Add to Cart
function initStickyAddToCart() {
    const $sticky = $('.mf-sticky-atc');
    const $productInfo = $('.mf-product-info');
    
    if ($sticky.length && $productInfo.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    $sticky.hide();
                } else {
                    $sticky.show();
                }
            });
        }, {
            threshold: 0,
            rootMargin: '0px 0px -50% 0px'
        });
        
        observer.observe($productInfo[0]);
        
        // Sticky button click
        $sticky.on('click', '.mf-sticky-atc-btn', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            $('.mf-add-to-cart[data-product-id="' + productId + '"]').trigger('click');
        });
    }
}

// Infinite Scroll
function initInfiniteScroll() {
    let isLoading = false;
    const $loadMore = $('.mf-load-more');
    const $productsGrid = $('.mf-products-grid');
    
    $(window).on('scroll', function() {
        if (isLoading) return;
        
        const scrollTop = $(window).scrollTop();
        const windowHeight = $(window).height();
        const documentHeight = $(document).height();
        const scrollPercentage = (scrollTop + windowHeight) / documentHeight;
        
        if (scrollPercentage > 0.9 && $loadMore.length && !$loadMore.is(':hidden')) {
            loadMoreProducts();
        }
    });
    
    $loadMore.on('click', function() {
        loadMoreProducts();
    });
    
    function loadMoreProducts() {
        if (isLoading) return;
        
        isLoading = true;
        $loadMore.addClass('is-loading');
        $loadMore.find('.mf-btn-text').hide();
        $loadMore.find('.mf-btn-loader').show();
        
        const currentPage = getQueryParam('paged') || 1;
        const maxPage = getQueryParam('max_page') || 2;
        
        if (currentPage >= maxPage) {
            $loadMore.hide();
            isLoading = false;
            $loadMore.removeClass('is-loading');
            $loadMore.find('.mf-btn-text').show();
            $loadMore.find('.mf-btn-loader').hide();
            return;
        }
        
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'moda_feminina_load_more_products',
                paged: currentPage + 1,
                _wpnonce: wc_add_to_cart_params.load_more_nonce
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    $productsGrid.append(response.data.html);
                    history.replaceState({}, '', response.data.permalink);
                }
            },
            error: function() {
                showToast('Erro ao carregar mais produtos', 'error');
            },
            complete: function() {
                isLoading = false;
                $loadMore.removeClass('is-loading');
                $loadMore.find('.mf-btn-text').show();
                $loadMore.find('.mf-btn-loader').hide();
            }
        });
    }
    
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
}

// Smooth Scroll
function initSmoothScroll() {
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800, 'easeInOutCubic');
        }
    });
}

// Intersection Observer Animations
function initIntersectionObserver() {
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const $element = $(entry.target);
                    $element.addClass('is-visible');
                    
                    // Stagger animations
                    if ($element.hasClass('mf-product-card')) {
                        const index = $element.index();
                        $element.css('animation-delay', (index * 0.1) + 's');
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        $('.mf-animate-on-scroll').each(function() {
            observer.observe(this);
        });
    }
}

// Local Storage Features
function initLocalStorageFeatures() {
    // Recently viewed products
    $(document).on('added_to_cart', function(event, fragments, total, $button) {
        const productId = $button.data('product-id');
        addToRecentlyViewed(productId);
    });
    
    // Wishlist functionality
    $(document).on('click', '.mf-wishlist-btn', function() {
        const $button = $(this);
        const productId = $button.data('product-id');
        
        toggleWishlist(productId, $button);
    });
    
    // Load wishlist from localStorage
    const wishlist = JSON.parse(localStorage.getItem('moda_feminina_wishlist') || '[]');
    wishlist.forEach(productId => {
        $(`.mf-wishlist-btn[data-product-id="${productId}"]`).addClass('is-in-wishlist');
    });
}

function addToRecentlyViewed(productId) {
    let recentlyViewed = JSON.parse(localStorage.getItem('moda_feminina_recently_viewed') || '[]');
    
    // Remove if already exists
    recentlyViewed = recentlyViewed.filter(id => id !== productId);
    
    // Add to beginning
    recentlyViewed.unshift(productId);
    
    // Keep only last 10
    recentlyViewed = recentlyViewed.slice(0, 10);
    
    localStorage.setItem('moda_feminina_recently_viewed', JSON.stringify(recentlyViewed));
    updateRecentlyViewedCarousel();
}

function toggleWishlist(productId, $button) {
    let wishlist = JSON.parse(localStorage.getItem('moda_feminina_wishlist') || '[]');
    
    const index = wishlist.indexOf(productId);
    if (index > -1) {
        wishlist.splice(index, 1);
        $button.removeClass('is-in-wishlist');
        showToast('Removido da lista de desejos', 'info');
    } else {
        wishlist.push(productId);
        $button.addClass('is-in-wishlist');
        showToast('Adicionado à lista de desejos', 'success');
    }
    
    localStorage.setItem('moda_feminina_wishlist', JSON.stringify(wishlist));
}

function updateRecentlyViewedCarousel() {
    const recentlyViewed = JSON.parse(localStorage.getItem('moda_feminina_recently_viewed') || '[]');
    const $track = $('[data-recently-viewed-track]');
    
    if (recentlyViewed.length === 0) {
        $('.mf-recently-viewed').attr('hidden', true);
        return;
    }
    
    $track.empty();
    
    recentlyViewed.forEach(productId => {
        const $productCard = $(`.product[data-id="${productId}"]`).clone();
        $productCard.addClass('mf-carousel-item');
        $track.append($productCard);
    });
    
    $('.mf-recently-viewed').attr('hidden', false);
}

// Toast Notifications
function initToastNotifications() {
    // Create toast container if not exists
    if (!$('.mf-toast-container').length) {
        $('body').append('<div class="mf-toast-container" aria-live="polite" aria-atomic="true"></div>');
    }
}

function showToast(message, type = 'info') {
    const $container = $('.mf-toast-container');
    const $toast = $('<div class="mf-toast mf-toast--' + type + '">');
    $toast.text(message);
    $container.append($toast);
    
    // Show toast
    setTimeout(() => {
        $toast.addClass('is-visible');
    }, 10);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        $toast.removeClass('is-visible');
        setTimeout(() => {
            $toast.remove();
        }, 300);
    }, 3000);
}

// Loading Skeletons
function initLoadingSkeletons() {
    // Add skeleton loading to product cards
    if ($('.mf-products-grid').length) {
        $('.mf-products-grid').addClass('mf-loading');
        
        setTimeout(() => {
            $('.mf-products-grid').removeClass('mf-loading');
            $('.mf-skeleton').remove();
        }, 1500);
    }
}

// Lightbox functionality
function openLightbox(imageSrc) {
    if (!$('.mf-lightbox').length) {
        $('body').append('<div class="mf-lightbox"><button class="mf-lightbox-close" aria-label="Fechar">×</button><img src="" alt=""><button class="mf-lightbox-nav mf-lightbox-prev">‹</button><button class="mf-lightbox-nav mf-lightbox-next">›</button></div>');
    }
    
    const $lightbox = $('.mf-lightbox');
    const $img = $lightbox.find('img');
    
    $img.attr('src', imageSrc);
    $lightbox.show();
    $lightbox.find('.mf-lightbox-close').focus();
    
    // Close lightbox
    $lightbox.on('click', '.mf-lightbox-close, .mf-lightbox', function(e) {
        if ($(e.target).hasClass('mf-lightbox') || $(e.target).hasClass('mf-lightbox-close')) {
            $lightbox.hide();
            $img.attr('src', '');
        }
    });
}

function navigateLightbox(direction) {
    // This would navigate through product images
    // Implementation depends on current product gallery state
    console.log('Navigate lightbox:', direction);
}

// Update wishlist button state
function updateWishlistButton($button) {
    const productId = $button.data('product-id');
    let wishlist = JSON.parse(localStorage.getItem('moda_feminina_wishlist') || '[]');
    
    if (wishlist.includes(productId)) {
        $button.addClass('is-in-wishlist');
    } else {
        $button.removeClass('is-in-wishlist');
    }
}

// Custom animation easing
$.easing['easeInOutCubic'] = function(x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
    return c / 2 * ((t -= 2) * t * t + 2) + b;
};
