<?php
    global $post;

    $cats = wp_get_post_terms( $post->ID, "product_cat" );
    foreach ( $cats as $cat ) {
        $cats_array[] .= $cat->term_id;
    }

    $tags = wp_get_post_terms( $post->ID, "product_tag" );
    foreach ( $tags as $tag ) {
        $tags_array[] .= $tag->term_id;
    }

    $related_posts = new WP_Query(
        array(
            'orderby' => 'rand',
            'posts_per_page' => 5,
            'post_type' => 'product',
            'post__not_in' => array($post->ID),
            'tax_query' => array(
                /*
                    'relation' => 'OR',
                    array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $cats_array
                    ),
                */
                    array(
                            'taxonomy' => 'product_tag',
                            'field' => 'id',
                            'terms' => $tags_array
                    )
            )
        )
    );
?>
