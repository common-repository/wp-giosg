<?php
/**
 * @var string $id
 * @var int $version
 */
?>
<!-- giosg tag -->
<script>
    (function(w,t,f){var s='script',o='_giosg',h='https://service.giosg.com',e,n;e=t.createElement(s);e.async=1;e.src=h+'/live<?php echo $version; ?>/'+<?php echo $version === 2 ? 'f' : '""'; ?>;w[o]=w[o]||function(){(w[o]._e=w[o]._e||[]).push(arguments);};w[o]._c=f;w[o]._h=h;n=t.getElementsByTagName(s)[0];n.parentNode.insertBefore(e,n);})(window,document,'<?php echo $id; ?>');
</script>
<!-- giosg tag -->
