This README contains all the information for setting up a working enviroment ready for 
custom WordPress theme development as well as a help guide for the most useful WordPress functions and practices

Matt Persell-Thompson
Web Developer
-------------







Setting up custom WordPress theme development environment ------------------------------------------------------------


1. Download 'Local' to create local WP sites (localwp.com)
2. Once site has been created, click on 'Go to site folder' button
3. Clone this Github respository into the following location 'app/public/wp-content/themes' 
4. Change the name of this folder from 'custom-wp-theme' to 'project-name-custom-theme' (optional)
5. Open up folder in Text Editor



!!!!!!! IF USING WINDOWS !!!!!!!

* Delete package-lock.json file
* Remove 'fsevents' section from 'package.json' as this is a MacOS only function






Run following commands in theme directory to install all necessary packages & dependencies  ------------------------------------------------------------


1. npm init (Creates package.json)
2. npm i
3. npm audit fix --force (Fixes any packages that are are outdated)

!! Only if error run !! 
4. npm install laravel-mix --save-dev
5. npm install webpack-livereload-plugin@1 --save-dev
6. npm install -g sass





Run following commands in theme directory to start up dev environment ------------------------------------------------------------

* npx mix watch   - Compile JS & SCSS files Automatically (You'll need this!!!)
* npx mix         - Will bring back styles.css & script.js files if deleted and compile manually





Live server set up  ------------------------------------------------------------

1. Install Live Server VS Code Extension by Ritwick Dey and activate
2. Install Live Server Web Extension (Should have the same icon)
3. Click 'Go Live' in bottom right corner of Text Editor (Should change to 'Port : {Port Number}')
4. Copy the URL of the page that the browser opens up and paste into 'Live Server Address' field in the browser extension
5. Copy the URL of the local Wordpress site's home page (website-name.local) and paste into 'Actual Server Address' field in the browser extension
6. Run 'npx mix watch' if you haven't already and refresh browser.
7. Test by changing colour of something 





Pulling live WP site to make changes locally ------------------------------------------------------------

1. Use 'All In One Migration' plugin to export the live site to a .wordpress file. 
2. Create a new site locally and import this file also using the 'All In One Migration' plugin.
3. DELETE 'node_modules' directory and 'package-lock.json' file from project and run "npm i".








Things to note   ------------------------------------------------------------



* Recurring plugins used :
     - Advanced Custom Fields
     - All In One Migration (Importing / Exporting)
     - WPCode (Header & Footer Insertions)
     - Contact Form 7 (Form Submissions)
     - Filter Everything (Filtration)



* The 'src' directory holds the 'js' and 'scss' folders which house the current and any new .js and .scss files. Changes made here wont be visible unless Laravel Mix is running (npx mix watch)

* Creating new .js and .scss files: 
   - When creating .scss files make sure to import in the style.scss file    (eg. @import "./components/buttons";)
   - When creating .js files make sure to import in script.js file           (eg. import "./filtration/project-filtration";)









/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////








WordPress Theme Development Cheat Sheet  ----------------------------



* WP Display Functions


HEADER CONTENTS                                    ------   <?php echo get_header();?>

FOOTER CONTENTS                                    ------   <?php echo get_footer();?>

TEMPLATE PART                                      ------   <?php echo get_template_part('includes/components/page-title-banner')?>

PAGE / POST TITLE                                  ------   <?php echo the_title();?>

CONTENT                                            ------   <?php echo the_content();?>

EXCERPT                                            ------   <?php echo the_excerpt();?>

IMAGES FROM 'IMAGES' FOLDER                        ------   <?php echo get_theme_file_uri('assets/images/picture.png');?>

LINK TO SPECIFIC PAGE                              ------   <?php echo site_url('/page-name');?>

ACF CUSTOM FIELDS                                  ------   <?php echo the_field('name_of_custom_field')?>

ACF CUSTOM FIELDS FROM ANOTHER PAGE                ------   <?php echo the_field('name_of_custom_field', 20)?>

CUSTOM 'CUSTOMISE' CONTROLS                        ------   <?php echo get_theme_mod('name-of-custom-theme-mod')?> 

POST SHORTCODE                                     ------   <?php echo do_shortcode('[your_shortcode]')?> 






* WP page naming conventions


- SINGLE POST PAGES

single.php                                                  ----  Created and used for individual 'post' pages
single-{custom-post-type-name}.php                          ----  Created and used for individual pages for your custom post types



- PAGES / TEMPLATES 

page-{page-slug}.php                                        ----  Will automatically be assigned to the page of the same name.
// Template Name: Your Page Name Template                   ----  Add underneath get_header(); of any page php file and select in correspsonding page on WP to display for specific pages



- ARCHIVE PAGES (Make sure post type has this initialed)

archive-{custom-post-type-name}.php                         ----  Created and used for pages that display all of a post type (ie. Projects)








 

DISPLAY MENU 


/////////////////////////////////////////////////////////////////////////////////////////////////////////////


    <?php
    wp_nav_menu( array(
                    'menu'           => 'Your Menu Name',
                    'theme_location' => 'main-menu',
                    'depth'    => 2
                ) );
    ?>



/////////////////////////////////////////////////////////////////////////////////////////////////////////////











THE WORDPRESS LOOP (PULL IN POSTS / CUSTOM POST TYPES )


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


!!! Alternatively you can just create Custom Post Types in ACF itself !!!





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










CREATING NEW CUSTOM 'CUSTOMISE' CONTROL FOR GLOBAL FIELDS (ie Company Contact Details)


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



ARCHIVE PAGES 


<?php if ( is_post_type_archive( 'project' ) ) { ?>
    <h1>This only appears on a single post type with an id of 22</h1>
<?php } ?>


/////////////////////////////////////////////////////////////////////////////////////////////////////////////











SETTING UP 'FILTER EVERYTHING' PLUG IN. 


/////////////////////////////////////////////////////////////////////////////////////////////////////////////



* Without the PRO version Filter Everything can only be implemented on archive-{post-type}.php pages.
* Make sure your post type has Archives initialised either with 'has_archive => true' on in any plug ins set up with.
* Make sure you post type has your filters created and assigned to them using ACF.
* Once archive page has been created and filters have been configured post [fe_widget] shortcode into that file. 
* Use the following function (modified by Filter Everything) to pull in your custom post type you wish to be filterable



        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                if (get_post_type() === 'testimonial') { 
                    ?>
                    <div class="testimonial">
                        <h1><a class="txt-black" href="<?php echo the_permalink();?>"><?php the_title(); ?></a></h1>
                        <!-- <p><?php echo esc_html(get_field('testimonial_content')); ?></p> -->
                    </div>
                    <?php
                }
            endwhile;
        else :
            echo '<p>No testimonials found.</p>';
        endif;
        ?>

    

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
