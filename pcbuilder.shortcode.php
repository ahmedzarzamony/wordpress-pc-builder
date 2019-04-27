<?php 

class Shortcode {
    public static function render_table_shortcode( $atts ) {
        //extract( shortcode_atts( ['numbers' => '5'], $atts ) );
        ob_start();
        $components = get_terms(['taxonomy'   => 'components', 'hide_empty' => false]);
        $components = array_map(function($com){ return $com->name; }, $components);
        $front_css = plugins_url('assets/print.css',__FILE__ );
        include_once( PCBUILDER__PLUGIN_DIR . "/html/shortcode.table.php" );
        return ob_get_clean();
    }

    public static function add_buttons( $plugin_array ) {
        $plugin_array['wptuts'] = plugins_url('assets/mce-buttons.js',__FILE__ );
        return $plugin_array;
    }

    public static function register_buttons( $buttons ) {
        array_push( $buttons, 'pcbuilder_table' ); 
        return $buttons;
    }

    
    function handle_ajax_request() {
        $name	= isset($_POST['name'])?trim($_POST['name']):"";
        $response	= array();
        $response['message']	= "Successfull Request";
        echo json_encode($response);
        exit;
    }

    public static function init( $buttons ) {
        add_shortcode( 'PCBUILDER-TABLE', [ 'Shortcode' , 'render_table_shortcode'] );
        add_action( 'wp_ajax_my_ajax_request', 'handle_ajax_request' );
        add_action( 'wp_ajax_nopriv_my_ajax_request', 'handle_ajax_request' );
        add_filter( "mce_external_plugins", [ 'Shortcode' , 'add_buttons'] );
        add_filter( 'mce_buttons', [ 'Shortcode' , 'register_buttons'] );
    }
}



