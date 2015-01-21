<?php

/**
 * Register custom post type for products
 *
 * Thank you Just Tadlock for posting this comprehensive snippet for registering post types
 * http://justintadlock.com/archives/2013/09/13/register-post-type-cheat-sheet
 */

function cc_register_product_post_type() {

    register_taxonomy(
		'product',
		'products',
		array(
			'label' => __( 'Product Categories' ),
			'rewrite' => array( 'slug' => 'products' ),
            'hierarchical' => true
		)
	);

	/* Set up the arguments for the post type. */
	$args = array(

		/*
		 * A short description of what your post type is. As far as I know, this isn't used anywhere 
		 * in core WordPress.  However, themes may choose to display this on post type archives. 
		 */
		'description'         => __( 'Cart66 Products', 'cart66' ), // string

		/* 
		 * Whether the post type should be used publicly via the admin or by front-end users.  This 
		 * argument is sort of a catchall for many of the following arguments.  I would focus more 
		 * on adjusting them to your liking than this argument.
		 */
		'public'              => true, // bool (default is FALSE)

		/*
		 * Whether queries can be performed on the front end as part of parse_request(). 
		 */
		'publicly_queryable'  => true, // bool (defaults to 'public').

		/*
		 * Whether to exclude posts with this post type from front end search results.
		 */
		'exclude_from_search' => false, // bool (defaults to FALSE - the default of 'internal')

		/*
		 * Whether individual post type items are available for selection in navigation menus. 
		 */
		'show_in_nav_menus'   => true, // bool (defaults to 'public')

		/*
		 * Whether to generate a default UI for managing this post type in the admin. You'll have 
		 * more control over what's shown in the admin with the other arguments.  To build your 
		 * own UI, set this to FALSE.
		 */
		'show_ui'             => true, // bool (defaults to 'public')

		/*
		 * Whether to show post type in the admin menu. 'show_ui' must be true for this to work. 
		 */
		'show_in_menu'        => true, // bool (defaults to 'show_ui')

		/*
		 * Whether to make this post type available in the WordPress admin bar. The admin bar adds 
		 * a link to add a new post type item.
		 */
		'show_in_admin_bar'   => true, // bool (defaults to 'show_in_menu')

		/*
		 * The position in the menu order the post type should appear. 'show_in_menu' must be true 
		 * for this to work.
		 */
		'menu_position'       => null, // int (defaults to 25 - below comments)

		/*
		 * The URI to the icon to use for the admin menu item. There is no header icon argument, so 
		 * you'll need to use CSS to add one.
		 */
		'menu_icon'           => 'dashicons-tag', // string (defaults to use the post icon)

		/*
		 * Whether the posts of this post type can be exported via the WordPress import/export plugin 
		 * or a similar plugin. 
		 */
		'can_export'          => true, // bool (defaults to TRUE)

		/*
		 * Whether to delete posts of this type when deleting a user who has written posts. 
		 */
		'delete_with_user'    => false, // bool (defaults to TRUE if the post type supports 'author')

		/*
		 * Whether this post type should allow hierarchical (parent/child/grandchild/etc.) posts. 
		 */
		'hierarchical'        => false, // bool (defaults to FALSE)

		/* 
		 * Whether the post type has an index/archive/root page like the "page for posts" for regular 
		 * posts. If set to TRUE, the post type name will be used for the archive slug.  You can also 
		 * set this to a string to control the exact name of the archive slug.
		 */
		'has_archive'         => 'products', // bool|string (defaults to FALSE)

		/*
		 * Sets the query_var key for this post type. If set to TRUE, the post type name will be used. 
		 * You can also set this to a custom string to control the exact key.
		 */
		'query_var'           => 'products', // bool|string (defaults to TRUE - post type name)

		/*
		 * A string used to build the edit, delete, and read capabilities for posts of this type. You 
		 * can use a string or an array (for singular and plural forms).  The array is useful if the 
		 * plural form can't be made by simply adding an 's' to the end of the word.  For example, 
		 * array( 'box', 'boxes' ).
		 */
		'capability_type'     => 'product', // string|array (defaults to 'post')

        'taxonomies' => array('product'),

		/*
		 * Whether WordPress should map the meta capabilities (edit_post, read_post, delete_post) for 
		 * you.  If set to FALSE, you'll need to roll your own handling of this by filtering the 
		 * 'map_meta_cap' hook.
		 */
		'map_meta_cap'        => true, // bool (defaults to FALSE)

		/* 
		 * How the URL structure should be handled with this post type.  You can set this to an 
		 * array of specific arguments or true|false.  If set to FALSE, it will prevent rewrite 
		 * rules from being created.
		 */
		'rewrite' => array(

			/* The slug to use for individual posts of this type. */
			'slug'       => 'product', // string (defaults to the post type name)

			/* Whether to show the $wp_rewrite->front slug in the permalink. */
			'with_front' => false, // bool (defaults to TRUE)

			/* Whether to allow single post pagination via the <!--nextpage--> quicktag. */
			'pages'      => true, // bool (defaults to TRUE)

			/* Whether to create pretty permalinks for feeds. */
			'feeds'      => true, // bool (defaults to the 'has_archive' argument)

			/* Assign an endpoint mask to this permalink. */
			'ep_mask'    => EP_PERMALINK, // const (defaults to EP_PERMALINK)
		),

		/*
		 * What WordPress features the post type supports.  Many arguments are strictly useful on 
		 * the edit post screen in the admin.  However, this will help other themes and plugins 
		 * decide what to do in certain situations.  You can pass an array of specific features or 
		 * set it to FALSE to prevent any features from being added.  You can use 
		 * add_post_type_support() to add features or remove_post_type_support() to remove features 
		 * later.  The default features are 'title' and 'editor'.
		 */
		'supports' => array(

			/* Post titles ($post->post_title). */
			'title',

			/* Post content ($post->post_content). */
			'editor',

			/* Post excerpt ($post->post_excerpt). */
			'excerpt',

			/* Post author ($post->post_author). */
			'author',

			/* Featured images (the user's theme must support 'post-thumbnails'). */
			'thumbnail',

			/* Displays comments meta box.  If set, comments (any type) are allowed for the post. */
			'comments',

			/* Displays meta box to send trackbacks from the edit post screen. */
			'trackbacks',

			/* Displays the Custom Fields meta box. Post meta is supported regardless. */
			'custom-fields',

			/* Displays the Revisions meta box. If set, stores post revisions in the database. */
			'revisions',

			/* Displays the Attributes meta box with a parent selector and menu_order input box. */
			'page-attributes',

			/* Displays the Format meta box and allows post formats to be used with the posts. */
			'post-formats',
		),

		/*
		 * Labels used when displaying the posts in the admin and sometimes on the front end.  These 
		 * labels do not cover post updated, error, and related messages.  You'll need to filter the 
		 * 'post_updated_messages' hook to customize those.
		 */
		'labels' => array(
			'name'               => __( 'Products',                   'cart66' ),
			'singular_name'      => __( 'Product',                    'cart66' ),
			'menu_name'          => __( 'Products',                   'cart66' ),
			'name_admin_bar'     => __( 'Products',                   'cart66' ),
			'add_new'            => __( 'Add New',                    'cart66' ),
			'add_new_item'       => __( 'Add New Product',            'cart66' ),
			'edit_item'          => __( 'Edit Product',               'cart66' ),
			'new_item'           => __( 'New Product',                'cart66' ),
			'view_item'          => __( 'View Product',               'cart66' ),
			'search_items'       => __( 'Search Products',            'cart66' ),
			'not_found'          => __( 'No products found',          'cart66' ),
			'not_found_in_trash' => __( 'No products found in trash', 'cart66' ),
			'all_items'          => __( 'All Products',               'cart66' ),

			/* Labels for hierarchical post types only. */
			'parent_item'        => __( 'Parent Product',             'cart66' ),
			'parent_item_colon'  => __( 'Parent Product:',            'cart66' ),

			/* Custom archive label.  Must filter 'post_type_archive_title' to use. */
			'archive_title'      => __( 'Products',                   'cart66' ),
		)
	);

	/* Register the post type. */
	register_post_type(
		'product', // Post type name. Max of 20 characters. Uppercase and spaces not allowed.
		$args      // Arguments for post type.
	);
}
