<?php
/**
 * wp website creator API
 *
 * @author Manfred Sandner <info@wp-website-creator.com>
 * @link http://wp-website-creator.com
 */


 //make sortable fields possible

 function wpwc_is_child( $theme_data ) {
     // For limitation of empty() write in var
     $parent = $theme_data->parent();
     if ( ! empty( $parent ) ) {
         return wp_get_theme();
     }
     return $theme_data->parent()->Name;
 }

add_action('admin_enqueue_scripts','wpwc_admin_scripts');

function wpwc_admin_scripts() {
    wp_enqueue_script( 'wpwc-wpwc-jquery-sortable-js', plugins_url( 'js/wpwc_jquery_sortable.min.js', __FILE__ ));
    wp_enqueue_script( 'wpwc-wpwc-jquery-ui-js', plugins_url( 'js/wpwc_jquery_ui.min.js', __FILE__ ));
}


 // Register style sheet.
 add_action( 'wp_enqueue_scripts', 'register_wp_website_creator_styles' );
 add_action( 'wp_enqueue_scripts', 'register_wp_website_creator_sortable_styles' );
 add_action( 'admin_enqueue_scripts', 'register_wp_website_creator_styles' );
 add_action( 'admin_enqueue_scripts', 'register_wp_website_creator_sortable_styles' );
 /**
  * Register style sheet.
  */
 function register_wp_website_creator_styles() {
 	wp_register_style( 'wpwc-wp-website-creator', plugins_url( 'css/wp-website-creator.css',__FILE__ ) );
 	wp_enqueue_style( 'wpwc-wp-website-creator' );
 }

 function register_wp_website_creator_sortable_styles() {
  wp_register_style( 'wpwc-wp-website-creator-sortable', plugins_url( 'css/wp-website-creator-sortable_full.css',__FILE__ ) );
 	wp_enqueue_style( 'wpwc-wp-website-creator-sortable' );
 }

 $theme_data    = wp_get_theme();

 //If jquery is not loaded and beaverbuilder is not in edit mode
 //Wenn Divi Builder nicht existiert et_get_option
 if ( ! wp_script_is( 'jquery', 'enqueued' ))
 {
      if (!isset($_GET['fl_builder']) and !isset($_GET['action']) and wpwc_is_child($theme_data)!='Divi' and wpwc_is_child($theme_data)!='Divi-child' )
        {
          add_action('wp_enqueue_scripts','wpwc_front_scripts_load');
        }
  }
//if jquery is loaded and beaverbuilder is not in edit mode
  else if ( wp_script_is( 'jquery', 'enqueued' ) and wpwc_is_child($theme_data)!='Divi' and wpwc_is_child($theme_data)!='Divi-child' )
  {
       if (!isset($_GET['fl_builder']) and !isset($_GET['action']) )
         {
           add_action('wp_enqueue_scripts','wpwc_front_scripts_upgrade');
         }
   }

   if (wp_get_theme() == 'Divi-child' or wpwc_is_child($theme_data) == 'Divi' )
     {
       add_action('wp_enqueue_scripts','wpwc_front_scripts_load_divi');
     }

