<?php
add_action("wp_enqueue_scripts", "my_theme_enqueue_styles");
function my_theme_enqueue_styles()
{
    $parent_style = "radcliffe-style";
    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . "/style.css"
    );
    wp_enqueue_style(
        "child-style",
        get_stylesheet_directory_uri() . "/style.css",
        [$parent_style],
        wp_get_theme()->get("Version")
    );
}

/**
 * woo_custom_order_formatted_billing_address
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
function woo_custom_order_formatted_billing_address($fields)
{
    print_r($fields);
    $address = [
        "company" => $fields->billing_company,
        "first_name" => $fields->billing_first_name,
        "last_name" => $fields->billing_last_name,
        "address_1" => $fields->billing_address_1,
        "address_2" => $fields->billing_address_2,
        "postcode" => $fields->billing_postcode,
        "city" => $fields->billing_city,
        "state" => $fields->billing_state,
        "country" => $fields->billing_country,
    ];

    return $address;
}

add_filter(
    "woocommerce_order_formatted_billing_address",
    "woo_custom_order_formatted_billing_address"
);

//
// add_filter(
//     "woocommerce_order_formatted_shipping_address",
//     "woo_custom_order_formatted_shipping_address"
// );
//
// /**
//  * woo_custom_order_formatted_shipping_address
//  *
//  * @access      public
//  * @since       1.0
//  * @return      void
//  */
// function woo_custom_order_formatted_shipping_address()
// {
//     $address = [
//         "company" => $this->shipping_company,
//         "first_name" => $this->shipping_first_name,
//         "last_name" => $this->shipping_last_name,
//         "address_1" => $this->shipping_address_1,
//         "address_2" => $this->shipping_address_2,
//         "postcode" => $this->shipping_postcode,
//         "city" => $this->shipping_city,
//         "state" => $this->shipping_state,
//         "country" => $this->shipping_country,
//     ];
//
//     return $address;
// }

/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */

if (!function_exists("radcliffe_load_style")) {
    function radcliffe_load_style()
    {
        if (!is_admin()) {
            $dependencies = [];

            /**
             * Translators: If there are characters in your language that are not
             * supported by the theme fonts, translate this to 'off'. Do not translate
             * into your own language.
             */
            $google_fonts = _x("on", "Google Fonts: on or off", "radcliffe");

            if ("off" !== $google_fonts) {
                wp_enqueue_style(
                    "radcliffe_googlefonts",
                    "//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700,700italic,800|Crimson+Text:400,400italic,700,700italic|Love+Ya+Like+A+Sister"
                );
                $dependencies[] = "radcliffe_googlefonts";
            }

            wp_enqueue_style(
                "radcliffe_style",
                get_template_directory_uri() . "/style.css",
                $dependencies
            );
        }
    }
    add_action("wp_print_styles", "radcliffe_load_style");
}

?>
