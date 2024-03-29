<?php
/**
 * Print order content. Copy this file to your themes
 * directory /woocommerce/print-order to customize it.
 *
 * @package WooCommerce Print Invoice & Delivery Note/Templates
 */

if (!defined("ABSPATH")) {
    exit();
} ?>

<?php $fields = apply_filters(
    "wcdn_order_info_fields",
    wcdn_get_order_info($order),
    $order
); ?>

	<div class="order-branding">
		<div class="company-logo">
			<?php if (wcdn_get_company_logo_id()): ?>
				<?php wcdn_company_logo();endif; ?>
		</div>

		<div class="company-info">
			<?php if (!wcdn_get_company_logo_id()): ?>
				<h1 class="company-name"><?php wcdn_company_name(); ?></h1><?php endif; ?>
			<div class="company-address"><?php wcdn_company_info(); ?></div>
		</div>

		<div class="order-date">
			 <?php echo apply_filters(
        "wcdn_order_info_content",
        $fields["order_date"]["value"],
        $fields["order_date"]
    ); ?>&nbsp;
		</div>

		<?php do_action("wcdn_after_branding", $order); ?>
	</div><!-- .order-branding -->

	<div class="order-addresses<?php if (
     !$order->get_formatted_shipping_address()
 ): ?> no-shipping-address<?php endif; ?>">
		<div class="billing-address">
			<h3><?php esc_attr_e("Billing Address", "woocommerce-delivery-notes"); ?></h3>
			<address>

				<?php if (!$order->get_formatted_billing_address()) {
        esc_attr_e("N/A", "woocommerce-delivery-notes");
    } else {
        echo wp_kses_post(
            apply_filters(
                "wcdn_address_billing",
                $order->get_formatted_billing_address(),
                $order
            )
        );
    } ?>
			</address>
			 <p class="billing-additional">
					<?php
     $telNr = apply_filters(
         "wcdn_order_info_content",
         $fields["billing_phone"]["value"],
         $fields["billing_phone"]
     );
     if ($telNr) {
         echo "Tel. " . $telNr;
     }
     ?><br />
					<?php echo apply_filters(
         "wcdn_order_info_content",
         $fields["billing_email"]["value"],
         $fields["billing_email"]
     ); ?>
			 </p>
		</div>

		<div class="shipping-address">
			<h3><?php esc_attr_e("Shipping Address", "woocommerce-delivery-notes"); ?></h3>
			<address>

				<?php if (!$order->get_formatted_shipping_address()) {
        esc_attr_e("N/A", "woocommerce-delivery-notes");
    } else {
        echo wp_kses_post(
            apply_filters(
                "wcdn_address_shipping",
                $order->get_formatted_shipping_address(),
                $order
            )
        );
    } ?>

			</address>
		</div>

		<?php do_action("wcdn_after_addresses", $order); ?>
	</div><!-- .order-addresses -->

	<div class="order-info">
		<h1><?php wcdn_document_title(); ?></h1>
		<p class="subtitle">Nr.: <?php echo date("Y") .
      "-" .
      apply_filters(
          "wcdn_order_info_content",
          $fields["order_number"]["value"],
          $fields["order_number"]
      ); ?></p>

		<?php do_action("wcdn_after_info", $order); ?>
	</div><!-- .order-info -->

	<div class="order-items">
		<table>
			<thead>
				<tr>
					<th class="head-name"><span><?php esc_attr_e(
         "Product",
         "woocommerce-delivery-notes"
     ); ?></span></th>
					<th class="head-item-price"><span><?php esc_attr_e(
         "Price",
         "woocommerce-delivery-notes"
     ); ?></span></th>
					<th class="head-quantity"><span><?php esc_attr_e(
         "Quantity",
         "woocommerce-delivery-notes"
     ); ?></span></th>
					<th class="head-price"><span><?php esc_attr_e(
         "Total",
         "woocommerce-delivery-notes"
     ); ?></span></th>
				</tr>
			</thead>

			<tbody>
				<?php if (count($order->get_items()) > 0): ?>
					<?php foreach ($order->get_items() as $item_id => $item): ?>

						<?php
      $product = apply_filters(
          "wcdn_order_item_product",
          $item->get_product(),
          $item
      );
      if (!$product) {
          continue;
      }
      if (version_compare(get_option("woocommerce_version"), "3.0.0", ">=")) {
          $item_meta = new WC_Order_Item_Product($item["item_meta"], $product);
      } else {
          $item_meta = new WC_Order_Item_Meta($item["item_meta"], $product);
      }
      ?>
						<tr>
							<td class="product-name">
								<?php do_action("wcdn_order_item_before", $product, $order, $item); ?>
								<span class="name">
								<?php
        $addon_name = $item->get_meta("_wc_pao_addon_name", true);
        $addon_value = $item->get_meta("_wc_pao_addon_value", true);
        $is_addon = !empty($addon_value);

        if ($is_addon) {
            // Displaying options of product addon.
            $addon_html =
                '<div class="wc-pao-order-item-name">' .
                esc_html($addon_name) .
                '</div><div class="wc-pao-order-item-value">' .
                esc_html($addon_value) .
                "</div></div>";

            echo wp_kses_post($addon_html);
        } else {

            $product_id = $item["product_id"];
            $prod_name = get_post($product_id);
            $product_name = $prod_name->post_title;

            echo wp_kses_post(
                apply_filters("wcdn_order_item_name", $product_name, $item)
            );
            ?>
									</span>

									<?php if (version_compare(get_option("woocommerce_version"), "3.0.0", ">=")) {
             if (isset($item["variation_id"]) && 0 !== $item["variation_id"]) {
                 $variation = wc_get_product($item["product_id"]);
                 foreach ($item["item_meta"] as $key => $value) {
                     if (!(0 === strpos($key, "_"))) {
                         if (is_array($value)) {
                             continue;
                         }
                         $term_wp = get_term_by("slug", $value, $key);
                         $attribute_name = wc_attribute_label($key, $variation);
                         if (isset($term_wp->name)) {
                             echo "<br>" .
                                 wp_kses_post(
                                     $attribute_name . ":" . $term_wp->name
                                 );
                         } else {
                             echo "<br>" .
                                 wp_kses_post($attribute_name . ":" . $value);
                         }
                     }
                 }
             } else {
                 foreach ($item["item_meta"] as $key => $value) {
                     if (!(0 === strpos($key, "_"))) {
                         if (is_array($value)) {
                             continue;
                         }
                         echo "<br>" . wp_kses_post($key . ":" . $value);
                     }
                 }
             }
         } else {
             $item_meta_new = new WC_Order_Item_Meta(
                 $item["item_meta"],
                 $product
             );
             $item_meta_new->display();
         } ?>
									<br>
									<dl class="extras">
										<?php if (
              $product &&
              $product->exists() &&
              $product->is_downloadable() &&
              $order->is_download_permitted()
          ): ?>

					<dt><?php esc_attr_e("Download:", "woocommerce-delivery-notes"); ?></dt>
						<dd>
						<?php
              // translators: files count.
              // translators: files count.
              ?>printf(
					esc_attr__("%s Files", "woocommerce-delivery-notes"),
					count($item->get_item_downloads())
			); ?>
						</dd>

					<?php endif; ?>

					<?php
     wcdn_print_extra_fields($item);
     $fields = apply_filters(
         "wcdn_order_item_fields",
         [],
         $product,
         $order,
         $item
     );

     foreach ($fields as $field): ?>

											<dt><?php echo esc_html($field["label"]); ?></dt>
											<dd><?php echo esc_html($field["value"]); ?></dd>

										<?php endforeach;
     ?>
									</dl>
								<?php
        }
        ?>
								<?php do_action("wcdn_order_item_after", $product, $order, $item); ?>
							</td>
							<td class="product-item-price">
								<span><?php echo wp_kses_post(
            wcdn_get_formatted_item_price($order, $item)
        ); ?></span>
							</td>
							<td class="product-quantity">
								<span><?php echo esc_attr(
            apply_filters("wcdn_order_item_quantity", $item["qty"], $item)
        ); ?></span>
							</td>
							<td class="product-price">
								<span><?php echo wp_kses_post(
            $order->get_formatted_line_subtotal($item)
        ); ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>

			<tfoot>
				<?php
    $totals_arr = $order->get_order_item_totals();
    if ($totals_arr):
        foreach ($totals_arr as $total): ?>
						<tr>
							<td class="total-name"><span><?php echo wp_kses_post(
           $total["label"]
       ); ?></span></td>
							<td class="total-item-price"></td>
							<?php if ("Total" === $total["label"]) { ?>
							<td class="total-quantity"><?php echo wp_kses_post(
           $order->get_item_count()
       ); ?></td>
							<?php } else { ?>
							<td class="total-quantity"></td>
							<?php } ?>
							<td class="total-price"><span><?php echo wp_kses_post(
           $total["value"]
       ); ?></span></td>
						</tr>
					<?php endforeach; ?>
				<?php
    endif;
    ?>
			</tfoot>
		</table>

		<?php do_action("wcdn_after_items", $order); ?>
	</div><!-- .order-items -->

	<div class="order-notes">
		<?php if (wcdn_has_customer_notes($order)): ?>
			<h4><?php esc_attr_e("Customer Note", "woocommerce-delivery-notes"); ?></h4>
			<?php wcdn_customer_notes($order); ?>
		<?php endif; ?>

		<?php do_action("wcdn_after_notes", $order); ?>
	</div><!-- .order-notes -->

	<div class="order-thanks">
		<?php wcdn_personal_notes(); ?>

		<?php do_action("wcdn_after_thanks", $order); ?>
	</div><!-- .order-thanks -->

	<div class="order-colophon">
		<div class="colophon-policies">
			<?php wcdn_policies_conditions(); ?>
		</div>

		<div class="colophon-imprint">
			<?php wcdn_imprint(); ?>
		</div>

		<?php do_action("wcdn_after_colophon", $order); ?>
	</div><!-- .order-colophon -->
