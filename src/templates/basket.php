<?php
/**
 * @var \WPGiosg\Plugin $this
 */
?>
<!-- giosg tag -->
<script>
    _giosg(function () {
        const data = <?php echo json_encode($this->getCartItems()); ?>;
        if (data.coupons.length) {
            _giosg('shoppingCart', 'setTotalPrice', data.subtotal - data.discount_total);
        }
        giosg.api.shoppingCart.submit(data.items).then(function () {
            console.log('sending items from basket');
        });
    });
</script>
<!-- giosg tag -->