<?php
$lang = function_exists('pll_get_current_language') ? pll_get_current_language() : '';
$action_url = home_url( $lang ? '/' . $lang . '/' : '/' );
?>

<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url($action_url); ?>">
  <input type="search" name="s" placeholder="<?php pll_e("Suche nach Produkt oder Artikelnummer…"); ?>" value="<?php echo get_search_query(); ?>" />
  <input type="hidden" name="post_type" value="product" />
  <button type="submit">🔍</button>
</form>