<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<p class="price">
<?php echo $product->get_price_html(); ?>
<?php
switch ($product->get_shipping_class()) {
    case "clean-burn":
        echo "<br>Plus Delivery & Set Up: £135 inc. VAT";
        break;
    case "heat-grill":
        echo "<br>Plus Delivery & Set Up: £140 inc. VAT";
        break;
    case "garden-gourmet":
        echo "<br>Plus Delivery & Set Up: £150 inc. VAT";
        break;
    case "garden-party":
        echo "<br>Plus Delivery & Set Up: £200 inc. VAT";
        break;
    case "terrace-gourmet":
        echo "<br>Plus Delivery & Set Up: £110 inc. VAT";
        break;
    default:
        echo "";
}
?>
</p>
