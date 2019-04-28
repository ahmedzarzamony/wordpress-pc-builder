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
        global $wpdb;
        $result = $wpdb->query( 
            $wpdb->prepare("
                DELETE '.$wpdb->prefix.'posts,pt,pm
                FROM '.$wpdb->prefix.'posts posts
                LEFT JOIN '.$wpdb->prefix.'term_relationships pt ON pt.object_id = posts.ID
                LEFT JOIN '.$wpdb->prefix.'postmeta pm ON pm.post_id = posts.ID
                WHERE posts.post_type = %s
                ", 
                'products'
            ) 
        );
    }

    private static function init_hooks() {
        add_theme_support( 'post-thumbnails' );
        self::registerProductsType();
        self::registerComponentsTaxonomy();
        self::registerBrandsTaxonomy();
        self::registerPurposeTaxonomy();
        self::registerExtraTaxonomy();
        add_action( 'admin_init',['PCBUILDER', 'backStyle']);
        add_action( 'wp_enqueue_scripts', ['PCBUILDER', 'frontStyle'] );
        add_action( 'wp_ajax_call_pc_products', ['PCBUILDER', 'call_pc_products'] );
        add_action( 'wp_ajax_call_budget_products', ['PCBUILDER', 'callBudgetProducts'] );
        add_action( 'manage_edit-products_columns', ['PCBUILDER', 'showTaxonomyCols'] );
        add_action( 'manage_posts_custom_column', ['PCBUILDER', 'showTaxonomyValuesForCols'] );
        //add_filter('single_template', ['PCBUILDER', 'productsSingeTemplate']);
        //add_filter('taxonomy_template', ['PCBUILDER', 'productsTaxonomyTemplate']);
        flush_rewrite_rules();
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
    

    public static function registerPurposeTaxonomy(){
        register_taxonomy('purpose', 'products', array(
            'label' => 'Purpose',
            'hierarchical' => true,
            'parent_item'  => null,
            'parent_item_colon' => null,
            'labels' => [
                'all_items'         => __( 'All Purpose', 'pc-builder-textdomain' ),
                'edit_item'         => __( 'Edit Purpose', 'pc-builder-textdomain' ),
                'update_item'       => __( 'Update Purpose', 'pc-builder-textdomain' ),
                'add_new_item'      => __( 'Add New Purpose', 'pc-builder-textdomain' ),
                'new_item_name'     => __( 'New Purpose Name', 'pc-builder-textdomain' ),
                'menu_name'         => __( 'Purpose', 'pc-builder-textdomain' ),
            ]
        ));
    }
    

    public static function registerExtraTaxonomy(){
        register_taxonomy('extra', 'products', array(
            'label' => 'Extra',
            'hierarchical' => true,
            'parent_item'  => null,
            'parent_item_colon' => null,
            'labels' => [
                'all_items'         => __( 'All Extra', 'pc-builder-textdomain' ),
                'edit_item'         => __( 'Edit Extra', 'pc-builder-textdomain' ),
                'update_item'       => __( 'Update Extra', 'pc-builder-textdomain' ),
                'add_new_item'      => __( 'Add New Extra', 'pc-builder-textdomain' ),
                'new_item_name'     => __( 'New Extra Name', 'pc-builder-textdomain' ),
                'menu_name'         => __( 'Extra', 'pc-builder-textdomain' ),
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
        wp_enqueue_script( 'range-slider', plugins_url('assets/range.slider.js',__FILE__ ) , array('jquery') );
        wp_localize_script( 'ajax-script', 'ajax_object', array( 
            'ajax_url' => admin_url( 'admin-ajax.php'), 
            'plugin_url' => plugins_url('/',__FILE__ ),
            'max_extra' => PCBUILDER_MIN_BUDGET_FOR_EXTRA,
            'min_budget' => PCBUILDER_MIN_BUDGET,
            'max_budget' => PCBUILDER_MAX_BUDGET
            ) );
    }

    public static function getMinPrice($arr, $key = 'price') {
        $arr_min = min(array_column($arr, $key));
        $min = array_filter($arr, function ($item)use($key, $arr_min) {
            return ($arr_min == $item[$key]);
        });
        $first = array_keys($min)[0];
        return (array)$min[$first];
    }

    public static function callBudgetProducts() {
        $data = (array)$_POST['data'];
        if(!empty($data)){
            if(!isset($data['price']) or (float)$data['price'] == 0){
                echo 0;
                wp_die();
            }

            /**
             * clean income fields
             */
            $list = new stdClass;
            $list->purpose = '';
            if(isset($data['purpose']))
                $list->purpose = sanitize_text_field($data['purpose']);
            $list->cpu = '';
            if(isset($data['cpu']))
                $list->cpu = sanitize_text_field($data['cpu']);
            $list->gpu = '';
            if(isset($data['gpu']))
                $list->gpu = sanitize_text_field($data['gpu']);
            $list->price = 0;
            if(isset($data['price'])){
                $list_price = (int)$data['price'];
                $list->price = $list_price + (int)($list_price * (10/100));
            }
            $list->extra = '';
            if(isset($data['extra']))
                $list->extra = sanitize_text_field($data['extra']);
            #============================================
            #============================================
            //$tax_query = ['relation' => 'AND'];
            /*if($list->purpose != ''){
                $tax_purpose = [
                    'taxonomy' => 'purpose',                
                    'field' => 'name',                    
                    'terms' => $list->purpose,
                    'operator' => 'IN'    
                ];
                $tax_query[] = $tax_purpose ;
            }
            if($list->extra != ''){
                $tax_extra = [
                    'taxonomy' => 'extra',                
                    'field' => 'name',                    
                    'terms' => $list->extra,
                    'operator' => 'IN'    
                ];
                $tax_query[] = $tax_extra ;
            }*/

            /**
             * query all products based on income price/2, the reason for divide on 2 is becouse noway one component deserve half of full budget
             */
            $args = [
                'post_type' => 'products',
                //'tax_query' => $tax_query,
                'meta_query' => [
                    [
                        'key' => 'pcbuilder_mprice',
                        'compare' => '<=',
                        'value' => $list->price/2,
                        'type' => 'NUMERIC'
                    ]
                ]
            ];
            $query = new WP_Query($args);
            $products = [];
            if(!empty($query->posts)){
                foreach($query->posts as $post){
                    // Get Component
                    $component = get_the_terms( $post->ID, 'components' );
                    if(!empty($component)){
                        $component = @get_term_meta($component[0]->term_id, 'component_type', true);
                        if(trim($component) == '')
                        continue;
                    }
                    // Get Brand
                    $brand = get_the_terms( $post->ID, 'brands' );
                    $brand = empty($brand) ? '' : $brand[0]->name;
                    // Get Purpose
                    $purpose = get_the_terms( $post->ID, 'purpose' );
                    $purpose = empty($purpose) ? '' : $purpose[0]->name;
                    // Get Extra
                    $extra = get_the_terms( $post->ID, 'extra' );
                    $extra = empty($extra) ? '' : $extra[0]->name;
                    #==========================================================
                    #========= Build Blueprint Object
                    #==========================================================
                    /**
                     * Each product that match past steps will append into it's type group
                     */
                    if($component == 'CPU' && trim($list->cpu) != ''){
                        if($brand != $list->cpu){
                            continue;
                        }
                    } 
                    if($component == 'GPU' && trim($list->gpu) != ''){
                        if($brand != $list->gpu){
                            continue;
                        }
                    }  
                    if(in_array($component, ['GPU', 'CPU', 'RAM'])){
                        if(trim($list->purpose) != ''){
                            if($purpose != $list->purpose){
                                continue;
                            }
                        } 
                        if($list->price >= PCBUILDER_MIN_BUDGET_FOR_EXTRA && trim($list->extra) != ''){
                            if($extra != $list->extra){
                                continue;
                            }
                        }
                    }
                    $products[$component][] = [
                        'id' => $post->ID,
                        'name' => $post->post_title,
                        'price' => (float)get_post_meta($post->ID, 'pcbuilder_mprice', true),
                        'component' => $component,
                        'brand' => $brand,
                        'purpse' => $purpose,
                        'extra' => $extra,
                        'url' => get_post_permalink($post->ID),
                    ];

                }
            }
            //print_r($products);wp_die();
            if(empty($query->posts) || empty($products)){
                echo 0;
                wp_die();
            }
            global $pcbuilder_groups;
            $pcbuilder_groups_keys = array_keys($pcbuilder_groups);
            $products_keys = array_keys($products);
            $blueprinted = [];

            foreach($pcbuilder_groups_keys as $key){
                if(in_array($key, $products_keys)){
                        $blueprinted[$key] = [
                            'status' => 1,
                            'list' => self::getMinPrice($products[$key])
                        ];
                }else{
                    $blueprinted[$key] = [
                        'status' => 0,
                        'message' => 'It doesn\'t match any products in current Budget'
                    ];
                }
            }
            #============================================
            #============================================
            $obj = [];
            if(empty($blueprinted)){
                echo 0;
                wp_die();
            }else{
                $i = 0;
                foreach($blueprinted as $com => $product){
                    $obj[$i]['status'] = $product['status'];
                    $obj[$i]['component'] = $com;
                    if($product['status']){
                        $obj[$i]['name'] = $product['list']['name'];
                        $obj[$i]['url'] = $product['list']['url'];
                        $obj[$i]['price']= $product['list']['price'];
                        $obj[$i]['brand'] = $product['list']['brand'];
                    }else{
                        $obj[$i]['message'] = $product['message'];
                    }
                    $i++;
                }
            }
            echo @json_encode($obj);
            unset($obj, $blueprinted, $products, $pcbuilder_groups_keys, $products_keys, $list, $component, $purpose, $brand, $extra, $data);
            #============================================
            #============================================
        }else{
            echo 0;
        }
        wp_die();
    }

    public static function call_pc_products() {
        $component = sanitize_text_field($_POST['component']);
        if($component == ''){
            echo 0;
            wp_die();
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
            echo 0;
            wp_die();
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