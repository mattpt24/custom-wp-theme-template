

This README contains all the information for setting up a working enviroment ready for 
custom WordPress theme development as well as a help guide for the most used WordPress functions and practices

Matthew Persell-Thompson
Web Developer
-------------










Setting up custom theme development enviroment ------------------------------------------------------------



* Download 'Local' to create local WP sites (localwp.com)

* This cloned github respository should be downloaded into the local wordpress site's 'wp-content/themes' directory

* Change the name of this directory from 'custom-wp-theme' to 'project-name-custom-theme' (optional)

* Main plugins used - ACF , All In One Migration, Header & Footer




!!!!!!! IF USING WINDOWS !!!!!!!

* Delete package-lock.json file
* Remove 'fsevents' section from 'package.json' as this is a MacOS only function








///////////////////////////////////////////////////////////////////////////




DOWNLOAD ALL NECESSARY PACKAGES & DEPENDENCIES (Compiling, Live Reloading)
Make sure node.js is installed (node -v)


npm init -y
npm i

OR
npm install laravel-mix --save-dev
npm install webpack-livereload-plugin@1 --save-dev
npm install -g sass



-



LIVE SERVER SET UP

* Install Live Server VS Code Extension by Ritwick Dey and activate
* Install Live Server Web Extension (Should have the same icon)
* Click 'Go Live' in bottom right corner (Should change to 'Port : 5500')
* Copy the URL of the page that the browser opens up and paste into 'Live Server Address' field in the extension
* Copy the URL of the local Wordpress site's home page (website-name.local) and paste into 'Actual Server Address' field in the extension
* Run 'npx mix watch' and refresh browser
* Test by changing colour of something 



npx mix watch - Compile JS & SCSS files Automatically (Recommended)
npx mix - Will bring back styles.css & script.js files if deleted and compile manually




- 



PULLING LIVE VERSION TO EDIT LOCALLY
- Import WP file exported from live version of site trying to edit (All In One Migration)
- DELETE 'node_modules' directory and 'package-lock.json' file from project and run "npm i"


























WordPress Theme Development Help Guide  ----------------------------





TITLE                            ------   <?php echo the_title();?>

EXCERPT                          ------   <?php echo the_excerpt();?>

CONTENT                          ------   <?php echo get_the_content();?>

IMAGES FROM 'IMAGES' FOLDER      ------   <?php echo get_theme_file_uri('images/picture.png');?>

LINK TO SPECIFIC PAGE            ------   <?php echo site_url('/page-name');?>

ACF CUSTOM FIELDS                ------   <?php echo the_field('name_of_custom_field')?>

CUSTOM 'CUSTOMISE' CONTROLS      ------   <?php echo get_theme_mod('name-of-custom-theme-mod')?> 





SINGLE POST PAGES

single.php                                  -----  Used for individual pages for standard posts

single-{custom-post-type-name}.php            -----  Used for individual pages for custom post types



PAGE TEMPLATES

// Template Name: Your Page Name Template   -----  Add underneath get_header(); of any page php file and select in correspsonding page on WP to display for specific pages







 

MENUS 


/////////////////////////////////////////////////////////////////////////////////////////////////////////////


    <?php
    wp_nav_menu( array(
                    'menu'           => 'Your Menu Name',
                    'theme_location' => 'main-menu',
                    'depth'    => 2
                ) );
    ?>



/////////////////////////////////////////////////////////////////////////////////////////////////////////////















THE WORDPRESS LOOP 


/////////////////////////////////////////////////////////////////////////////////////////////////////////////


<?php 

$anyName = new WP_Query(array(
'posts_per_page' => -1,                    
'post_type'      => 'your-post-type-name', 
'orderby'        => 'post_date',
'order'          => 'DESC',
'meta_tag'       => 'custom-field-name',  
'meta_value'     => 'custom-field-value' 
 ));
 

while($anyName->have_posts()) {
    $anyName->the_post(); ?>

    <h1><?php echo the_title();?></h1>

<?php 
wp_reset_query();
}?>


/////////////////////////////////////////////////////////////////////////////////////////////////////////////














CUSTOM POST TYPES 


/////////////////////////////////////////////////////////////////////////////////////////////////////////////


1. Create an 'mu-plugins' folder in your local WP sites 'wp-content' folder

2. Create a 'custom-post-type.php' within the 'mu-plugins' folder

3. In this file use the below function to create custom post types (Using 'Projects' as an example)

4. Make sure there is NO white space in this file as it breaks WP when live

* USE ACF plugin by 'WP Engine' to assign your custom post type custom fields





<?php

function custom_post_types() {
    register_post_type('Projects', array(
        'supports' => array('title', 'except'),
        'rewrite' => array('slug' => 'projects'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Projects',
            'add_new_item' => 'Add New Project',
            'edit_item' => 'Edit Project',
            'all_items' => 'All Projects',
            'singular_name' => 'Project'
        ),
        'menu_icon' => 'dashicons-admin-home',
    ));

}

add_action('init', 'custom_post_types');

?>


/////////////////////////////////////////////////////////////////////////////////////////////////////////////















