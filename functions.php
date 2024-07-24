<?php 
// IMPORTING CSS, CDN's & JS /////////////////////////////////////////////////////////////////////////

function allow_head_content() {

    // JAVASCRIPT PARAMETERS , PATH, ANY DEPENDENCIES?, VERSION NUMBER, LOAD AT THE BOTTOM OF PAGE, MICROTIME PREVENTS CACHING
    wp_enqueue_script('website-main-js', get_theme_file_uri('/script.js'), NULL, microtime(), true);
    wp_enqueue_script('scroll-animations', '//unpkg.com/aos@2.3.1/dist/aos.js', NULL, microtime(), true);



    // IMPORTING CSS STYLESHEET AND OTHER CDN's - FIRST PARAMETER CAN BE ANYTHING
    wp_enqueue_style('custom-google-font', '//fonts.googleapis.com/css2?family=Inter+Tight:wght@300;800&display=swap');
    wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css');
    wp_enqueue_style('animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    wp_enqueue_style('website-main-css', get_stylesheet_uri(), NULL, microtime());
    wp_enqueue_style('scroll-animations-css', '//unpkg.com/aos@2.3.1/dist/aos.css', NULL, microtime());

}

// RUNS FUNCTION TO ENABLE ALL OF THE ABOVE
add_action('wp_enqueue_scripts', 'allow_head_content');


/////////////////////////////////////////////////////////////////////////////////////////////////


// ALLOWS PAGE TITLES TO BE DISPLAYED IN BROWSER TAB
function website_features() {
    register_nav_menu('header_menu_location', 'Header Menu Location');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'website_features');


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>