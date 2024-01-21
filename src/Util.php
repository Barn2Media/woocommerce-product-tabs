<?php
namespace Barn2\Plugin\WC_Product_Tabs_Free;

/**
 * Utility functions for WooCommerce Product Tabs.
 *
 * @package   Barn2\woocommerce-product-tabs
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
final class Util {

  public static function is_tab_global( $tab_id ) {
    // In the older versions of the plugin, the _wpt_display_tab_globally meta doesn't exist 
    if( ! metadata_exists( 'post', $tab_id, '_wpt_display_tab_globally' ) ) {
      if( get_post_meta( $tab_id, '_wpt_conditions_category', true ) ) {
        return 'no';
      } else {
        return 'yes';
      }
    } else {
      return get_post_meta( $tab_id, '_wpt_display_tab_globally', true );
    }
  }

  public static function get_all_categories( $conditions_categories ) {

    if( ! $conditions_categories || ! is_array( $conditions_categories ) ) {
      return array();
    }

    if( is_array( $conditions_categories ) && empty( $conditions_categories ) ) {
      return array();
    }

    $child_categories = [];
    foreach ( $conditions_categories as $category ) {
      $child_terms = get_terms( array(
        'child_of'   => $category,
        'hide_empty' => true,
        'taxonomy'   => 'product_cat',
        'fields'     => 'ids'
      ) );

      if ( is_array( $child_terms ) ) {
        $child_categories = array_unique( array_merge( $child_categories, $child_terms ) );
      }
    }
    return array_unique( array_merge( $conditions_categories, $child_categories ) );
  }
  
}