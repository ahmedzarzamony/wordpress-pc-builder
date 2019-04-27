<?php 

class Customfields{

    public static function handle_meta_box_places() {
        # Get the globals:
        global $post, $wp_meta_boxes;
        # Output the Special meta boxes:
        do_meta_boxes( get_current_screen(), 'specs_box', $post );
        do_meta_boxes( get_current_screen(), 'price_box', $post );

        # Remove the initial "advanced" meta boxes:
        unset($wp_meta_boxes['post']['price_box']);
        unset($wp_meta_boxes['post']['specs_box']);
    }

    # Specification meta box
    public static function addingSpecsMetaBoxes( $post_type, $post ) {
        if($post_type == 'products'){
            add_meta_box('specs-meta-box', __( 'Specification Options' ), ['Customfields', 'renderSpecsMetaBox'], 'products', 'specs_box', 'high');
        }
    }

    # Price meta box
    public static function addingPriceMetaBoxes( $post_type, $post ) {
        if($post_type == 'products'){
            add_meta_box('price-meta-box', __( 'Price Options' ), ['Customfields', 'renderPriceMetaBox'], 'products', 'price_box', 'high');
        }
    }

    public static function renderPriceMetaBox($post){
        $countries = include_once( PCBUILDER__PLUGIN_DIR . "pcbuilder.countries.php" );
        $data = get_post_meta($post->ID, 'pcbuilder_price', true);
        $pcbuilder_mprice = (float)get_post_meta($post->ID, 'pcbuilder_mprice', true);
        include_once( PCBUILDER__PLUGIN_DIR . "/html/pricebox.php" );
    }


    public static function savePostedMeta($id) {
        $post_type = get_post_type($id);
        if( ! ( wp_is_post_revision( $id) || wp_is_post_autosave( $id ) ) && $post_type == "products" ) {
            if( isset($_POST['pcbuilder_price']) ){
                $pcbuilder_price = (array)$_POST['pcbuilder_price'];
                $prices = [];
                foreach($pcbuilder_price as $price){
                    if($price['price'] == 0) continue;
                    $prices[sanitize_text_field($price['country'])] = (float)$price['price'];
                }
                delete_post_meta($id, 'pcbuilder_price');
                update_post_meta($id, 'pcbuilder_price', $prices);
            }
            if( isset($_POST['pcbuilder_mprice']) ){
                $pcbuilder_mprice = (float)$_POST['pcbuilder_mprice'];
                update_post_meta($id, 'pcbuilder_mprice', $pcbuilder_mprice);
            }
            if( isset($_POST['pcbuilder_spec']) ){
                $pcbuilder_spec = (array)$_POST['pcbuilder_spec'];
                $specs = [];
                foreach($pcbuilder_spec as $spec){
                    if($spec['title'] == '') continue;
                    $specs[sanitize_text_field($spec['title'])] = sanitize_textarea_field($spec['content']);
                }
                delete_post_meta($id, 'pcbuilder_spec');
                update_post_meta($id, 'pcbuilder_spec', $specs);
            }
        }
    }

    public static function renderSpecsMetaBox($post){
        $data = get_post_meta($post->ID, 'pcbuilder_spec', true);
        include_once( PCBUILDER__PLUGIN_DIR . "/html/specsbox.php" );
    }

    public static function showCustomFieldBeforeContent($content) {
        global $post;
        $beforecontent = '';
        if($post->post_type == 'products'){
            $pcbuilder_price = get_post_meta($post->ID, 'pcbuilder_price', true);
            $pcbuilder_price_co = 'Found No price.';
            if(!empty($pcbuilder_price)){
                $pcbuilder_price_co = '';
                foreach($pcbuilder_price as $k=>$d){
                    $pcbuilder_price_co .= '<span><strong>'.$k.': </strong>'.$d.'USD</span>';
                }
            }
            $pcbuilder_spec = get_post_meta($post->ID, 'pcbuilder_spec', true);
            $pcbuilder_spec_co = 'Found No Spcs.';
            if(!empty($pcbuilder_spec)){
                $pcbuilder_spec_co = '';
                foreach($pcbuilder_spec as $k=>$d){
                    $pcbuilder_spec_co .= '<div class="pcbuilder-cf-spec"><label>'.$k.':</label><div class="pcbuilder-cf-spec-co">'.nl2br($d).'</div></div>';
                }
            }
            $brand = '';
            if(!empty(wp_get_post_terms($post->ID, 'brands'))){
                $arr = wp_get_post_terms($post->ID, 'brands');
                $brand = $arr[0]->name;
            }
            $component = '';
            if(!empty(wp_get_post_terms($post->ID, 'components'))){
                $arr = wp_get_post_terms($post->ID, 'components');
                $component = $arr[0]->name;
            }
            $beforecontent = <<<"HTML"
            <div class="pcbuilder-cf-show">
                <div class="pcbuilder-cf-taxonomy">
                    <span><strong>Brand: </strong>$brand</span>
                    <span><strong>Component: </strong>$component</span>
                </div><!-- pcbuilder-cf-taxonomy --->
                <div class="pcbuilder-cf-price">
                    <h3>Pirce list: </h3>
                    $pcbuilder_price_co
                </div><!-- pcbuilder-cf-price --->
                <div class="pcbuilder-cf-specs">
                    <h3>Specs list: </h3>
                    $pcbuilder_spec_co
                </div><!-- pcbuilder-cf-specs--->
            </div><!-- pcbuilder-cf-show --->
HTML;
        }
        $aftercontent = '';
        $fullcontent = $beforecontent . $content . $aftercontent;
        
        return $fullcontent;
    }
    