CREATING NEW CUSTOM CUSTOMISE CONTROL


/////////////////////////////////////////////////////////////////////////////////////////////////////////////




function new_section($wp_customize) {


    $wp_customize->add_section('section-name', array(
        'title' => 'New Section Name'
    ));
    
    

    $wp_customize->add_setting('section-name-title', array(
        'default' => 'This will display as a default!',
    ));
    


    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'section-name-title-control', array(
        'label' => 'Section Title',
        'section' => 'section-name',           
        'settings' => 'section-name-title'   
        'type' => 'text'                        
    )));    
    
    }


    
    // RUNS THE ABOVE FUNCTION 
    add_action('customize_register', 'new_section');

?>
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

















PULL IN NAMES OF CHILD PAGES TO SPECIFIC PARENT EXCEPT THE PAGE ITS ON


/////////////////////////////////////////////////////////////////////////////////////////////////////////////


            <?php 
                if ( 104 == $post->post_parent ) {
                $pages = get_pages( [
                'sort_order'   => 'ASC',
                'sort_column'  => 'post_title',
                'hierarchical' => 1,
                'exclude'      => get_the_ID(),
                'include'      => '',
                'meta_key'     => '',
                'meta_value'   => '',
                'authors'      => '',
                'child_of'     => 104,
                'parent'       => -1,
                'exclude_tree' => '',
                'number'       => '',
                'offset'       => 0,
                'post_type'    => 'page',
                'post_status'  => 'publish',
            ] );

            foreach( $pages as $post ){
                setup_postdata( $post );
                ?>
                <div class="about-page-link"><a href="<?php echo the_permalink();?>"><?php echo the_title();?></a></div>
                <?php
            }

            wp_reset_postdata();
                }
            ?>


/////////////////////////////////////////////////////////////////////////////////////////////////////////////














SHOW THE PREVIOUS & NEXT POST


/////////////////////////////////////////////////////////////////////////////////////////////////////////////



<?php
    if ( is_singular( 'post' ) ) {
        the_post_navigation(
            array(
                'prev_text' => '<div class="next-post">' . __( '<i class="fa-solid fa-arrow-left"></i> &nbsp;Previous post') . '</div> ' .
                    '<div class="post-title">%title</div>',
                    'next_text' => '<div class="next-post">' . __( '<i class="fa-solid fa-arrow-right"></i> &nbsp; Next post') . '</div> ' .
                    '<div class="post-title">%title</div>'
            )
        );
    }
?>



/////////////////////////////////////////////////////////////////////////////////////////////////////////////











MAKE CONTENT ONLY APPEAR ON CERTAIN PAGES



/////////////////////////////////////////////////////////////////////////////////////////////////////////////


PAGES 

 <?php if (is_page(22)){?>
        <h1>This only appears on page 22</h1>
 <?php } ?>




SINGLE POST TYPES 

<?php if (is_single(22)){?>
    <h1>This only appears on a single post type with an id of 22</h1>
<?php } ?>



/////////////////////////////////////////////////////////////////////////////////////////////////////////////








META SLIDER CAROUSEL WP PLUGIN 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////


** SET UP ACF FIELD WHERE YOU WANT THE CAROUSEL TO APPEAR CALLED 'Image Carousel (image_carousel)' **
** ENTER THE NUMBER OF THE CAROUSEL INTO THE FIELD TO DISPLAY SPECIFIC CAROUSEL **




HTML / PHP ----
<div class="project-modal__carousel"><?php echo do_shortcode( '[metaslider id="'.get_field('image_carousel').'"]' ); ?></div>




SCSS ----

    .project-modal__carousel {
        width: 42vw;
        margin: 0 auto;


        // DOTS
        ol {
            li {
                a {
                    background: white!important;
                }
            }
        }

        .flex-active {
            background: red!important;
            border-color: red!important;
        }



        // NEXT / PREVIOUS BUTTONS
        .flex-prev,
        .flex-next {
            transition: all .3s ease;
            background: red!important;
            border-radius: 100%;
            position: relative;
            opacity: .5!important;
            &:hover {
                transition: all .3s ease;
                opacity: 1!important;
                cursor: pointer;
            }
        }

        .flex-prev {
            &::after {
                content: "";
                height: 8px;
                width: 8px;
                background: none;
                border-left: solid 2px white;
                border-bottom: solid 2px white;
                position: absolute;
                top: 58%;
                left: 40%;
                transform: rotate(45deg) translate(-50%,-50%);
            }
        }


        .flex-next {
            &::after {
                content: "";
                height: 8px;
                width: 8px;
                background: none;
                border-right: solid 2px white;
                border-bottom: solid 2px white;
                position: absolute;
                top: 35%;
                left: 50%;

                transform: rotate(-45deg) translate(-50%,-50%);
            }
        }



        img {
            height: 500px;
        }

        @media screen and (max-width: $tablet) {
            width: 85vw;
        }
         @media screen and (max-width: $mobile) {
            width: 90vw;
        }
    }

}

// END OF IMAGE CAROUSEL STYLES


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

