<?php
// =================
remove_action( 'wp_head', 'wp_generator' );

// =================
if ( ! function_exists( 'mkd_enqueue_scripts_for_home_page' ) ) :
    function mkd_enqueue_scripts_for_home_page() {
        if ( is_front_page() || is_home()) {
            wp_enqueue_style(
                'scroll-slider-style',
                get_template_directory_uri() . '/assets/css/scroll-slider.css',
                array(),
                wp_get_theme()->get( 'Version' )
            );
    
            wp_enqueue_script(
                'scroll-slider-script',
                get_template_directory_uri() . '/assets/js/scroll-slider.js',
                array(),
                wp_get_theme()->get( 'Version' ),
                true // przed zamknięciem tagu body
            );
        }
    }
       
endif;

add_action( 'wp_enqueue_scripts', 'mkd_enqueue_scripts_for_home_page' );


// ==============
