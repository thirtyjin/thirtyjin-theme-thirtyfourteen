<?php

error_reporting(E_ALL & ~E_NOTICE);

/* Step 2 Registering the New Type */
/* Thumbnail & Featured Image Support */


/* Creating a New Post Type */

add_action('init', 'portfolio_register');

function portfolio_register() {

	$labels = array(
			'name' => __('Portfolio', 'twentyfourteen' ),
			'singular_name' => __('Portfolio Item', 'twentyfourteen' ),
			'add_new' => __('Add New', 'twentyfourteen' ),
			'add_new_item' => __('Add New Portfolio Item', 'twentyfourteen' ),
			'edit_item' => __('Edit Portfolio Item', 'twentyfourteen' ),
			'new_item' => __('New Portfolio Item', 'twentyfourteen' ),
			'view_item' => __('View Portfolio Item', 'twentyfourteen' ),
			'search_items' => __('Search Portfolio', 'twentyfourteen' ),
			'not_found' => __( 'No Portfolio found', 'twentyfourteen' ),
			'not_found_in_trash' => __( 'No Portfolio found in Trash', 'twentyfourteen' )
	);

    $args = array(
        'labels' => $labels,
    	'label' => __( 'Portfolio', 'twentyfourteen' ),
    	//'menu_icon' => get_template_directory_uri() .'/inc/images/portfolio_icon.png',
    	'menu_icon' => 'dashicons-cart',
    	'menu_position' => 5,
        'singular_label' => __('portfolio_project', 'twentyfourteen'),
        'public' => true,
        'show_ui' => true,
    	'show_in_nav_menus' => true,
    	'show_in_menu' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
    	'has_archive' => 'portfolio', // The archive slug ; Default: false ;
    	'rewrite' => array( 'slug' => 'portfolio', 'with_front' => false ),
    	'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
        'taxonomies' => array('project-type') // this is IMPORTANT
       );

    register_post_type( 'portfolio' , $args );
}



/* Adding a Custom Taxonomy */

function taxonomies_portfolio() {

    $args = array(
			'public' => true,
			'hierarchical' => true,
			'label' => __('Portfolio Types', 'twentyfourteen' ),
			"labels" => array(
					'name' => __('Portfolio Types', 'twentyfourteen' ),
					'singular_name' => __('Portfolio Type', 'twentyfourteen' ),
			),
			'singular_label' => __('Portfolio Type', 'twentyfourteen' ),
			'show_in_nav_menus' => true,
			'rewrite' => true
	);

    register_taxonomy( 'project-type', array('portfolio'), $args );
}

add_action( 'init', 'taxonomies_portfolio', 0 );






/* Step 3 Customizing Admin Columns */

add_filter("manage_edit-portfolio_columns", "project_edit_columns");

function project_edit_columns($columns){
        $columns = array(
            "cb" 			=> "<input type=\"checkbox\" />",
            "title" 		=> __( 'Project', 'twentyfourteen' ),
        	"thumbnail" 	=> __( 'Thumbnail', 'twentyfourteen' ),
            "description" 	=> __( 'Description', 'twentyfourteen' ),
            "type" 			=> __( 'Type of Project', 'twentyfourteen' ),
        );

        return $columns;
}

add_action("manage_portfolio_posts_custom_column",  "project_custom_columns");

function project_custom_columns($column){
        global $post;
        switch ($column)
        {
            case "description":
                the_excerpt();
                break;
            case "type":
                echo get_the_term_list($post->ID, 'project-type', '', ',','');
                break;
            case "thumbnail":
                echo the_post_thumbnail('thumbnail');
                break;
        }
}




//http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts

function set_admin_portfolio_posts_per_page( $query ) {

	if ( is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', 6 );
		return;
	}

}
add_action( 'pre_get_posts',  'set_admin_portfolio_posts_per_page'  );





?>
