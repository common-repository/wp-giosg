jQuery(document).ready(($) => {
    $.ajax({
        method: 'POST',
        url: wp_giosg.ajax_url,
        data: wp_giosg.data,
        dataType: 'json',
        success: (data) => {
            if (typeof _giosg !== 'undefined') {
                _giosg(() => {
                    if (data.coupons.length) {
                        _giosg('shoppingCart', 'setTotalPrice', data.subtotal - data.discount_total)
                    }
                    giosg.api.shoppingCart.submit(data.items).then(() => {})
                })
            }
        }
    })
    $('body').on('added_to_cart', () => {
        $.ajax({
            method: 'POST',
            url: wp_giosg.ajax_url,
            data: wp_giosg.data,
            dataType: 'json',
            success: (data) => {
                if (typeof _giosg !== 'undefined') {
                    _giosg(() => {
                        if (data.coupons.length) {
                            _giosg('shoppingCart', 'setTotalPrice', data.subtotal - data.discount_total)
                        }
                        giosg.api.shoppingCart.submit(data.items).then(() => {})
                    })
                }
            }
        })
    })
    $('body').on('removed_from_cart', () => {
        $.ajax({
            method: 'POST',
            url: wp_giosg.ajax_url,
            data: wp_giosg.data,
            dataType: 'json',
            success: (data) => {
                if (typeof _giosg !== 'undefined') {
                    _giosg(() => {
                        if (data.coupons.length) {
                            _giosg('shoppingCart', 'setTotalPrice', data.subtotal - data.discount_total)
                        }
                        giosg.api.shoppingCart.submit(data.items).then(() => {})
                    })
                }
            }
        })
    })
    $('body').on('updated_cart_totals', () => {
        $.ajax({
            method: 'POST',
            url: wp_giosg.ajax_url,
            data: wp_giosg.data,
            dataType: 'json',
            success: (data) => {
                if (typeof _giosg !== 'undefined') {
                    _giosg(() => {
                        if (data.coupons.length) {
                            _giosg('shoppingCart', 'setTotalPrice', data.subtotal - data.discount_total)
                        }
                        giosg.api.shoppingCart.submit(data.items).then(() => {})
                    })
                }
            }
        })
    })
    $('form.checkout').on('checkout_place_order', () => {
        $.ajax({
            method: 'POST',
            url: wp_giosg.ajax_url,
            data: wp_giosg.data,
            dataType: 'json',
            success: () => {
                if (typeof _giosg !== 'undefined') {
                    _giosg(() => {
                        giosg.api.shoppingCart.freeze().then(() => {})
                    })
                }
            }
        })
    })
})