//Laden wenn divi nicht existiert
   function wpwc_front_scripts_load() {
       #wp_enqueue_script( 'wpwc_jquery_insert-js', plugins_url( 'js/wpwc_jquery_insert_full.js', __FILE__ ));
       wp_enqueue_script( 'wpwc-wpwc-jquery-sortable-js', plugins_url( 'js/wpwc_jquery_sortable.min.js', __FILE__ ));
   }


   //speziell für Divi
   function wpwc_front_scripts_load_divi() {
       wp_enqueue_script( 'wpwc-wpwc-jquery-sortable-js', plugins_url( 'js/wpwc_jquery_sortable.min.js', __FILE__ ));
   }

   function wpwc_front_scripts_upgrade() {
       #wp_enqueue_script( 'wpwc_jquery_insert-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.2.0/jquery-migrate.min.js', array(), null, true);
       #wp_enqueue_script( 'wpwc-wpwc-jquery-sortable-js', plugins_url( 'js/wpwc_jquery_sortable.min.js', __FILE__ ));
   }


 //Load language files
  add_action( 'plugins_loaded', 'wp_website_creator_load_textdomain' );
   function wp_website_creator_load_textdomain() {
   load_plugin_textdomain( 'wp-website-creator', false, basename( dirname( __FILE__ ) ) . '/languages' );
 }




    class WpWcreator_Wp_Website {

    /**
     * settings sections array
     *
     * @var array
     */
    private $settings_sections = array();

    /**
     * Settings fields array
     *
     * @var array
     */
    private $settings_fields = array();

    /**
     * Singleton instance
     *
     * @var object
     */
    private static $_instance;

    public function __construct() {

    }

    public static function getInstance() {
        if ( !self::$_instance ) {
            self::$_instance = new WpWcreator_Wp_Website();
        }

        return self::$_instance;
    }

    /**
     * Set settings sections
     *
     * @param array $sections setting sections array
     */
    function set_sections( $sections ) {
        $this->settings_sections = $sections;
    }

    /**
     * Set settings fields
     *
     * @param array $fields settings fields array
     */
    function set_fields( $fields ) {
        $this->settings_fields = $fields;
    }

    /**
     * Initialize and registers the settings sections and fileds to WordPress
     *
     * Usually this should be called at `admin_init` hook.
     *
     * This function gets the initiated settings sections and fields. Then
     * registers them to WordPress and ready for use.
     */
    function admin_init() {

        //register settings sections
        foreach ($this->settings_sections as $section) {
            if ( false == get_option( $section['id'] ) ) {
                add_option( $section['id'] );
            }

            add_settings_section( $section['id'], $section['title'], '__return_false', $section['id'] );
        }

        //register settings fields
        foreach ($this->settings_fields as $section => $field) {

            foreach ($field as $option) {
                $args = array(
                    'id' => $option['name'],
                    'desc' => $option['desc'],
                    'name' => $option['label'],
                    'section' => $section,
                    'size' => isset( $option['size'] ) ? $option['size'] : null,
                    'options' => isset( $option['options'] ) ? $option['options'] : '',
                    'std' => isset( $option['default'] ) ? $option['default'] : ''
                );
                add_settings_field( $section . '[' . $option['name'] . ']', $option['label'], array($this, 'callback_' . $option['type']), $section, $section, $args );
            }
        }

        // creates our settings in the options table
        foreach ($this->settings_sections as $section) {
            register_setting( $section['id'], $section['id'] );
        }
    }

    /**
     * Displays a text field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_text( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
        $html .= sprintf( '<br><span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }

    function callback_hidden( $args ) {

      $value = current_time( 'timestamp' );
      $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

      $html = sprintf( '<input type="hidden" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
      $html .= sprintf( '<br><span class="description"> %s</span>', $args['desc'] );

      echo $html;
    }

    /**
     * Displays a checkbox for a settings field
     *
     * @param array $args settings field args
     */
    function callback_checkbox( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

        $html = sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="on"%4$s /><br>', $args['section'], $args['id'], $value, checked( $value, 'on', false ) );
        $html .= sprintf( '<label for="%1$s[%2$s]"> %3$s</label><br>', $args['section'], $args['id'], $args['desc'] );

        echo $html;
    }

    /**
     * Displays a multicheckbox a settings field
     *
     * @param array $args settings field args
     */
    function callback_multicheck( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );

        $html = '';
        foreach ($args['options'] as $key => $label) {
            $checked = isset( $value[$key] ) ? $value[$key] : '0';
            $html .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s"%4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
            $html .= sprintf( '<label for="%1$s[%2$s][%4$s]"> %3$s</label><br>', $args['section'], $args['id'], $label, $key );
        }
        $html .= sprintf( '<span class="description"> %s</label>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a multicheckbox a settings field
     *
     * @param array $args settings field args
     */
    function callback_radio( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );

        $html = '';
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
            $html .= sprintf( '<label for="%1$s[%2$s][%4$s]"> %3$s</label><br>', $args['section'], $args['id'], $label, $key );
        }
        $html .= sprintf( '<span class="description"> %s</label>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a selectbox for a settings field
     *
     * @param array $args settings field args
     */
    function callback_select( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
        }
        $html .= sprintf( '</select>' );
        $html .= sprintf( '<br><span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a textarea for a settings field
     *
     * @param array $args settings field args
     */
    function callback_textarea( $args ) {

        $value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
        $html .= sprintf( '<br><span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }


    function callback_password( $args ) {
        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $html = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value=""/>', $size, $args['section'], $args['id'], $value );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );
        echo $html;
    }

    /**
     * Get the value of a settings field
     *
     * @param string $option settings field name
     * @param string $section the section name this field belongs to
     * @param string $default default text if it's not found
     * @return string
     */
    function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }

    /**
     * Show navigations as tab
     *
     * Shows all the settings section labels as tab
     */
    function show_navigation() {
        $html = '<h2 class="nav-tab-wrapper">';

        foreach ($this->settings_sections as $tab) {
            $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
        }

        $html .= '</h2>';

        echo $html;
    }

    /**
     * Show the section settings forms
     *
     * This function displays every sections in a different form
     */
     function show_forms() {
         ?>
         <div class="metabox-holder">
             <div class="postbox" style="padding:20px;">
               <?php
               $thiswpwcmember = get_option( 'wpwc_membership',true);

               if($thiswpwcmember=='' or $thiswpwcmember=='1'){$thiswpwcmember='Free';}

               echo 'Your Membership <h1 style="color:blue;">'.$thiswpwcmember.'</h1>';?>

              <?php foreach ($this->settings_sections as $form)
                {
                $thistab = $form['id'];

                if($thistab != 'wpcr_tutorials'){?>

                  <div id="<?php echo $form['id']; ?>" class="group">
                       <div class="uk-child-width-1-2@s uk-grid-match" uk-grid>
                           <div>
                               <div class="uk-card uk-card-hover uk-card-body">
                                 <?php if($thistab != 'wpcr_thememanufacturer'){?>
                                 <form method="post" action="options.php">
                                    <?php if($thistab == 'wpcr_beaver'){?>
                                      <div class="wpwcpostalert_info"><p><?php echo __( "<b>!!NOTE!!</b><br>These settings only come to bear if you use our <br><b>Beaver Builder pro ready-made-websites</b> templates for website creation.<br>You need a <a target='_blank' href='https://www.wpbeaverbuilder.com/?fla=1133'>Beaver Builder Agency</a> license to whitelabel all these settings<br>If you use your own templates you have to enter the settings already in the templates.", "wp-website-creator" );?></p></div>
                                    <?php }?>
                                    <?php if($thistab == 'wpcr_uabb'){?>
                                      <div class="wpwcpostalert_info"><p><?php echo __( "<b>!!NOTE!!</b><br>These settings only come to bear if you use <br><b>our templates made with UABB pro</b> for website creation.<br>You need a <a target='_blank' href='https://www.ultimatebeaver.com/?bsf=157'>Ultimate Addons for Beaver Builder pro</a> license to whitelabel all these settings<br>If you use your own templates you have to enter the settings already in the templates.", "wp-website-creator" );?></p></div>
                                    <?php }?>
                                    <?php if($thistab == 'wpcr_uae'){?>
                                      <div class="wpwcpostalert_info"><p><?php echo __( "<b>!!NOTE!!</b><br>These settings only come to bear if you use <br><b>our templates made with UAE pro</b> for website creation.<br>You need a <a target='_blank' href='https://uaelementor.com/?bsf=157'>Ultimate Addons for Elementor pro</a> license to whitelabel all these settings<br>If you use your own templates you have to enter the settings already in the templates.", "wp-website-creator" );?></p></div>
                                    <?php }?>
                                    <?php if($thistab == 'wpcr_support'){?>
                                    <div class="wpwcpostalert_info"><p><?php echo __( "<b>!!NOTE!!</b><br>Each of our ready-made websites can be provided with a video tutorial button and a buy button. Here you can decide if you want it or not.", "wp-website-creator" );?></p></div>
                                    <?php }?>
                                     <?php settings_fields( $form['id'] ); ?>
                                     <?php do_settings_sections( $form['id'] );?>
                                     <br><br>

                                     <div style="padding-left: 10px">
                                         <?php submit_button(); ?>
                                     </div>
                                 </form>
                               <?php }?>
                               <?php if($thistab == 'wpcr_thememanufacturer'){?>
                                 <h2><?php echo __( "You are theme manufacturer?", "wp-website-creator" );?><br><br> <?php echo __( "COMING SOON!", "wp-website-creator" );?></h2><br><p>
                                 <?php echo __( "As a template manufacturer you have the possibility to integrate your templates into our Plugin.
                                 <br>Imagine every installed wp-website-creator plugin would contain your templates.
                                 <br>This is an enormous distribution turbo.
                                 <br>All you have to do is provide pre-built web pages.
                                 <br>These websites must be created with the free version of your theme.
                                 <br>These websites can then be automatically installed on any WHM/cPanel or plesk server worldwide using our plugin.", "wp-website-creator" );?></p>
                               <?php }?>
                               </div>
                           </div>
                           <div>
                               <div class="uk-card uk-card-default uk-card-hover uk-card-body">
                                   <?php if($thistab == 'wpcr_id'){?>
                                     <table width="100%">
                                       <tr>
                                         <td width="100%" valign="top">
                                           <div>
                                             <h2><div class="notice notice-info is-dismissible"><p><?php _e( '<b>!!PLEASE NOTE!!</b> This plugin works only if your Rest API is reachable. It does not work on a local installation. The site must be reachable online.', 'sample-text-domain' ); ?></p></div><?php echo __( "What is WP Website Creator", "wp-website-creator" );?></h2>
                                             <p><?php echo __( "With WP Website Creator, you can provide your website visitors a WordPress installation by using a simple form.<br>
                           Your visitor send the form and a WordPress website will be installed immediately.<br>
                           Your visitor (new customer) get's his website access 10 minutes after sending the form.", "wp-website-creator" );?></p>
                           <h2><?php echo __( "How to setup.", "wp-website-creator" );?></h2><br>
                           <b>1. <?php echo __( "Install one of these form plugins", "wp-website-creator" );?></b><br>
                           <a href="https://wordpress.org/plugins/wpforms-lite/">wp-forms</a><br>
                           <a href="https://wordpress.org/plugins/ninja-forms/">ninja forms</a><br>
                           <a href="https://wordpress.org/plugins/formidable/">formidable</a><br>
                           <a href="https://wordpress.org/plugins/contact-form-7/">contact form 7</a><br>
                           <a href="https://wordpress.org/plugins/caldera-forms/">caldera forms</a><br>
                           <a href="https://www.gravityforms.com/">gravity forms</a><br><br>
                           <b>2. <?php echo __( "Create a form with your installed form plugin", "wp-website-creator" );?></b><br><br>
                           <b>3. <?php echo __( "Go to the WPWCreator section and create a relation mapping to send your form data to wp-website-creator for website installation.", "wp-website-creator" );?></b>

                                              <h2><?php echo __( "Technical Description", "wp-website-creator" );?></h2>
                                              <p><?php echo __( "The content of your form will be sent via the API interface to wp-website-creator.com. Our system sends the selected design to the registered server (WHM, cPanel or plesk) and the installation process starts. Then the data of the newly created website will be sent back to your website via curl. A custom endpoint (WPWCreator->Websites) will be set up on your website. As soon as a new website has been installed and entered in your backend, an email with the access data will be sent to the new customer.", "wp-website-creator" );?></p>
                                           </div>
                                         </td>
                                       </tr>
                                       <tr>
                                         <td width="100%" valign="top">
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/IG3Rpwm4iQY?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                     </table>
                                   <?php }?>
                                   <?php if($thistab == 'wpcr_support'){?>
                                     <table width="100%">

                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Videotutorial", "wp-website-creator" );?></h2>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/UTdBW3-i9l4" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                     </table>
                                   <?php }?>
                                   <?php if($thistab == 'wpcr_themes'){?>
                                     <table width="100%">

                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Offer our ready-made websites!", "wp-website-creator" );?></h2>
                                           <p><?php echo __( "Decide which ready-made websites you want to offer your customers. If you choose templates that contain paid themes and plugins you should also enter the plugin data so that our system can replace the data during the installation.", "wp-website-creator" );?> </p>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/A6qPgk5xXSQ" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Offer your own ready-made websites!", "wp-website-creator" );?></h2>
                                           <p><?php echo __( "Watch this tutorial to see how it works", "wp-website-creator" );?></p>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/QaXRKPbMIao" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                     </table>
                                   <?php }?>
                                   <?php if($thistab == 'wpcr_thememanufacturer'){?>
                                     <table width="100%">

                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Videotutorial", "wp-website-creator" );?></h2>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/r8XP8Xu5UqI" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                     </table>
                                   <?php }?>
                                   <?php if($thistab == 'wpcr_uabb'){?>
                                     <table width="100%">

                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Videotutorial", "wp-website-creator" );?></h2>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/r8XP8Xu5UqI" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                     </table>
                                   <?php }?>
                                   <?php if($thistab == 'wpcr_uae'){?>
                                     <table width="100%">

                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Videotutorial", "wp-website-creator" );?></h2>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/r8XP8Xu5UqI" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>

                                       </tr>

                                     </table>
                                   <?php }?>
                                   <?php if($thistab == 'wpcr_beaver'){?>
                                     <table width="100%">
                                       <tr>
                                         <td width="100%" valign="top">
                                           <h2><?php echo __( "Videotutorial", "wp-website-creator" );?></h2>
                                           <div style="margin-bottom:20px;" class="responsive-video">
                                             <iframe width="100%" src="https://www.youtube.com/embed/r8XP8Xu5UqI" frameborder="0" allowfullscreen></iframe>
                                           </div>
                                         </td>
                                       </tr>
                                     </table>
                                   <?php }?>

                               </div>
                           </div>
                       </div>
                     </div>
                   <?php }//End if not videotutorials ?>

                   <?php if($thistab  == 'wpcr_tutorials'){?>

                  <div id="<?php echo $form['id']; ?>" class="group">

                    <div uk-filter="target: .js-filter">

                        <ul class="uk-subnav uk-subnav-pill">
                            <li class="uk-active" uk-filter-control><a href="#">All</a></li>
                            <li uk-filter-control=".tag-wpforms"><a href="#">WPForms</a></li>
                            <li uk-filter-control=".tag-gravity"><a href="#">Gravity</a></li>
                            <li uk-filter-control=".tag-ninja"><a href="#">Ninja</a></li>
                            <li uk-filter-control=".tag-formidable"><a href="#">Formidable</a></li>
                            <li uk-filter-control=".tag-cf7"><a href="#">CF7</a></li>
                            <li uk-filter-control=".tag-caldera"><a href="#">Caldera</a></li>
                            <li uk-filter-control=".tag-email"><a href="#">Email customization</a></li>
                            <li uk-filter-control=".tag-destination"><a href="#">Destinations</a></li>
                            <li uk-filter-control=".tag-support"><a href="#">Support</a></li>
                            <li uk-filter-control=".tag-woocommerce"><a href="#">Woocommerce</a></li>
                        </ul>

                        <ul class="js-filter uk-child-width-1-2 uk-child-width-1-3@m uk-text-center" uk-grid>
                            <li class="tag-formidable">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/34vg6qib1Ug" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-gravity">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/5Vaa7q8YNpc" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-ninja">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/21RfhBakuis" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-wpforms">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/vz0Pp9UVKMY" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-caldera">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/SggtLel1D3c" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-cf7">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/SFNDS2kFzX0" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-email">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/Auxv4UIpY3I" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-destination">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/A6qPgk5xXSQ" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-destination">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/puUXiRuF_No" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-destination">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/Nf8u_QVdMEM" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-destination">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/Q17km_vHjME" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-upgrade">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/K9l1DTzuNJk" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            <li class="tag-woocommerce">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/OQlcE6YH0J4" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                            <li class="tag-support">
                              <div class="uk-card uk-card-default uk-card-body">
                                <div style="margin-bottom:20px;" class="responsive-video">
                                  <iframe width="100%" src="https://www.youtube.com/embed/UTdBW3-i9l4" frameborder="0" allowfullscreen></iframe>
                                </div>
                              </div>
                            </li>
                        </ul>
                    </div>
                  </div>
                <?php }//End if tutorials?>
                <?php }//Foreach ?>
             </div>
         </div>
         <?php
         $this->script();
     }

     /**
      * Tabbable JavaScript codes
      *
      * This code uses localstorage for displaying active tabs
      */
     function script() {
         ?>
         <script>
             jQuery(document).ready(function($) {
                 // Switches option sections
                 $('.group').hide();
                 var activetab = '';
                 if (typeof(localStorage) != 'undefined' ) {
                     activetab = localStorage.getItem("activetab");
                 }
                 if (activetab != '' && $(activetab).length ) {
                     $(activetab).fadeIn();
                 } else {
                     $('.group:first').fadeIn();
                 }
                 $('.group .collapsed').each(function(){
                     $(this).find('input:checked').parent().parent().parent().nextAll().each(
                     function(){
                         if ($(this).hasClass('last')) {
                             $(this).removeClass('hidden');
                             return false;
                         }
                         $(this).filter('.hidden').removeClass('hidden');
                     });
                 });

                 if (activetab != '' && $(activetab + '-tab').length ) {
                     $(activetab + '-tab').addClass('nav-tab-active');
                 }
                 else {
                     $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                 }
                 $('.nav-tab-wrapper a').click(function(evt) {
                     $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                     $(this).addClass('nav-tab-active').blur();
                     var clicked_group = $(this).attr('href');
                     if (typeof(localStorage) != 'undefined' ) {
                         localStorage.setItem("activetab", $(this).attr('href'));
                     }
                     $('.group').hide();
                     $(clicked_group).fadeIn();
                     evt.preventDefault();
                 });
             });
         </script>
         <?php
     }

 }