    public static function brandsAddTaxonomyCustomFields() {
        include_once( PCBUILDER__PLUGIN_DIR . "/html/brandsbox.add.php" );
    }
    
    public static function brandsEditTaxonomyCustomFields( $term ) {
        $component_gpu = @get_term_meta($term->term_id, 'component_gpu', true);
        $component_cpu = @get_term_meta($term->term_id, 'component_cpu', true);
        include_once( PCBUILDER__PLUGIN_DIR . "/html/brandsbox.edit.php" );
    }

    public static function brandsSaveCustomFieldsForTaxonomy($term_id) {
        if (isset($_POST['component_gpu'])) {
            update_term_meta($term_id, 'component_gpu', sanitize_text_field($_POST['component_gpu']));
        }
        if (isset($_POST['component_cpu'])) {
            update_term_meta($term_id, 'component_cpu', sanitize_text_field($_POST['component_cpu']));
        }
    }
    
    public static function componentsAddTaxonomyCustomFields() {
        include_once( PCBUILDER__PLUGIN_DIR . "/html/componentsbox.add.php" );
    }
    
    public static function componentsEditTaxonomyCustomFields( $term ) {
        $component_type = @get_term_meta($term->term_id, 'component_type', true);
        include_once( PCBUILDER__PLUGIN_DIR . "/html/componentsbox.edit.php" );
    }

    public static function componentsSaveCustomFieldsForTaxonomy($term_id) {
        if (isset($_POST['component_type'])) {
            update_term_meta($term_id, 'component_type', sanitize_text_field($_POST['component_type']));
        }
    }

    public static function addColsForBrands($columns) {
        $columns['CPU'] = 'CPU';
        $columns['GPU'] = 'GPU';
        return $columns;
    }

    public static function addColsContentForBrands( $value, $column_name, $term_id ){
        $component_gpu = (int)@get_term_meta($term_id, 'component_gpu', true);
        $component_cpu = (int)@get_term_meta($term_id, 'component_cpu', true);
        if ($column_name === 'GPU') {
            $columns = ($component_gpu == 0) ? 'No' : 'Yes';
        }
        if ($column_name === 'CPU') {
            $columns = ($component_cpu == 0) ? 'No' : 'Yes';
        }
        return $columns;
    }

    public static function addColsForComponents($columns) {
        $columns['type'] = 'Type';
        return $columns;
    }

    public static function addColsContentForComponents( $value, $column_name, $term_id ){
        $component_type = @get_term_meta($term_id, 'component_type', true);
        if ($column_name === 'type') {
            $columns = ($component_type == '' || $component_type == '-1') ? '—' : strtoupper($component_type);
        }
        return $columns;
    }
    


    public static function init() {
        add_action( 'add_meta_boxes', ['Customfields', 'addingSpecsMetaBoxes'], 10, 2 );
        add_action( 'add_meta_boxes', ['Customfields', 'addingPriceMetaBoxes'], 10, 3 );
        add_action('edit_form_after_title', ['Customfields', 'handle_meta_box_places']);
        add_action('save_post', ['Customfields', 'savePostedMeta'], 10, 2);
        add_filter('the_content', ['Customfields', 'showCustomFieldBeforeContent']);

        #Connect  brand with components
        add_action( 'brands_add_form_fields', ['Customfields', 'brandsAddTaxonomyCustomFields'], 10, 2 );  
        add_action( 'brands_edit_form_fields', ['Customfields', 'brandsEditTaxonomyCustomFields'], 10, 2 ); 
        add_action( 'create_brands', ['Customfields', 'brandsSaveCustomFieldsForTaxonomy'], 10, 2 ); 
        add_action( 'edited_brands', ['Customfields', 'brandsSaveCustomFieldsForTaxonomy'], 10, 2 );
        add_action( 'manage_edit-brands_columns', ['Customfields', 'addColsForBrands'], 10 );
        add_action( 'manage_brands_custom_column', ['Customfields', 'addColsContentForBrands'], 10, 3 );

        add_action( 'components_add_form_fields', ['Customfields', 'componentsAddTaxonomyCustomFields'], 10, 2 );  
        add_action( 'components_edit_form_fields', ['Customfields', 'componentsEditTaxonomyCustomFields'], 10, 2 ); 
        add_action( 'create_components', ['Customfields', 'componentsSaveCustomFieldsForTaxonomy'], 10, 2 ); 
        add_action( 'edited_components', ['Customfields', 'componentsSaveCustomFieldsForTaxonomy'], 10, 2 );
        add_action( 'manage_edit-components_columns', ['Customfields', 'addColsForComponents'], 10 );
        add_action( 'manage_components_custom_column', ['Customfields', 'addColsContentForComponents'], 10, 3 );

    }

}





