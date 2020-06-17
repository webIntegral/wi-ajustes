<?php

/**
 * Plugin Name: Wi Ajustes
 * Description: Plugin en blanco para hacer ajustes en Wordpress y WooCommerce.
 * Version: 1.0.0
 * Author: Mario <mario@webintegral.com.co>
 * Plugin URI: https://webintegral.com.co/blog/como-crear-un-plugin-propio-para-hacer-todos-los-ajustes-de-wordpress-y-woocommercer/
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Wi Inventario Class
 */
class Wi_Ajustes
{
    
    /**
     * Constructor
     */
    public function __construct()
    {
        define( 'WI_VERSION', '1.0.0' );
        define( 'WI_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
        define( 'WI_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
        define( 'WI_MAIN_FILE', __FILE__ );
        define( 'WI_ABSPATH', dirname( __FILE__ ) . '/' );
        
        add_action( 'plugins_loaded', array( $this, 'includes' ) );
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        
        if ( is_admin() ) {
            $this->admin_includes();
        }
    }
    
    /**
     * Cargar includes
     */
    public function includes()
    {
        // Incluir functions.php
        include_once( WI_ABSPATH . 'fuctions.php' );
    }
    
    /**
     * Init self
     */
    public function init()
    {
        // Cargar Templates
        add_filter( 'template_include', array( $this, 'include_template' ), 11 );
        add_filter( 'wc_get_template', array( $this, 'get_template' ), 11, 5 );
    }
    
    public function admin_includes()
    {

    }

    /**
     * Forzar a que la plataforma busque los templates primero en este plugin
     * Este método funciona para los templates de nivel superior (single.php, page.php, etc).
     * No funciona para template parts.
     *
     * Relevant trac ticket; https://core.trac.wordpress.org/ticket/13239
     *
     * @param  string $template template string.
     * @return string $template new template string.
     */
    public function include_template( $template ) {
        if ( file_exists( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' . basename( $template ) ) ) {
        $template = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' . basename( $template );
        }
        return $template;
    }
        
    /**
     * Forzar WooCommerce para que busque sus templates primero en este plugin.
     * 
     * Por ejemplo, si se quiere modificar el template woocommerce/templates/cart/cart.php,
     * agregar el nuevo template en <plugindir>/templates/woocommerce/cart/cart.php
     *
     * @param string $located is the currently located template, if any was found so far.
     * @param string $template_name is the name of the template (ex: cart/cart.php).
     * @return string $located is the newly located template if one was found, otherwise
     *                         it is the previously found template.
     */
    public function get_template( $located, $template_name, $args, $template_path, $default_path ) {
        $plugin_template_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/woocommerce/' . $template_name;
        
        if ( file_exists( $plugin_template_path ) ) {
            $located = $plugin_template_path;
        }
        return $located;
    }

  /**
   * Cargar archivo assets/css/styles.css
   */
  public function add_scripts_and_styles()
  {
    wp_register_style( 'wiAjustesStyles', WI_PLUGIN_URL .  'style.css', null, WI_VERSION );
    wp_enqueue_style( 'wiAjustesStyles' );

    wp_enqueue_script('wiAjustesStylesJs', WI_PLUGIN_URL . 'script.js' , array('jquery'));
  }
}

$GLOBALS['wi_ajustes'] = new Wi_Ajustes();