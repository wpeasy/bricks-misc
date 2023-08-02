<?php

function set_category_color() {

    /* wp_head may be called more than once. This ensures the function only runs once */
    static $_done;
    if($_done){ return; }
    $_done = true;

    // Get the categories for the current post
    $categories = get_the_category();

    /* If no categories, maybe this is a preview post in a Bricks Template? */
    if(empty($categories)){
        $maybe_preview_id = \Bricks\Helpers::get_template_setting( 'templatePreviewPostId', get_the_id() );
        if($maybe_preview_id ){
            $categories = get_the_category($maybe_preview_id);
        }
    }

    /* Set defaults */
    $category_color_primary = 'var(--bricks-color-primary)';
    $category_color_secondary = 'var(--bricks-text-dark)';

    /* Iterate all post categories, if one exists with 'category_color', assign it to $color_cat */
    array_walk($categories, function($item, $key) use(&$category_color_primary, &$category_color_secondary){
        $primary = get_term_meta( $item->term_id, 'category_color_primary', true);
        if(!empty($primary)){
            $category_color_primary = $primary;
        }

        $secondary= get_term_meta( $item->term_id, 'category_color_secondary', true);
        if(!empty($secondary)){
            $category_color_secondary= $secondary;
        }
    });

    echo "<style id='cat-colors'>:root{ --category-color--primary : $category_color_primary;  --category-color--secondary : $category_color_secondary }</style>";
}

add_action('wp_head', 'set_category_color');
