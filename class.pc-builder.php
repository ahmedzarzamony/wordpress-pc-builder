<?php

class PCBUILDER{

    private static $initiated = false;
	
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
    
    public static function pluginActivation() {
        
    }
    
    public static function pluginDeactivation() {
        unregister_post_type( 'products');
        flush_rewrite_rules();
    }
    
    public static function pluginUninstall() {
        
    }

    private static function init_hooks() {
        add_theme_support( 'post-thumbnails' );
        self::registerProductsType();
        self::registerComponentsTaxonomy();
        self::registerBrandsTaxonomy();
        add_action( 'admin_init',['PCBUILDER', 'backStyle']);
        add_action( 'wp_enqueue_scripts', ['PCBUILDER', 'frontStyle'] );
        add_action( 'wp_ajax_call_pc_products', ['PCBUILDER', 'call_pc_products'] );
        add_action( 'manage_edit-products_columns', ['PCBUILDER', 'showTaxonomyCols'] );
        add_action( 'manage_posts_custom_column', ['PCBUILDER', 'showTaxonomyValuesForCols'] );
        //add_filter('single_template', ['PCBUILDER', 'productsSingeTemplate']);
        //add_filter('taxonomy_template', ['PCBUILDER', 'productsTaxonomyTemplate']);
    }
    
    public static function registerProductsType() {
        register_post_type('products', array(
            'labels'      => array(
                'name'               => _x( 'PC Products', 'post type general name', 'pc-builder-textdomain' ),
                'singular_name'      => _x( 'Product', 'post type singular name', 'pc-builder-textdomain' ),
                'menu_name'          => _x( 'PC Products', 'admin menu', 'pc-builder-textdomain' ),
                'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'pc-builder-textdomain' ),
                'add_new'            => _x( 'Add New', 'product', 'pc-builder-textdomain' ),
                'add_new_item'       => __( 'Add New Product', 'pc-builder-textdomain' ),
                'new_item'           => __( 'New Product', 'pc-builder-textdomain' ),
                'edit_item'          => __( 'Edit Product', 'pc-builder-textdomain' ),
                'view_item'          => __( 'View Product', 'pc-builder-textdomain' ),
                'all_items'          => __( 'All Products', 'pc-builder-textdomain' ),
                'search_items'       => __( 'Search Products', 'pc-builder-textdomain' ),
                'parent_item_colon'  => __( 'Parent Products:', 'pc-builder-textdomain' ),
                'not_found'          => __( 'No Products found.', 'pc-builder-textdomain' ),
                'not_found_in_trash' => __( 'No Products found in Trash.', 'pc-builder-textdomain' ),
            ),
            'menu_icon'           => plugins_url('assets/ship.png',__FILE__ ),
            'taxonomies'            => ['components', 'brands'],
            'show_in_admin_bar'   => true,
            'public'      => true,
            'has_archive' => false,
            'rewrite' => ['slug' => 'products', 'with_front' => FALSE],
            'supports'    => ['title', 'thumbnail', 'editor']
        ));
    }

    public static function registerComponentsTaxonomy(){
        register_taxonomy('components', 'products', array(
            'label' => 'Components',
            'hierarchical' => true,
            'parent_item'  => null,
            'parent_item_colon' => null,
            'labels' => [
                'all_items'         => __( 'All Components', 'textdomain' ),
                'edit_item'         => __( 'Edit Components', 'textdomain' ),
                'update_item'       => __( 'Update Component', 'textdomain' ),
                'add_new_item'      => __( 'Add New Component', 'textdomain' ),
                'new_item_name'     => __( 'New Component Name', 'textdomain' ),
                'menu_name'         => __( 'Components', 'textdomain' ),
            ]
        ));
    }
    

    public static function registerBrandsTaxonomy(){
        register_taxonomy('brands', 'products', array(
            'label' => 'Brands',
            'hierarchical' => true,
            'parent_item'  => null,
            'parent_item_colon' => null,
            'labels' => [
                'all_items'         => __( 'All Brands', 'pc-builder-textdomain' ),
                'edit_item'         => __( 'Edit Brand', 'pc-builder-textdomain' ),
                'update_item'       => __( 'Update Brand', 'pc-builder-textdomain' ),
                'add_new_item'      => __( 'Add New Brand', 'pc-builder-textdomain' ),
                'new_item_name'     => __( 'New Brand Name', 'pc-builder-textdomain' ),
                'menu_name'         => __( 'Brands', 'pc-builder-textdomain' ),
            ]
        ));
    }

    public static function backStyle() {
        wp_register_style('PCBUILDER-CSS', plugins_url('assets/style.css',__FILE__ ));
        wp_enqueue_style('PCBUILDER-CSS');
        wp_register_script( 'PCBUILDER-JS', plugins_url('assets/script.js',__FILE__ ), ['jquery']);
        wp_enqueue_script('PCBUILDER-JS');
    }

    public static function frontStyle() {
        wp_register_style('PCBUILDER-CSS', plugins_url('assets/front.css',__FILE__ ));
        wp_enqueue_style('PCBUILDER-CSS');
        wp_enqueue_script( 'ajax-script', plugins_url('assets/front.js',__FILE__ ) , array('jquery') );
        wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php'), 'plugin_url' => plugins_url('/',__FILE__ ) ) );
    }

    public static function call_pc_products() {
        $component = sanitize_text_field($_POST['component']);
        if($component == ''){
            echo @json_encode([]);
        } 
        $data = new WP_Query([
                'post_type' => 'products',
                'tax_query' => [
                    ['taxonomy' => 'components', 'terms' => $component, 'field' => 'name' ]
                ]
            ]
        );
        $obj = [];
	    if(empty($data->posts)){
            echo [];
        }else{
            $i = 0;
            foreach($data->posts as $post){
                $brand = wp_get_post_terms($post->ID, 'brands');
                $obj[$i]['name'] = sanitize_text_field($post->post_title);
                $obj[$i]['url'] = get_post_permalink($post->ID);
                $obj[$i]['price']= (float)get_post_meta($post->ID, 'pcbuilder_mprice', true);
                $obj[$i]['brand'] = !empty($brand) ? sanitize_text_field($brand[0]->name) : 'Undefined';
                $i++;
            }
        }
        echo @json_encode($obj);
        wp_die();
    }

    public static function showTaxonomyValuesForCols($column ) {
        if($column  == 'Brand') {
            $brand = wp_get_post_terms(get_the_ID(), 'brands');
            if(!empty($brand)){
                echo $brand[0]->name;
            }else{
                echo '-';
            }
        }elseif($column  == 'Components') {
            $component = wp_get_post_terms(get_the_ID(), 'components');
            if(!empty($component)){
                echo $component[0]->name;
            }else{
                echo '-';
            }
        }
    }

    public static function showTaxonomyCols($columns ) {
        $columns['Brand'] = 'Brand';
        $columns['Components'] = 'Components';
        unset($columns['date']);
        return $columns;
    }

    public static function productsSingeTemplate($single) {
        global $post;
        if ( $post->post_type == 'products' ) {
                return PCBUILDER__PLUGIN_DIR . '/single-products.php';
        }
        return $single;
    }

}