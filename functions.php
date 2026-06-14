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

add_filter(
    "woocommerce_order_formatted_billing_address",
    "woo_custom_order_formatted_billing_address"
);

function woo_custom_order_formatted_billing_address($fields)
{
    $address = [
        "company" => $fields["company"],
        "first_name" => $fields["first_name"],
        "last_name" => $fields["last_name"],
        "address_1" => $fields["address_1"],
        "address_2" => $fields["address_2"],
        "postcode" => "",
        "city" => $fields["postcode"] . " " . $fields["city"],
        "state" => $fields["state"],
        "country" => $fields["country"],
    ];
    return $address;
}

add_filter(
    "woocommerce_order_formatted_shipping_address",
    "woo_custom_order_formatted_shipping_address"
);

function woo_custom_order_formatted_shipping_address($fields)
{
    $address = [
        "company" => $fields["company"],
        "first_name" => $fields["first_name"],
        "last_name" => $fields["last_name"],
        "address_1" => $fields["address_1"],
        "address_2" => $fields["address_2"],
        "postcode" => "",
        "city" => $fields["postcode"] . " " . $fields["city"],
        "state" => $fields["state"],
        "country" => $fields["country"],
    ];
    return $address;
}

/* Woocommerce Print Invoices & Delivery Notes */

add_action("wcdn_before_logo", "burelade_logo",$template);
function burelade_logo($order) 
{ ?>
    <style>
        .wcdn-logo {
            text-align: center;
            margin-bottom: -7rem;
            position: relative;
            height: 6rem;
            margin-right: 7rem;
        }
        .wcdn-logo img {
            max-height: 100px;
        }
    </style>
<?php
}

add_action("wcdn_before_branding", "burelade_header",$template);
function burelade_header($order) 
{ ?>
    <style>
        .printdate {
            position: absolute;
            text-align: right;
            margin-top: 21px;
            right: 50px;
        }
    </style>
    <span class="printdate"><?= $order["documentDate"] ?></span>
<?php
}

add_action("wcdn_before_policies", "burelade_quittung",$template);
function burelade_quittung($order)
{ ?>
<style>
.container {
  display: grid;
  grid-template-areas:
    "header header"
    "signature line-1"
    "thankyou line-2";
  grid-template-columns: 2fr 3fr;
  gap: 1rem;
  margin: 0 4rem 4rem;
}
.container div.header {
  grid-area: header;
}
.container div.thankyou {
  grid-area: thankyou;
}
.container div.signature {
  grid-area: signature;
}
.container div.line-1 {
  grid-area: line-1;
}
.container div.line-2 {
  grid-area: line-2;
}
.container div.label {
  align-content: end;
}
.container div.lines {
  height: 4rem;
}
.container div.lines > div {
border-bottom: 1px black solid;
  height: 100%;
}
</style>
<div class="container">
    <div class="header"><h2>Quittung</h2></div>

    <div class="label thankyou">Betrag dankend erhalten den:</div>
    <div class="lines line-1"><div></div></div>
    <div class="label signature">Unterschrift:</div>
    <div class="lines line-2"><div></div></div>
</div>
    
<style>
  hr { visibility: hidden; }
  .wcdn-address-grid {  
    padding-left: 52%;
  }
  .wcdn-address-grid .wcdn-billing-address > strong {
    font-weight: normal;
    font-size: 10px;
    border-bottom: 1px black solid;
    display: inline-block;
    padding: 0 10px;
    margin: 0 -10px 10px;
  }
</style>
<?php
}

// Append a custom billing note as a final address line. $lines:
//     Array
//     (
//         [0] => Breitestrasse 6
//         [1] => Oberuzwil
//         [2] => 9242
//     )
add_filter( 'wcdn_billing_address', function( $lines, $order ) {
    $lines[1] = $lines[2].' '.$lines[1];
    unset($lines[2]);
    return $lines;
}, 10, 2 );


add_action("wcdn_after_addresses", "burelade_title",$template);
function burelade_title($order) 
{ ?>
    <style>
        /* .printdate {
            position: absolute;
            text-align: right;
            margin-top: 21px;
            right: 50px;
        } */
    </style>
    <h2>Rechnung</h2>
    <div style="margin-bottom: 3rem">Nr.: <?= substr($order["date"], 0, 4)."-".$order["orderNumber"] ?></div>
<?php
}
/* $order:
Array
(
    [id] => 4948
    [invoiceNumber] => INV-4948
    [documentDate] => 23. April 2026
    [orderNumber] => 4948
    [date] => 2026-04-23T10:45:39
    [paymentDate] => 2026-04-23T11:07:39
    [paymentMethod] => Barbezahlung
    [shippingMethod] => 
    [currency] => CHF
    [payment_url] => 
    [customer_note] => 
    [billing] => Array
        (
            [name] => Martin Schilliger
            [address] => Array
                (
                    [0] => Breitestrasse 6
                    [1] => Oberuzwil
                    [2] => 9242
                )

            [phone] => 0795618716
            [email] => martin_schilliger@me.com
        )

    [shipping] => Array
        (
            [name] =>  
            [address] => Array
                (
                )

        )

    [items] => Array
        (
            [0] => Array
                (
                    [name] => Deckel Schwarz 48mm zu Konfiglas 106ml
                    [sku] => 
                    [price] => CHF  0.20
                    [quantity] => 1
                    [total] => CHF  0.20
                    [product_id] => 3103
                    [order_item_id] => 16779
                    [meta] => Array
                        (
                        )

                    [addon] => 
                    [image_url] => https://www.burelade.ch/wp-content/uploads/2023/03/48mm-Schwarz-scaled.jpeg
                    [image_path] => /home/burelade/www/burelade.ch/wp-content/uploads/2023/03/48mm-Schwarz-scaled.jpeg
                )

        )

    [totals] => Array
        (
            [subtotal] => CHF  0.19
            [shipping] => CHF  0.00
            [total] => CHF  0.20
            [tax_lines] => Array
                (
                    [0] => Array
                        (
                            [label] => MwSt.
                            [value] => CHF  0.01
                        )

                )

            [tax] => CHF  0.01
        )

    [refund] => Array
        (
            [date] => 05. Juni 2026
            [reason] => Customer returned item
            [total] => CHF  0.20
            [items] => Array
                (
                    [0] => Array
                        (
                            [name] => Deckel Schwarz 48mm zu Konfiglas 106ml
                            [sku] => 
                            [quantity] => 1
                            [price] => CHF  0.20
                            [total] => CHF  0.20
                            [meta] => Array
                                (
                                )

                            [addon] => 
                        )

                )

        )

    [status] => completed
    [extra_fields] => Array
        (
        )

)


*/


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
