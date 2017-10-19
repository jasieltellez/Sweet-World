<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Plugin Name: PayForm - Transbank webpay Chile Gateway for WooCommerce
 * Plugin URI: https://payform.cl
 * Description: Plugin para aceptar pagos de Webpay de Transbank en Chile usando WooCommerce mediante PayForm
 * Version: 1.1.3
 * Author: PayForm
 */

if ( 
  in_array( 
    'woocommerce/woocommerce.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
  ) 
) {

    function pf_activation_redirect( $plugin ) {
        if( $plugin == plugin_basename( __FILE__ ) ) {
            exit( wp_redirect( admin_url( 'admin.php?page=woocommerce-webpay' ) ) );
        }
    }
    add_action( 'activated_plugin', 'pf_activation_redirect' );

    function theme_options_panel(){
      add_submenu_page( 'woocommerce', 'PayForm (Webpay Transbank)', 'PayForm (Webpay Transbank)', 'manage_options', 'woocommerce-webpay', 'wps_theme_func_settings');
    }
    add_action('admin_menu', 'theme_options_panel');

    function wps_theme_func_settings(){
        $woooptions = get_option('woocommerce_payform_settings');

        if ($woooptions == false || (isset($woooptions['payform_secret']) && strlen($woooptions['payform_secret']) < 6) || !isset($woooptions['payform_secret'])) {
            $configured = false;
        } else {
            $configured = true;
        }

        if (isset($_GET['easyname']) && isset($_GET['key'])) {

            update_option( 'woocommerce_payform_settings', array(
                "enabled" => "yes",
                "title" => "Pago via Webpay",
                "description" => "Pago mediante tarjetas de crédito y débito",
                "payform_name" => $_GET['easyname'],
                "payform_secret" => $_GET['key']
            ));

            $configured = true;
        }

        ?>

        <style type="text/css">
            .firstblock {
                background-color: #1b6999;
                color: white;
                padding: 20px;
            }
            .firstblock h1 {
                color: white;
                font-weight: lighter;
                font-size: 30px;
                text-align: center;
            }


            .firstblock p.infotext{
                color: white;
                font-size: 14px;
            }

            .payform-input {
                width: 100%;
                height: 40px;
                height: 34px;
                padding: 9px 15px;
                font-size: 16px;
                line-height: 1.42857143;
                color: #555;
                background-color: #fff;
                background-image: none;
                border: 1px solid #ccc;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            }
            .payform-btn {
                padding: 8px 13px;
                border-radius: 4px;
                font-size: 18px;
                margin-top: 3px;
                background-color: #e97d68;
                border: 0px;
                color: white;
                cursor: pointer;
                text-decoration: none;
            }
            .payform-btn:hover {
                color: white;
            }

            .payform-blue-btn {
                padding: 14px 13px;
                border-radius: 4px;
                font-size: 16px;
                margin-top: 3px;
                background-color: #1b6999;
                border: 0px;
                color: white;
                text-decoration: none;
                margin-top: 10px;
                display: inline-block;
                width: 150px;
            }
            .payform-blue-btn:hover {
                color: white;
            }

            .secondblock {
                background-color: white;
                padding-top: 20px;
                padding-left: 20px;
                padding-right: 20px;
                vertical-align:top;
                text-align: center;
            }

            .secondblock .help {
                width: calc(100% - 400px);
                padding: 20px;
                vertical-align:top;
                padding-top: 30px;
                text-align: center;
                display: inline-block;
            }

            .secondblock .help h2{
                font-weight: lighter;
                font-size: 26px;
                color: #222;
            }

            @media screen and (max-width: 700px) {
               .secondblock .help {
                    width: calc(100% - 20px);
               }
            }
        </style>
        <div class="wrap">
            <div id="payform-configured" class="firstblock" style="<?php if(!$configured) echo "display: none;";?>">
                <div align="right">
                    <img src="https://payform.me/img/payform-white.png" style="height: 30px;">
                </div>
                <br>
                <br>
                <h1>
                    ¡Felicitaciones! Tu plugin se encuentra configurado
                </h1>
                <p class="infotext" align="center">
                    
                    Ya estás aceptando pagos mediante Transbank Webpay usando PayForm.<br>
                    Puedes configurar otras opciones en las <a href="admin.php?page=wc-settings&tab=checkout&section=payform" style="color: white; font-weight: bold;">opciones avanzadas.</a>

                    <br>
                    <br>
                    <small><a href="https://manage.payform.me/<?php if(isset($woooptions['payform_name'])) echo $woooptions['payform_name'];?>/#!/transactions" target="_new" class="payform-btn" type="submit">Ver mi detalle de pagos y transacciones</a></small>
                    <br>
                    <br>
                </p>

            </div>
            <div id="payform-nonconfigured" class="firstblock" style="<?php if($configured) echo "display: none;";?>">
                <div align="right">
                    <img src="https://payform.me/img/payform-white.png" style="height: 30px;">
                </div>
                <br>
                <br>
                <h1>
                    Bienvenido a PayForm
                </h1>
                <p class="infotext" align="center">
                    
                    Comenzar su integración de PayForm para aceptar <b>Transbank Webpay</b> en WooCommerce es muy fácil y no tomará mas de un minuto.<br>
                    Puede revisar nuestras tarifas vigentes en nuestra <a target="_new" href="https://payform.me/pricing" style="color: white; font-weight: bold;">página de precios</a>.

                    <br>
                    <form style="margin-top: 30px;" method="post" action="https://payform.me/plugins/config" align="center">
                        <input name="email" class="payform-input" style="max-width: 400px;" value="<?php echo get_bloginfo('admin_email');?>" placeholder="Ingrese su email">
                        <input type="hidden" name="blog_title" value="<?php echo get_bloginfo('title');?>">
                        <input type="hidden" name="blog_url" value="<?php echo get_site_url();?>">
                        <input type="hidden" id="return_url" name="return_url" value="">
                        <script type="text/javascript">document.getElementById('return_url').value = location.href;</script>
                        <button class="payform-btn" type="submit">Comenzar</button>
                        <br>
                        <small style="margin-top: 10px; display: block">Al continuar aceptas nuestros <a href="https://payform.me/tos" target="_new" style="color:white;">Términos de Servicio</a> y nuestra <a href="https://payform.me/privacy" target="_new" style="color:white;">Política de Privacidad</a></small>
                    </form>
                    <br>
                    <br>
                </p>

            </div>
            <div class="secondblock">
                
                <div class="help" align="center">
                    <h2>¿Necesitas ayuda?</h2>
                    <p style="max-width: 500px; display: inline-block;">Si tienes alguna duda acerca de nuestros productos, pasarelas de pago o deseas obtener mas información, no dudes en contactarnos.</p>
                    <br>
                    <a href="https://payform.me/cl/#call" target="_new" class="payform-blue-btn">Te llamamos <span class="dashicons dashicons-phone"></span></a>
                </div><img src="https://payform.me/img/nicolas.png" style="width: 250px;">
            </div>
        </div>
        
        <?php
    }

    function pf_cleanup_number($number) {

        $number = preg_replace("/[^0-9,.]/", "", $number);

        preg_match_all('/([0-9]+)/', $number, $num, PREG_PATTERN_ORDER);
        preg_match_all('/([^0-9]{1})/', $number, $sep, PREG_PATTERN_ORDER);
        if (count($sep[0]) == 0) {
            // no separator, integer
            return (int) $num[0][0];
        }
        elseif (count($sep[0]) == 1) {
            // one separator, look for last number column
            if (strlen($num[0][1]) == 3) {
                if (strlen($num[0][0]) <= 3) {
                    // treat as thousands seperator
                    return (int) ($num[0][0] * 1000 + $num[0][1]);
                }
                elseif (strlen($num[0][0]) > 3) {
                    // must be decimal point
                    return (float) ($num[0][0] + $num[0][1] / 1000);
                }
            }
            else {
                // must be decimal point
                return (float) ($num[0][0] + $num[0][1] / pow(10,strlen($num[0][1])));
            }
        }
        else {
            // multiple separators, check first an last
            if ($sep[0][0] == end($sep[0])) {
                // same character, only thousands separators, check well-formed nums
                $value = 0;
                foreach($num[0] AS $p => $n) {
                    if ($p == 0 && strlen($n) > 3) {
                        return -1; // malformed number, incorrect thousands grouping
                    }
                    elseif ($p > 0 && strlen($n) != 3) {
                        return -1; // malformed number, incorrect thousands grouping
                    }
                    $value += $n * pow(10, 3 * (count($num[0]) - 1 - $p));
                }
                return (int) $value;
            }
            else {
                // mixed characters, thousands separators and decimal point
                $decimal_part = array_pop($num[0]);
                $value = 0;
                foreach($num[0] AS $p => $n) {
                    if ($p == 0 && strlen($n) > 3) {
                        return -1; // malformed number, incorrect thousands grouping
                    }
                    elseif ($p > 0 && strlen($n) != 3) {
                        return -1; // malformed number, incorrect thousands grouping
                    }
                    $value += $n * pow(10, 3 * (count($num[0]) - 1 - $p));
                }
                return (float) ($value + $decimal_part / pow(10,strlen($decimal_part)));
            }
        }
    }


    function sample_admin_notice__success() {
        $woooptions = get_option('woocommerce_payform_settings');
        
        if ($woooptions == false || (isset($woooptions['payform_secret']) && strlen($woooptions['payform_secret']) < 6) || !isset($woooptions['payform_secret'])) {
        
        ?>
        <div class="notice notice-error" style="<?php
            if(isset($_GET['page']) &&  $_GET['page'] == "woocommerce-webpay") echo "display:none;";
        ?>">
            <p>El plugin de <b>PayForm para Transbank Webpay</b> aún no ha sido configurado. <a href="admin.php?page=woocommerce-webpay">Configúralo ahora</a></p>
            
        </div>
        <?php
        }
    }
    add_action('admin_notices', 'sample_admin_notice__success');

    add_action('plugins_loaded', 'woocommerce_payform_init', 0);

    function woocommerce_payform_init()
    {

        class WC_Gateway_PayForm extends WC_Payment_Gateway
        {

            public function __construct()
            {
                $this->id = 'payform';
                $this->icon = plugins_url('images/buttons/50x25.png', __FILE__);
                $this->has_fields = false;
                $this->method_title = __('PayForm [CL] para WooCommerce', 'woocommerce');
                $this->notify_url = add_query_arg('wc-api', 'wc_gateway_payform', home_url('/') . "index.php");
                $this->init_form_fields();
                $this->init_settings();
                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');
                $this->payform_name = $this->get_option('payform_name');
                $this->payform_secret = $this->get_option('payform_secret');
                
                $this->payform_error = false;
                if ($this->get_option('woocommerce_payform_receiver_valid', "true") == "false") {
                    $this->payform_error = "Debe indicar el nombre de su PayForm";
                    $this->update_option('woocommerce_payform_receiver_valid', "true");
                }
                if ($this->get_option('woocommerce_payform_secret_valid', "true") == "false") {
                    $this->payform_error = "Su Secret Key no es válida";
                    $this->update_option('woocommerce_payform_secret_valid', "true");
                }

                // Hooks
                add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
                

                add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
                /** Detecting WC version **/
                if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
                  add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
                } else {
                  add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                }

                add_action('woocommerce_api_wc_gateway_payform', array($this, 'check_ipn'));
                
            }
            


            public function admin_options()
            {
                ?>
                
                <h3><?php _e('PayForm para WooCommerce', 'woocommerce'); ?></h3>
                <p><?php _e('Utilice nuestro Plugin para WooCommerce de forma de conectar su PayForm y recibir pagos mediante Webpay en su tienda. Este plugin es exclusivo para Chile', 'woocommerce'); ?></p>


                <?php if ($this->payform_error !== false) { ?>
                <div class="inline error">
                    <p>
                        <strong><?php echo $this->payform_error; ?></strong>
                    </p>
                </div>
                <?php } ?>

                <table class="form-table">
                    <?php $this->generate_settings_html();?>
                </table>
                
            <?php
            }

            function init_form_fields()
            {
                $this->form_fields = array(
                    'enabled' => array(
                        'title' => 'Activar/Desactivar',
                        'type' => 'checkbox',
                        'label'   => __( 'Enable PayForm', 'woocommerce' ),
                        'default' => 'yes'
                    ),
                    'title' => array(
                        'title' => __('Title', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                        'default' => __('Pago via Webpay', 'woocommerce'),
                        'desc_tip' => true
                    ),
                    'description' => array(
                        'title' => __('Description', 'woocommerce'),
                        'type' => 'textarea',
                        'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'),
                        'default' => __('Pago mediante tarjetas de crédito y débito')
                    ),
                    'payform_name' => array(
                        'title' => __('Nombre de PayForm', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('Ingrese el nombre de su PayForm (Lo que va después de <i>https://payform.cl/</i>)', 'woocommerce'),
                        'default' => '',
                        'desc_tip' => false
                    ),
                    'payform_secret' => array(
                        'title' => __('Secret Key de PayForm', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('Ingrese su Secret Ket de PayForm (La puede encontrar en la sección <i>Compartir</i> del Dashboard de PayForm', 'woocommerce'),
                        'default' => '',
                        'desc_tip' => false
                    )
                );

            }

            function process_payment($order_id)
            {

                $order = new WC_Order($order_id);
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
                );
            }

            function receipt_page($order_id)
            {
                
                $order = new WC_Order($order_id);
                $order_item = $order->get_items();
                $catchid = 0;
                foreach($order_item as $item) {
                    $catchid = ($item['product_id']);
                }
                $recurrence = get_post_meta($catchid, '_payform_recurrence', true );
                if (!$recurrence) $recurrence = "none";

                $payform_name = $this->get_option('payform_name');
                $order_id = str_replace('#', '', $order->get_order_number());
                
                $amount = pf_cleanup_number($order->get_total());
                
                if ( version_compare( WOOCOMMERCE_VERSION, '2.7', '<' ) ) {
                    $currency = strtoupper($order->get_order_currency());
                } else {
                    $currency = strtoupper($order->get_currency());
                }
                
                $first_name = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_first_name : $order->get_billing_first_name(); 
                $last_name  = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_last_name : $order->get_billing_last_name(); 
                $email      = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_email : $order->get_billing_email(); 
                $payform_url_exito = $this->get_return_url($order);
                $payform_url_fracaso = str_replace('&amp;', '&', $order->get_cancel_order_url());
                $payform_url_ipn =  $this->notify_url;

                if ($currency=="CLP") {
                    
                    ?>
                        <form method="post" action="https://api.payform.me/pay">
                            <input type="hidden" name="business" value="<?php echo $payform_name;?>">
                            <input type="hidden" name="customer_email" value="<?php echo $email;?>">
                            <input type="hidden" name="customer_firstname" value="<?php echo $first_name;?>">
                            <input type="hidden" name="customer_lastname" value="<?php echo $last_name;?>">
                            <input type="hidden" name="trx_id" value="<?php echo $order_id;?>">
                            <input type="hidden" name="item_name" value="<?php echo get_bloginfo('name') . " | Orden Nº" . $order_id;?>">
                            <input type="hidden" name="amount" value="<?php echo $amount;?>">
                            <input type="hidden" name="currency_code" value="<?php echo $currency;?>">
                            <input type="hidden" name="recurrence" value="<?php echo $recurrence;?>">
                            <input type="hidden" name="return" value="<?php echo $payform_url_exito;?>">
                            <input type="hidden" name="fail_return" value="<?php echo $payform_url_fracaso;?>">
                            <input type="hidden" name="ipn" value="<?php echo $payform_url_ipn;?>">
                            <button type="submit">Pagar con WebPay</button>
                        </form>
                    <?php

                } else {
                    echo "Este medio de pago solo acepta órdenes en CLP";
                }
            }

            
            function check_ipn()
            {

                $order_id = sanitize_text_field($_POST['trx_id']);
                $event = sanitize_text_field($_POST['event']);
                $hash = sanitize_text_field($_POST['hash']);
                $amount = pf_cleanup_number(sanitize_text_field($_POST['amount']));
                $currency = strtoupper(sanitize_text_field($_POST['currency']));
                $payform_secret = $this->payform_secret;

                if (is_null($payform_secret) || $payform_secret == false || strlen($payform_secret)==0) {
                    $payform_secret = "00e608dc-5491-4103-87cd-a7d0027f03eb";
                }

                $payform_id = sanitize_text_field($_POST['id']);
                $payform_sub_id = sanitize_text_field($_POST['subscription_id']);

                $calchash = md5($payform_id . $payform_secret . $payform_sub_id . $amount . $currency);
                
                $order = new WC_Order($order_id);
                
                if ($order) {
                    
                    $order_woo = pf_cleanup_number($order->get_total());
                    if ( version_compare( WOOCOMMERCE_VERSION, '2.7', '<' ) ) {
                        $currency_woo = strtoupper($order->get_order_currency());
                    } else {
                        $currency_woo = strtoupper($order->get_currency());
                    }
                    

                    if ($order_woo == $amount && $currency_woo == $currency && $hash == $calchash) {
                        if ($event == 'approved') {
                            $order->add_order_note(__('Pago aprobado', 'woocommerce'));
                            $order->payment_complete();
                        } else {
                            $order->update_status( 'failed',  __( 'Pago rechazado', 'woocommerce' ));
                        }
                    
                    }
                }

                exit;

            }

            //

        }



        /**
         * Add the Gateway to WooCommerce
         **/
        function woocommerce_add_payform_gateway($methods)
        {
            $methods[] = 'WC_Gateway_PayForm';
            return $methods;
        }

        add_filter('woocommerce_payment_gateways', 'woocommerce_add_payform_gateway');

        add_action( 'woocommerce_product_options_general_product_data', 'wc_custom_add_custom_fields' );
        function wc_custom_add_custom_fields() {
            woocommerce_wp_select(
                array( 
                    'id'          => '_payform_recurrence', 
                    'label'       => __( 'Recurrencia de PayForm', 'woocommerce' ), 
                    'description' => __( 'Esta es la recurrencia de este producto o servicio. El carro de compras solo aceptará productos con la misma recurrencia.', 'woocommerce' ),
                    'desc_tip' => 'true',
                    'options' => array(
                        'none'   => __( 'Sin recurrencia', 'woocommerce' ),
                        '1 month'   => __( 'Mensual', 'woocommerce' ),
                        '3 months' => __( 'Cada 3 meses', 'woocommerce' ),
                        '6 months' => __( 'Cada 6 meses', 'woocommerce' ),
                        '1 year' => __( 'Cada año', 'woocommerce' ),
                        '2 years' => __( 'Cada 2 años', 'woocommerce' )
                    )
                )
            );
        }

        add_action( 'woocommerce_process_product_meta', 'payform_custom_save_custom_fields' );
        function payform_custom_save_custom_fields( $post_id ) {
            if ( ! empty( $_POST['_payform_recurrence'] ) ) {
                
                $valid_values = array(
                   'none',
                   '1 month',
                   '3 months',
                   '6 months',
                   '1 year',
                   '2 years'
                );

                $recurrence_value = sanitize_text_field( $_POST['_payform_recurrence'] );
                if( in_array( $recurrence_value, $valid_values ) ) {
                    update_post_meta( $post_id, '_payform_recurrence', $recurrence_value );
                }
            }
        }

        function so_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
            
            global $woocommerce;
            
            $items = $woocommerce->cart->get_cart();

            if (count($items)==0) return $passed;

            $new_recurrence = get_post_meta($product_id, '_payform_recurrence', true );
            $has_removed = false;
            foreach($items as $item => $values) { 
                $old_recurrence = get_post_meta($values['product_id'], '_payform_recurrence', true );
                if (!$old_recurrence) $old_recurrence = "none";
                if ($old_recurrence !== $new_recurrence) {
                    $woocommerce->cart->remove_cart_item($item);
                    $has_removed = true;
                }
            }

            if ($has_removed) wc_add_notice( __( 'Hemos actualizado su carro de compras debido a que algunos productos no son compatibles entre si', 'textdomain' ), 'error' );
            return $passed;

        }
        add_filter( 'woocommerce_add_to_cart_validation', 'so_validate_add_cart_item', 10, 5 );


    }

} else {

    function sample_admin_notice__success() {
        ?>
        <div class="notice notice-error">
            <p>El plugin de <b>PayForm para Transbank Webpay</b> requiere de WooCommerce para funcionar. <a href="plugins.php">Activar WooCommerce</a></p>
            
        </div>
        <?php
    }
    add_action('admin_notices', 'sample_admin_notice__success');
}
