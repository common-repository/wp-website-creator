<?php

/**
 * Plugin Name: WP Website Creator
 * Plugin URI: https://www.wp-website-creator.com
 * Description: Install websites with a simple email form. WPForm, Ninja forms, gravity forms, formidable, caldera forms or contact form 7 are supported.
 * Author: <a href="https://www.wp-website-creator.com">WP website creator</a>
 * Version: 4.0
 */


 require_once dirname( __FILE__ ) . '/class.settings-api.php';


#require_once(ABSPATH . 'wp-admin/includes/screen.php');
//include save fields when save post action
$wpwc_post_save_file = dirname( __FILE__ ) . '/includes/wpwc_register_posttypes.php';
include($wpwc_post_save_file);
$wpwc_post_save_file = dirname( __FILE__ ) . '/includes/wpwc_other_functions.php';
include($wpwc_post_save_file);
$wpwc_post_save_file = dirname( __FILE__ ) . '/includes/wpwc_post_save.php';
include($wpwc_post_save_file);
$wpwc_metabox_content_functions_file = dirname( __FILE__ ) . '/includes/wpwc_metabox_content_functions.php';
include($wpwc_metabox_content_functions_file);
$wpwc_send_website_data_file = dirname( __FILE__ ) . '/includes/wpwc_send_website_data.php';
include($wpwc_send_website_data_file);
$wpwc_calls_file = dirname( __FILE__ ) . '/includes/wpwc_calls.php';
include($wpwc_calls_file);
$wpwc_save_themes_to_mapper_header_file = dirname( __FILE__ ) . '/includes/wpwc_save_themes_to_mapper_header.php';
include($wpwc_save_themes_to_mapper_header_file);
$wpwc_metaboxes_file = dirname( __FILE__ ) . '/includes/wpwc_metaboxes.php';
include($wpwc_metaboxes_file);
$wpwc_save_options_file = dirname( __FILE__ ) . '/includes/wpwc_save_options.php';
include($wpwc_save_options_file);


//before options are saved the first time
if(!get_option( 'wpcr_themes'))
{
global $current_user;
global $wpdb;
wpwc_get_themes_for_options();
wpwc_website_verification(get_site_url());
#$wpcr_themes = get_option('wpcr_themes');
$wpcr_themes['wpcr_astra_free_uabb_free']= 'on';
$wpcr_themes['wpcr_astra_free_uabb_pro']= 'on';
$wpcr_themes['wpcr_astra_pro_uabb_pro']= 'on';
$wpcr_themes['wpcr_beaver_pro_uabb_pro']= 'on';
$wpcr_themes['wpcr_astra_free_uae_free']= 'on';
$wpcr_themes['wpcr_astra_free_uae_pro']= 'on';
$wpcr_themes['wpcr_astra_pro_uae_pro']= 'on';
update_option('wpcr_themes', $wpcr_themes);

#$wpcr_ids = get_option('wpcr_id');
$wpcr_ids['wpcr_main_wordpress_admin_email'] = get_bloginfo( 'admin_email' );
update_option('wpcr_id', $wpcr_ids);


//Create first email template
$email_content='
<h3 style="text-align: center;">Congratulations on creating your new website</h3>
<p style="text-align: center;">
This are your credential\'s for #website_domain#</p>
<p style="text-align: center;">Login URL: #website_login_domain#
Admin Username: #website_username#
Admin Password: #website_password#</p>
<p style="text-align: center;">To edit your website professionally you can watch our video tutorials here
#support_videotutorials#</p>';

$email_content_admin='
---------------

You can login with the following credentials
Username: #website_admin_username#
Password: #website_admin_password#
';

$emailtemplate    = array(
  'post_title' => 'First email template',
  'post_status' => 'publish',
  'post_type' => 'wpwc_email',
  'post_content' => $email_content,
  'post_author' => $current_user->ID
);
$new_post_id = wp_insert_post($emailtemplate);
#update_option('wpcr_emailtemplate', $new_post_id);

update_post_meta($new_post_id,'wpwc_sender_name',get_bloginfo( 'name' ));
update_post_meta($new_post_id,'wpwc_sender_email',get_bloginfo( 'admin_email' ));
update_post_meta($new_post_id,'wpwc_email_subject','Your new website');
update_post_meta($new_post_id,'wpwc_admin_info',$email_content_admin);


}



###Display wpforms
####

// Add settings link on plugin page
function wp_website_creator_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=wp_website_creator_settings">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wp_website_creator_settings_link' );


/**
 * WP Website Creator settings API
 *
 * @author Manfred Sandner
 */
if( !class_exists( "WpWcreator_Wp_Website_Creator" ) ) {
  class WpWcreator_Wp_Website_Creator
  {

    private $settings_api;

    function __construct() {
        $this->settings_api = WpWcreator_Wp_Website::getInstance();

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu()
    {
        add_options_page( 'WP Website Creator', 'WP Website Creator', 'manage_options', 'wp_website_creator_settings', array($this, 'plugin_page') );
    }

    function get_settings_sections()
    {

      $options_wpcr_themes = get_option('wpcr_themes');
      if($options_wpcr_themes)
      {
        foreach($options_wpcr_themes as $key => $val)
        {
          if($key=='wpcr_astra_free_uabb_free'){$wpcr_astra_free_uabb_free = $val;}
          if($key=='wpcr_astra_free_uabb_pro'){$wpcr_astra_free_uabb_pro = $val;}
          if($key=='wpcr_astra_pro_uabb_pro'){$wpcr_astra_pro_uabb_pro = $val;}
          if($key=='wpcr_beaver_pro_uabb_pro'){$wpcr_beaver_pro_uabb_pro = $val;}
          if($key=='wpcr_astra_free_uae_free'){$wpcr_astra_free_uae_free = $val;}
          if($key=='wpcr_astra_pro_uae_pro'){$wpcr_astra_pro_uae_pro = $val;}

        }
      }

        $sections = array(
            array(
                'id' => 'wpcr_id',
                'title' => __( 'WP website creator settings', 'wp-website-creator' )
            )
        );

        //Support links only replaced in websites created by wpwc
        if($wpcr_astra_free_uabb_free == 'on' or $wpcr_beaver_pro_uabb_pro == 'on' or $wpcr_beaver_pro_uabb_pro == 'on' or $wpcr_astra_free_uae_free == 'on' or $wpcr_astra_pro_uae_pro == 'on')
        {
        $array_wpcr_support = array(
          'id' => 'wpcr_support',
          'title' => __( 'Customer support settings', 'wp-website-creator' )
        );
        array_push($sections,$array_wpcr_support);
        }

          if($wpcr_astra_pro_uae_pro == 'on')
          {
            $array_wpcr_uae = array(
              'id' => 'wpcr_uae',
              'title' => __( 'Ultimate Elementor Add Ons settings', 'wp-website-creator' )
            );
            array_push($sections,$array_wpcr_uae);
          }

          if($wpcr_beaver_pro_uabb_pro == 'on')
          {
            $array_wpcr_beaver = array(
              'id' => 'wpcr_beaver',
              'title' => __( 'Beaver Builder settings', 'wp-website-creator' )
            );
            array_push($sections,$array_wpcr_beaver);
          }

          if($wpcr_astra_pro_uabb_pro == 'on' or $wpcr_beaver_pro_uabb_pro == 'on' or $wpcr_astra_free_uabb_pro == 'on')
          {
            $array_wpcr_uabb = array(
              'id' => 'wpcr_uabb',
              'title' => __( 'Ultimate Beaver Builder Add Ons settings', 'wp-website-creator' )
            );
            array_push($sections,$array_wpcr_uabb);
          }



            $array_wpcr_templates = array(
              'id' => 'wpcr_themes',
              'title' => __( 'Offer ready-made websites', 'wp-website-creator' )
            );
            array_push($sections,$array_wpcr_templates);

            $array_wpcr_theme_manufacturer = array(
              'id' => 'wpcr_thememanufacturer',
              'title' => __( 'Theme manufacturer', 'wp-website-creator' )
            );
            array_push($sections,$array_wpcr_theme_manufacturer);

            $array_wpcr_tutorials = array(
              'id' => 'wpcr_tutorials',
              'title' => __( 'Tutorials', 'wp-website-creator' )
            );
            array_push($sections,$array_wpcr_tutorials);




        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields()
    {
        $settings_fields = array(
            'wpcr_id' => array(

              array(
                  'name' => 'wpcr_preferred_software',
                  'options' => array('cPanel'=>'cPanel','Plesk'=>'Plesk'),
                  'label' => __( 'Your preferred server software', 'wp-website-creator' ),
                  'desc' => __( 'Please choose on which server you want us to install your websites if you choose "Installation in the wpwc cloud".', 'wp-website-creator' ),
                  'type' => 'select'
                ),

                array(
                    'name' => 'wpcr_id',
                    'label' => __( 'Your client-id', 'wp-website-creator' ),
                    'desc' => __( 'Please sign up at <a target="_blank" href="https://wp-website-creator.com">wp-website-creator.com</a> to get an ID', 'wp-website-creator' ),
                    'type' => 'text'
                  ),

                array(
                    'name' => 'wpcr_password',
                    'label' => __( 'Your client-secret-key', 'wp-website-creator' ),
                    'desc' => __( 'Please sign up at <a target="_blank" href="https://wp-website-creator.com">wp-website-creator.com</a> to get an key', 'wp-website-creator' ),
                    'type' => 'text'
                  ),

                  array(
                      'name' => 'wpcr_username',
                      'label' => __( 'Your username at <br> wp-website-creator.com', 'wp-website-creator' ),
                      'desc' => __( 'Please sign up at <a target="_blank" href="https://wp-website-creator.com">wp-website-creator.com</a> to get an username', 'wp-website-creator' ),
                      'type' => 'text'
                    ),

                    array(
                        'name' => 'wpcr_main_wordpress_admin_email',
                        'label' => __( 'Master WordPress admin email', 'wp-website-creator' ),
                        'desc' => __( 'An admin account will be created for you on every page that is installed trough your forms. So you can edit the website and support your customers. Which email address should be used for these admin accounts?', 'wp-website-creator' ),
                        'type' => 'text'
                      )
                      ,

                      array(
                          'name' => 'wpcr_chooserlanguage',
                          'label' => __( 'Master website language', 'wp-website-creator' ),
                          'options' => array('en_EN'=>'English','ru_RU'=>'Русский','de_DE'=>'Deutsch','es_ES'=>'Español','fr_FR'=>'Français','ja'=>'Japanese','it_IT'=>'Italiano','nl_NL'=>'Nederlands','pl_PL'=>'Polish','pt_PT'=>'Português','sv_SE'=>'Swedish','tr_TR'=>'Turkish','zh_CN'=>'Chinese','cs_CZ'=>'Czech','he_IL'=>'Hebrew','hi'=>'Indian','in'=>'Indonesian','el'=>'Greek','ar'=>'Arabisch','af'=>'Afrikaans','ko_KR'=>'Korean'),
                          'desc' => __( 'You can specify in each form in which language the web pages that are installed via this form should be installed. If you don\'t do this you should enter a main language here', 'wp-website-creator' ),
                          'type' => 'select'
                        )
                        ,

                        array(
                            'name' => 'wpcr_poweredby',
                            'label' => __( 'Your "Powered by" link', 'wp-website-creator' ),
                            'desc' => __( 'Add a powered by link to the footer on each page. Note! In the free version a powered by link from wp-website-creator will be added.', 'wp-website-creator' ),
                            'type' => 'text'
                          )
                          ,

                          array(
                              'name' => 'wpcr_googlemapapi',
                              'label' => __( 'Your google map API', 'wp-website-creator' ),
                              'desc' => __( 'In order for the maps of your installed websites to work immediately after installation, please insert your Google API key here. You can get an API key for a complete server or a wildecard key for a domain from Google.', 'wp-website-creator' ),
                              'type' => 'text'
                            ),

                            array(
                                'name' => 'wpcr_google_capcha_2',
                                'label' => __( 'Your google capcha API site key', 'wp-website-creator' ),
                                'desc' => __( 'To make forms containing a capcha work immediately after installation, please insert your Google capcha site key and your secret key here. You can get the key\'s for a complete server from Google or a wildecard key for a domain.', 'wp-website-creator' ),
                                'type' => 'text'
                              ),

                              array(
                                  'name' => 'wpcr_google_capcha_3',
                                  'label' => __( 'Your google capcha API secret key', 'wp-website-creator' ),
                                  'desc' => __( 'To make forms containing a capcha work immediately after installation, please insert your Google capcha site key and your secret key here. You can get the key\' for a complete server from Google or a wildecard key for a domain.', 'wp-website-creator' ),
                                  'type' => 'text'
                                )
                          ,

                          array(
                              'name' => 'wpcr_newid',
                              'label' => '',
                              'desc' => '',
                              'type' => 'hidden'
                            )
              ),
              'wpcr_beaver' => array(
                  array(
                      'name' => 'wpcr_bblicence',
                      'label' => __( 'Your Beaver Builder license', 'wp-website-creator' ),
                      'desc' => __( 'This license will be added to all beaver builder templates after they have been installed.', 'wp-website-creator' ),
                      'type' => 'text'
                    ),

                  array(
                      'name' => 'wpcr_bb_plugin_name',
                      'label' => __( 'Editor name in your whitelable installation', 'wp-website-creator' ),
                      'desc' => __( 'Please enter a editor name', 'wp-website-creator' ),
                      'type' => 'text'
                    ),

                  array(
                      'name' => 'wpcr_bb_icon_url',
                      'label' => __( 'URL to your editor icon', 'wp-website-creator' ),
                      'desc' => __( 'Enter the URL to your brand or icon', 'wp-website-creator' ),
                      'type' => 'text'
                    ),
                  array(
                        'name' => 'wpcr_bb_theme_description',
                        'label' => __( 'Beaver builder theme description', 'wp-website-creator' ),
                        'desc' => __( 'Enter a description for your whitelabel theme', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                  array(
                        'name' => 'wpcr_bb_theme_company',
                        'label' => __( 'The company name shown on theme site', 'wp-website-creator' ),
                        'desc' => __( 'Enter your company name', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                  array(
                        'name' => 'wpcr_bb_theme_company_url',
                        'label' => __( 'URL to your company', 'wp-website-creator' ),
                        'desc' => __( 'Enter the company URL that is shown on theme site', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                    array(
                        'name' => 'wpcr_bb_theme_screenshot',
                        'label' => __( 'URL to a screenshot of your theme', 'wp-website-creator' ),
                        'desc' => __( 'This image will be shown on the new installed website Appaerance->Theme page', 'wp-website-creator' ),
                        'type' => 'text'
                      ),

                    array(
                        'name' => 'wpcr_bb_icon_url',
                        'label' => __( 'URL to your editor icon', 'wp-website-creator' ),
                        'desc' => __( 'Enter the URL to your brand or icon', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                    array(
                          'name' => 'wpcr_bb_theme_description',
                          'label' => __( 'Beaver builder theme description', 'wp-website-creator' ),
                          'desc' => __( 'Enter a description for your whitelabel theme', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                    array(
                          'name' => 'wpcr_bb_theme_company',
                          'label' => __( 'The company name shown on theme site', 'wp-website-creator' ),
                          'desc' => __( 'Enter your company name', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                    array(
                          'name' => 'wpcr_bb_theme_company_url',
                          'label' => __( 'URL to your company', 'wp-website-creator' ),
                          'desc' => __( 'Enter the company URL that is shown on theme site', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                    array(
                          'name' => 'wpcr_bb_help_button',
                          'label' => __( 'Beaver Builder help button', 'wp-website-creator' ),
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'desc' => __( 'Should the Beaver Builder help button be displayed?', 'wp-website-creator' ),
                          'type' => 'select'
                        ),
                    array(
                          'name' => 'wpcr_bb_help_tour',
                          'label' => __( 'Beaver Builder Help Tour', 'wp-website-creator' ),
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'desc' => __( 'Should the Beaver Builder help tour be available?', 'wp-website-creator' ),
                          'type' => 'select'
                        ),
                    array(
                          'name' => 'wpcr_bb_help_video',
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'label' => __( 'Beaver Builder Video tutorial ', 'wp-website-creator' ),
                          'desc' => __( 'Want to view a Beaver Builder video tutorial? Note! You can also specify your own URL.', 'wp-website-creator' ),
                          'type' => 'select'
                        ),
                    array(
                          'name' => 'wpcr_bb_video_url',
                          'label' => __( 'The URL to your Beaver Builder tutorial', 'wp-website-creator' ),
                          'desc' => __( 'If you do not enter a video URL but want to display the video, the standard Beaver Builder overview tutorial will be displayed.', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                    array(
                          'name' => 'wpcr_bb_knowledgebase',
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'label' => __( 'Knowledgebase', 'wp-website-creator' ),
                          'desc' => __( 'Show a knowledgebase link inside Beaver Builder editor?', 'wp-website-creator' ),
                          'type' => 'select'
                        ),
                    array(
                          'name' => 'wpcr_bb_knowledgebase_url',
                          'label' => __( 'The knowledgebase URL', 'wp-website-creator' ),
                          'desc' => __( 'Enter a URL to the knowledgebase. If you want to display the knowledgebase but don\'t enter a link here, the Beaver Builder knowledgebase URL will be used.', 'wp-website-creator' ),
                          'type' => 'text'
                        )
                        ,
                    array(
                          'name' => 'wpcr_bb_support',
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'label' => __( 'Support link', 'wp-website-creator' ),
                          'desc' => __( 'Show a support link inside Beaver Builder editor?', 'wp-website-creator' ),
                          'type' => 'select'
                        )
                        ,
                    array(
                          'name' => 'wpcr_bb_support_url',
                          'label' => __( 'The support URL', 'wp-website-creator' ),
                          'desc' => __( 'Enter a URL to the support page.', 'wp-website-creator' ),
                          'type' => 'text'
                        )
                ),
                'wpcr_uae' => array(
                  array(
                      'name' => 'wpcr_uaelicence',
                      'label' => __( 'Your UAE license', 'wp-website-creator' ),
                      'desc' => __( 'This license will be added to all elementor templates after they have been installed.', 'wp-website-creator' ),
                      'type' => 'text'
                    ),
                    array(
                        'name' => 'wpcr_uae_plugin_name',
                        'label' => __( 'Your UAE name', 'wp-website-creator' ),
                        'desc' => __( 'The name for Ultimate Add-Ons for Elementor in your Whitelabele version.', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                      array(
                          'name' => 'wpcr_uae_short_name',
                          'label' => __( 'Your UAE short name', 'wp-website-creator' ),
                          'desc' => __( 'The plugin short name shown in some places on WP backend', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                        array(
                            'name' => 'wpcr_uae_description',
                            'label' => __( 'Your UAE description', 'wp-website-creator' ),
                            'desc' => __( 'The UAE plugin description', 'wp-website-creator' ),
                            'type' => 'text'
                          ),
                        array(
                        'name' => 'wpcr_uae_author',
                        'label' => __( 'Author name', 'wp-website-creator' ),
                        'desc' => __( 'Enter the author name shown as UAE author', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                      array(
                          'name' => 'wpcr_uae_author_url',
                          'label' => __( 'Author URL', 'wp-website-creator' ),
                          'desc' => __( 'The url to the UAE author page', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                      array(
                            'name' => 'wpcr_uae_replace_logo',
                            'options' => array('yes'=>'Yes','no'=>'No'),
                            'label' => __( 'Replace logo', 'wp-website-creator' ),
                            'desc' => __( 'Replace the UAE logo with the plugin name', 'wp-website-creator' ),
                            'type' => 'select'
                          ),
                      array(
                            'name' => 'wpcr_uae_display_knowledgebase',
                            'options' => array('yes'=>'Yes','no'=>'No'),
                            'label'=> __( 'Display the knowledgebase box', 'wp-website-creator' ),
                            'desc' => __( 'Should the knowledge base be displayed?', 'wp-website-creator' ),
                            'type' => 'select'
                            ),
                      array(
                            'name' => 'wpcr_uae_knowledgebase_url',
                            'label'=> __( 'Knowledgebase URL', 'wp-website-creator' ),
                            'desc' => __( 'The knowledgebase url. If you want to display the knowledgebase but don\'t enter a link here, the UAE knowledgebase URL will be used.', 'wp-website-creator' ),
                            'type' => 'text'
                          ),
                      array(
                            'name' => 'wpcr_uae_display_supportbox',
                            'options' => array('yes'=>'Yes','no'=>'No'),
                            'label'=> __( 'Display the support box', 'wp-website-creator' ),
                            'desc' => __( 'Should the support box be displayed?', 'wp-website-creator' ),
                            'type' => 'select'
                            ),
                      array(
                            'name' => 'wpcr_uae_support_url',
                            'label'=> __( 'Support URL', 'wp-website-creator' ),
                            'desc' => __( 'The support url. If you want to display the support url but don\'t enter a link here, the UAE support URL will be used.', 'wp-website-creator' ),
                            'type' => 'text'
                          ),
              ),
              'wpcr_uabb' => array(
                  array(
                      'name' => 'wpcr_uabb_licence',
                      'label' => __( 'Your UABB license', 'wp-website-creator' ),
                      'desc' => __( 'Enter your ultimate add ons license', 'wp-website-creator' ),
                      'type' => 'text'
                    ),
                    array(
                        'name' => 'wpcr_uabb_plugin_name',
                        'label' => __( 'Your UABB plugin name', 'wp-website-creator' ),
                        'desc' => __( 'Enter the plugin name shown in some places on WP backend', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                      array(
                          'name' => 'wpcr_uabb_short_name',
                          'label' => __( 'Your UABB short name', 'wp-website-creator' ),
                          'desc' => __( 'Enter your UABB short name', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                        array(
                            'name' => 'wpcr_uabb_description',
                            'label' => __( 'Your UABB description', 'wp-website-creator' ),
                            'desc' => __( 'Enter your UABB description', 'wp-website-creator' ),
                            'type' => 'text'
                          ),
                      array(
                      'name' => 'wpcr_uabb_author_name',
                      'label' => __( 'Author name', 'wp-website-creator' ),
                      'desc' => __( 'Enter the author name shown as UABB author', 'wp-website-creator' ),
                      'type' => 'text'
                    ),
                    array(
                        'name' => 'wpcr_uabb_author_url',
                        'label' => __( 'Author URL', 'wp-website-creator' ),
                        'desc' => __( 'The url to the UABB author page', 'wp-website-creator' ),
                        'type' => 'text'
                      ),
                    array(
                          'name' => 'wpcr_uabb_plugin_icon_url',
                          'label' => __( 'Plugin Icon URL', 'wp-website-creator' ),
                          'desc' => __( 'Enter the URL to your icon/logo to replace the UABB icon', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                    array(
                          'name' => 'wpcr_uabb_knowledge',
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'label'=> __( 'Display the knowledgebase tab', 'wp-website-creator' ),
                          'desc' => __( 'Enable this option to display Knowledge Base link in Help tab.', 'wp-website-creator' ),
                          'type' => 'select'
                          ),
                    array(
                          'name' => 'wpcr_uabb_knowledge_url',
                          'label'=> __( 'Knowledgebase URL', 'wp-website-creator' ),
                          'desc' => __( 'The knowledgebase url. If you want to display the knowledgebase but don\'t enter a link here, the UABB knowledgebase URL will be used.', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
                    array(
                          'name' => 'wpcr_uabb_display_support_url',
                          'options' => array('yes'=>'Yes','no'=>'No'),
                          'label'=> __( 'Display the support tab', 'wp-website-creator' ),
                          'desc' => __( 'Enable this option to display support link in Help tab.', 'wp-website-creator' ),
                          'type' => 'select'
                          ),
                    array(
                          'name' => 'wpcr_uabb_support_url',
                          'label'=> __( 'Support URL', 'wp-website-creator' ),
                          'desc' => __( 'The support url. If you want to display the support url but don\'t enter a link here, the UABB support URL will be used.', 'wp-website-creator' ),
                          'type' => 'text'
                        ),
            )

              ,'wpcr_support' => array(
              array(
                'name' => 'wpcr_show_video_tutorial',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Show video tutorials button', 'wp-website-creator' ),
                'desc' => __( 'Show a video tutorials button in the top bar of every installed website', 'wp-website-creator' ),
                'type' => 'select'
              ),
              array(
                'name' => 'wpcr_video_tutorials_button',
                'label'=> __( 'Video button text', 'wp-website-creator' ),
                'desc' => __( 'A text string for the video tutorials button', 'wp-website-creator' ),
                'type' => 'text'
              ),
              array(
                'name' => 'wpcr_video_tutorials_url',
                'label'=> __( 'Video tutorials URL', 'wp-website-creator' ),
                'desc' => __( 'The URL to your video tutorials page', 'wp-website-creator' ),
                'type' => 'text'
              ),
              array(
                'name' => 'wpcr_sale_button_show',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Show sale button', 'wp-website-creator' ),
                'desc' => __( 'Show a sale button in the top bar of every installed website', 'wp-website-creator' ),
                'type' => 'select'
              ),
              array(
                'name' => 'wpcr_sale_button_text',
                'label'=> __( 'Sale button text', 'wp-website-creator' ),
                'desc' => __( 'A text string for the sale button', 'wp-website-creator' ),
                'type' => 'text'
              ),
              array(
                'name' => 'wpcr_sale_page_url',
                'label'=> __( 'Sale page URL', 'wp-website-creator' ),
                'desc' => __( 'The URL to your sale page', 'wp-website-creator' ),
                'type' => 'text'
              ),
            ),'wpcr_themes' => array(
              array(
                'name' => 'wpcr_yourthemes',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'My own ready-made websites', 'wp-website-creator' ),
                'desc' => __( 'You can promote your own ready-made websites. You have to create websites and upload them in your backend to wp-website-creator.<br>Watch the tutorial for more information', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_astra_free_uabb_free',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Astra free theme & UABB free', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with Astra free theme, Beaver Builder Editor Standard (free) version and Ultimate Add Ons for Beaver Builder Free Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_astra_free_uabb_pro',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Astra free & UABB pro', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with Astra Free theme, Beaver Builder Editor Standard (free) version and Ultimate Add Ons for Beaver Builder Pro Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_astra_pro_uabb_pro',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Astra pro & UABB pro', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with Astra Pro theme, Beaver Builder Editor Standard (free) version and Ultimate Add Ons for Beaver Builder Pro Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_beaver_pro_uabb_pro',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Beaver Builder pro', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with the Beaver Builder Pro or Agency version and Ultimate Add Ons for Beaver Builder Pro Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_astra_free_uae_free',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Astra free & UAE free', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with Astra free theme, Elementor free version and Ultimate Add Ons for Elementor Free Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_astra_free_uae_pro',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Astra free & UAE pro', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with Astra free theme, Elementor free version and Ultimate Add Ons for Elementor Pro Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
              array(
                'name' => 'wpcr_astra_pro_uae_pro',
                'options' => array('yes'=>'Yes','no'=>'No'),
                'label'=> __( 'Astra pro & UAE pro', 'wp-website-creator' ),
                'desc' => __( 'Ready-made websites created with Astra Pro theme, Elementor free version and Ultimate Add Ons for Elementor Pro Plugin.', 'wp-website-creator' ),
                'type' => 'checkbox'
              ),
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap" style="padding:20px;">';
        settings_errors();

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }
  }
}

$settings = new WpWcreator_Wp_Website_Creator();

//redirect after activate this plugin
register_activation_hook(__FILE__, 'my_plugin_activate');
add_action('admin_init', 'my_plugin_redirect');

function my_plugin_activate() {
    add_option('my_plugin_do_activation_redirect', true);
}
// Solution 1
function my_plugin_redirect() {
    if (get_option('my_plugin_do_activation_redirect', false)) {
        delete_option('my_plugin_do_activation_redirect');
         wp_redirect("options-general.php?page=wp_website_creator_settings");
         //wp_redirect() does not exit automatically and should almost always be followed by exit.
         exit;
    }
}
?>
