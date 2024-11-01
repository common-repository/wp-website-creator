<?php

#######Get wp-website-creator username, password, client secret to login to rest api
#######
function get_wpwc_creadentials()
{
  global $wpdb;
  $wpcr_id = get_option( 'wpcr_id' );

    foreach($wpcr_id as $key=>$val)
      {
        if($key == 'wpcr_id' && $val!='')
        {
          $wpcr_id = $val;
        }
        if($key == 'wpcr_username' && $val!='')
        {
          $wpcr_username = $val;
          $settings_set = '1';
        }
        if($key == 'wpcr_password' && $val!='')
        {
          $wpcr_password = $val;
        }
        if($key == 'wpcr_preferred_software' && $val!='')
        {
          $wpcr_preferred_software = $val;
        }
      }

      if($settings_set=='1')
      {
      return array('wpcr_preferred_software' => $wpcr_preferred_software,'wpcr_id' => $wpcr_id,'wpcr_username' => $wpcr_username,'wpcr_password' => $wpcr_password);
      }
      else
      {
      return array('wpcr_preferred_software' => $wpcr_preferred_software,'wpcr_id' => '8hdArajyGWzPRI0Y2MKmxSDkt47TEHNcpZU6vOCi','wpcr_username' => 'global@wp-website-creator.com','wpcr_password' => 'D4vwq2PQjzT8NcXRMLJtkg1FSda5AWYeGH9yCpuZ');
      }
  #else return array('wpcr_id' => 'no');
}


#####Get user ID from wp-website-Creator#######
#############
function wpwc_get_userid()
	{
    global $wpdb;
    $wpwc_credentials = get_wpwc_creadentials();
    #wp_mail('sandner@cronema.com','user 1','1');
    if($wpwc_credentials["wpcr_id"] == 'no')
    {
      return 'no';
    }
    else
    {
    $wpcr_username = $wpwc_credentials["wpcr_username"];
    $wpcr_password = $wpwc_credentials["wpcr_password"];
    $wpcr_id = $wpwc_credentials["wpcr_id"];


    $url='https://wp-website-creator.com/wp-json/wp/v2/users/me';
    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $wpcr_username . ':' . $wpcr_password )
      )
    );

    $wpwc_response = wp_remote_get( $url, $args );
    $data = json_decode($wpwc_response['body'], TRUE);
    $id = $data['id'];
    return $id;
    }

  }

  function replace_semi($body)
  {
    $body = str_replace('&#8221;','"',$body);
    $body = str_replace('&#8243;','"',$body);
    $body = str_replace('<p>','',$body);
    $body = str_replace('</p>','',$body);
    return $body;
  }

  ##########Get the themes from wp-website-creator for this user and save it in database options
  #################
  function wpwc_get_themes_for_options()
  	{
    	global $wpdb;
      $thisuserid = wpwc_get_userid();



      if($thisuserid != 'no')
      {

        $wpwc_credentials = get_wpwc_creadentials();
        $wpcr_username = $wpwc_credentials["wpcr_username"];
        $wpcr_password = $wpwc_credentials["wpcr_password"];
        $wpcr_id = $wpwc_credentials["wpcr_id"];


        $url='https://wp-website-creator.com/wp-json/wp/v2/users/me';
        $args = array(
          'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( $wpcr_username . ':' . $wpcr_password )
          )
        );

        $wpwc_response = wp_remote_get( $url, $args );
        $data = json_decode($wpwc_response['body'], TRUE);

        $designarrayid = '0';

        $roles = $data['roles'];
        if($roles)
          {
            foreach ($roles as $key=>$val)
            {
              if($val == 'BIG_PLAN_Membership')
              {
                $membership = 'Big Plan';
                update_option( 'wpwc_cyourthemes', $data['cyourthemes'] );
                break;
              }
              else if($val == 'agency_membership')
              {
                $membership = 'Agency';
                break;
              }
              else if($val == 'FREE_Membership')
              {
                $membership = 'Free';
                break;
              }
            }
          }update_option( 'wpwc_membership', $membership);
      }

        if($thisuserid==''){$thisuserid='1';}
        $url="https://wp-website-creator.com/wp-json/wp-website-creator/v2/wpwcservices?secretkey=".$wpcr_id."&allthemes=".$thisuserid;
        $wpwc_response = wp_remote_get( $url );
        $body = $wpwc_response['body']; // use the content
        update_option( 'wpwc_themes', $body);

    }

    ##########Get the themes from wp-website-creator for this user and save it in database options
    #################
    function wpwc_get_themes()
    	{
        global $post;
      	global $wpdb;
        $page = $page;

        $membership = get_option( 'wpwc_membership');
        $allthemes = get_option( 'wpwc_themes');
        $selectedthemes = get_option( 'wpcr_themes');

        if($selectedthemes != '')
        {
          foreach($selectedthemes as $key=>$val)
            {
              if($key == 'wpcr_astra_free_uabb_free')
              {
                $wpcr_astra_free_uabb_free = $val;
              }
              if($key == 'wpcr_astra_free_uabb_pro')
              {
                $wpcr_astra_free_uabb_pro = $val;
              }
              if($key == 'wpcr_astra_pro_uabb_pro')
              {
                $wpcr_astra_pro_uabb_pro = $val;
              }
              if($key == 'wpcr_beaver_pro_uabb_pro')
              {
                $wpcr_beaver_pro_uabb_pro = $val;
              }
              if($key == 'wpcr_astra_free_uae_free')
              {
                $wpcr_astra_free_uae_free = $val;
              }
              if($key == 'wpcr_astra_free_uae_pro')
              {
                $wpcr_astra_free_uae_pro = $val;
              }
              if($key == 'wpcr_astra_pro_uae_pro')
              {
                $wpcr_astra_pro_uae_pro = $val;
              }
              if($key == 'wpcr_yourthemes')
              {
                $wpcr_yourthemes = $val;
              }
            }
        }



        $yourtheme_explo = explode('###yourtheme###',$allthemes);
        $freetheme_explo = explode('###freetheme###',$allthemes);
        $astrafreetheme_explo = explode('###astrafreetheme###',$allthemes);
        $astratheme_explo = explode('###astratheme###',$allthemes);
        $free_e_explo = explode('###free_e###',$allthemes);
        $astra_free_e_explo = explode('###astra_free_e###',$allthemes);
        $astra_e_explo = explode('###astra_e###',$allthemes);
        $beaver_explo = explode('###beaver###',$allthemes);

        if($wpcr_yourthemes=='on' && $yourtheme_explo[1]!='')
        {
        $yourtheme = get_allowed_designs($yourtheme_explo[1],$membership,'Your themes');
        }
        if($wpcr_astra_free_uabb_free=='on' && $freetheme_explo[1]!='')
        {
        $freetheme = get_allowed_designs($freetheme_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://wordpress.org/themes/astra/'>Astra free theme</a> + <a target='_blank' href='https://wordpress.org/plugins/ultimate-addons-for-beaver-builder-lite/'>Ultimate Addons for Beaver Builder free</a>");
        }
        if($wpcr_astra_free_uabb_pro=='on' && $astrafreetheme_explo[1]!='')
        {
        $astrafreetheme = get_allowed_designs($astrafreetheme_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://wordpress.org/themes/astra/'>Astra free theme</a> + <a target='_blank' href='https://www.ultimatebeaver.com/?bsf=157'>Ultimate Addons for Beaver Builder pro</a>");
        }
        if($wpcr_astra_pro_uabb_pro=='on' && $astratheme_explo[1]!='')
        {
        $astratheme = get_allowed_designs($astratheme_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://wpastra.com/?bsf=157'>Astra pro theme</a> + <a target='_blank' href='https://www.ultimatebeaver.com/?bsf=157'>Ultimate Addons for Beaver Builder pro</a>");
        }
        if($wpcr_astra_free_uae_free=='on' && $free_e_explo[1]!='')
        {
        $free_e = get_allowed_designs($free_e_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://wordpress.org/themes/astra/'>Astra free theme</a> + <a target='_blank' href='https://wordpress.org/plugins/essential-addons-for-elementor-lite/'>Ultimate Addons for Elementor free</a>");
        }
        if($wpcr_astra_free_uae_pro=='on' && $astra_free_e_explo[1]!='')
        {
        $astra_free_e = get_allowed_designs($astra_free_e_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://wordpress.org/themes/astra/'>Astra free theme</a> + <a target='_blank' href='https://uaelementor.com/?bsf=157'>Ultimate Addons for Elementor pro</a>");
        }
        if($wpcr_astra_pro_uae_pro=='on' && $astra_e_explo[1]!='')
        {
        $astra_e = get_allowed_designs($astra_e_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://wpastra.com/?bsf=157'>Astra pro theme</a> + <a target='_blank' href='https://uaelementor.com/?bsf=157'>Ultimate Addons for Elementor pro</a>");
        }
        if($wpcr_beaver_pro_uabb_pro=='on' && $beaver_explo[1]!='')
        {
        $beaver = get_allowed_designs($beaver_explo[1],$membership,"BUILT WITH <a target='_blank' href='https://www.wpbeaverbuilder.com/?fla=1133'>Beaver Builder theme</a> + <a target='_blank' href='https://www.ultimatebeaver.com/?bsf=157'>Ultimate Addons for Beaver Builder pro</a>");
        }


        #return $images_pr;
        #return var_dump($data);
        return array('themeimporter' => $yourtheme.$freetheme.$astrafreetheme.$astratheme.$free_e.$astra_free_e.$astra_e.$beaver,'selectedarray' => $array_design,'membership' => $membership);
      }


      function wpwc_call_cpanel($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$cppostid = '',$cpanelreturn='',$checkdomains='')
      {
      	global $post;
      	global $wpdb;

        $this_cPanel_url = prepare_wpwc_server_login_url($this_cPanel_url);



          $args = array(
            'headers' => array(
              'Authorization' => 'Basic ' . base64_encode( $this_cPanel_username . ':' . $this_cPanel_password )
            )
          );



      	$url = $this_cPanel_url.'json-api/cpanel?cpanel_jsonapi_user='.$this_cPanel_username.'&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=DomainLookup&cpanel_jsonapi_func=getmaindomain';
      	$urladdomains = $this_cPanel_url.'json-api/cpanel?cpanel_jsonapi_user='.$this_cPanel_username.'&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&cpanel_jsonapi_func=listaddondomains';
      	$urlsubdomains = $this_cPanel_url.'json-api/cpanel?cpanel_jsonapi_user='.$this_cPanel_username.'&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=SubDomain&cpanel_jsonapi_func=listsubdomains';


      	$wpwc_response = wp_remote_get( $url, $args );
      	if($wpwc_response['body'])
      	 {
      		$data = json_decode($wpwc_response['body'], TRUE);
      		//We get data from the url
      		if($data['cpanelresult'])
      			{
      					$wpwcerror = $data['cpanelresult']['error'];
      					if($wpwcerror && $cppostid>'0')
      					{
      						update_post_meta($cppostid, "wpwc_s_server_error", $wpwcerror);exit;
      					}
      					if(!$wpwcerror)
      					{
      						if($data['cpanelresult']['data'])
      						{
      							foreach ($data['cpanelresult']['data'] as $maindo)
      							{
                      if($maindo['main_domain'])
                      {
      								$maincpaneldomain = $maindo['main_domain'];
      								//Wir scannen alle Dateine im folder der Hauptdomain

      									$array_domain .= $maincpaneldomain.'#';
                        if($maincpaneldomain==$checkdomains){$domainexists='1';}
      								}
      							}
      						}
      							//If there is a domain array
      							if($array_domain && $cppostid>'0'){delete_post_meta($cppostid, "wpwc_s_server_error");}


      						//Second call only if first call is ok
      						$wpwc_response = wp_remote_get( $urladdomains, $args );
      						$data = json_decode($wpwc_response['body'], TRUE);

                  if($data['cpanelresult']['error'])
                  {
      						$wpwcerror = $data['cpanelresult']['error'];
                  }
      						if($wpwcerror && $cppostid>'0')
      						{
      						 update_post_meta($cppostid, "wpwc_s_server_error", $wpwcerror);
      						}
      						if(!$wpwcerror)
      						{
      							if($data['cpanelresult']['data'])
      							{
      								foreach ($data['cpanelresult']['data'] as $maindo)
      								{
                        if($maindo['domain'])
                        {
      									$addondomain = $maindo['domain'];
      									//Wir scannen alle Dateine im folder der Hauptdomain

      										$array_domain .= $addondomain.'#';
                          if($addondomain==$checkdomains){$domainexists='1';}
      									}
      								}
      							}
      							if($array_domain && $cppostid>'0'){delete_post_meta($cppostid, "wpwc_s_server_error");}
      						}

      							//Third call only if first call is ok
      							$wpwc_response = wp_remote_get( $urlsubdomains, $args );
      							$data = json_decode($wpwc_response['body'], TRUE);

      							$wpwcerror = $data['cpanelresult']['error'];
      							if($wpwcerror && $cppostid>'0')
      							{
      								update_post_meta($cppostid, "wpwc_s_server_error", $wpwcerror);
      							}
      							if(!$wpwcerror)
      							{
      								if($data['cpanelresult']['data'])
      								{
      									foreach ($data['cpanelresult']['data'] as $maindo)
      										{
                            if($maindo['domain'])
                            {
      											$subdomain = $maindo['domain'];
      											//Wir scannen alle Dateine im folder der Hauptdomain

      												$array_domain .= $subdomain.'#';
                              if($subdomain==$checkdomains){$domainexists='1';}
      											}
      										}
      								}
      								if($array_domain){delete_post_meta($cppostid, "wpwc_s_server_error");}
      							}//No error in third call
      							if($cppostid>'0')
      							{
      							update_post_meta($cppostid, "wpwc_map_domains", $array_domain);
      						}
      						}//Main data call no error
      				}//Main data call data exists
      			}//Main tata call there is a body

      		else
      		{
            if($cppostid > '0')
            {
      				update_post_meta($cppostid, "wpwc_s_server_error", "Wrong URL");
            }
      		}
          if($cpanelreturn=='1')
          {
            return $array_domain;
          }
          if($checkdomains!='')
          {
            return $domainexists;
          }
      }


      function wpwc_call_whm($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$cppostid = '',$logintype,$returndomains='0')
      {
        global $post;
        global $wpdb;

        if($logintype == 'token')
        {
          $args = array(
            'headers' => array(
              #$header[0] = "Authorization: whm $this_cPanel_username:$this_cPanel_password" . "\n\r";
              'Authorization' => 'whm '.$this_cPanel_username.':'.$this_cPanel_password
            )
          );
        }
        if($logintype == 'password')
        {
          $args = array(
            'headers' => array(
              'Authorization' => 'Basic ' . base64_encode( $this_cPanel_username . ':' . $this_cPanel_password )
            )
          );
        }

        $url = $this_cPanel_url.'json-api/listpkgs';
        #echo $query;
        $wpwc_response = wp_remote_get( $url, $args );
        $data = json_decode($wpwc_response['body'], TRUE);
        #return '<pre>' . print_r($data, TRUE) . '</pre>';

        //Wir holen alle Pakete und schreiben sie in das Pakete Feld
        if($data['package'])
        {
        foreach ($data['package'] as $paket)
          {
            $allepakete .= $paket['name'].'#';
          }
          update_post_meta($cppostid, "wpwc_map_pakete", $allepakete);
        }


       $url = $this_cPanel_url.'json-api/listaccts?api.version=1&searchtype=domain';
       $wpwc_response = wp_remote_get( $url, $args );

       $data = json_decode($wpwc_response['body'], TRUE);
       //We get data from the url
       if($data)
        {
          $wpwcerror = $data['cpanelresult']['error'];
          //But there is a error connecting to whm
          if($wpwcerror)
          {
            if($cppostid>='1')
            {
            update_post_meta($cppostid, "wpwc_s_server_error", $wpwcerror);
            }
          }//End Server connection error
          //If we get no error o on
          if(!$wpwcerror)
            {
              if($data['data']['acct'])
              {
                foreach ($data['data']['acct'] as $itinerary)
                {
                  $thisdomain = $itinerary['domain'];
                  $array_domain .= $thisdomain.'#';
                  if($thisdomain == $returndomains){$domainexists = '1';}
                }
                if($array_domain)
                {
                  if($cppostid>='1')
                  {
                  delete_post_meta($cppostid, "wpwc_s_server_error");
                  }
                }
              }
              if($cppostid>='1')
              {
              update_post_meta($cppostid, "wpwc_map_domains", $array_domain);
              }
            }//End no server error / Connection ok
        }//we get data
        if(!$data)
        {
          if($cppostid>='1')
          {
          update_post_meta($cppostid, "wpwc_s_server_error", "Wrong URL");
          }
        }

        if($returndomains!='0')
        {
          return $domainexists;
        }
        if(!$data)
        {
          return '';
        }


      }

      //call $plesk
      function wpwc_call_plesk($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$cppostid = '',$getpackage='',$checkdomains='',$checkcustomers='')
      {

        global $post;
        global $wpdb;
              if($cppostid>'1')
              {
        			     update_post_meta($cppostid, "wpwc_s_server_error", "Can't login");
              }
        			$headers = array(
        			  "Content-Type: text/xml",
        			  "HTTP_PRETTY_PRINT: TRUE",
        			  "HTTP_AUTH_LOGIN: $this_cPanel_username",
        			  "HTTP_AUTH_PASSWD: $this_cPanel_password",
        			  );

        				//Domain
        			$request = <<<EOF
        			<?xml version="1.0" encoding="UTF-8"?>
        			<packet>
        			<webspace>
        			 <get>
        			  <filter/>
        			  <dataset>
        			   <gen_info/>
        			  </dataset>
        			 </get>
        			</webspace>
        			</packet>
EOF;

        			$curl = curl_init();
        			curl_setopt($curl, CURLOPT_URL, $this_cPanel_url.'enterprise/control/agent.php');
        			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        			curl_setopt($curl, CURLOPT_POST, true);
        			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);


        			  $result = curl_exec($curl);
        			  if($result)
        			  {
        			  $xml= simplexml_load_string($result);

        			  $errortext = $xml->webspace->get->result->errtext;
        			  $status = $xml->webspace->get->result->status;
        			  $pleskid = $xml->webspace->get->result->id;


        			  if($status=='error')
        			  {
                  if($cppostid>'1')
                  {
        			       update_post_meta($cppostid, "wpwc_s_server_error", $errortext);

                  }
        			  }

        			  #$domainresult = $xml_2->webspace->get->result->data->gen_info->name;
        			  #$domainoptions = $xml_2->webspace->get->result->data->get_info->name;
        			  if($status == 'ok')
        			  {
                  #wp_mail('sandner@cronema.com','plesk','call'.$domainexists);
        			    foreach ($xml->webspace->get->result as $item)
        			    {
        			      $domainresult .= $item->data->gen_info->name;
        			      if($item->data->gen_info->name!='')
        			      {
        			      $array_domain .= $item->data->gen_info->name.'#';
                    if($item->data->gen_info->name==$checkdomains)
                    {
                      return '1';
                    }

        			      }
        			    }
                  if($cppostid>'1')
                  {
        			         update_post_meta($cppostid, "wpwc_map_domains", $array_domain);
        			         delete_post_meta($cppostid, "wpwc_s_server_error");
                  }
        			  }
                #wp_mail('sandner@cronema.com','plesk','call'.$domainexists);
        			}//End if result domains

              if($getpackage=='1')
              {
        			//Paket
        			$request = <<<EOF
        		  <?xml version="1.0"?>
        		  <packet version="1.6.3.0">
        		    <service-plan>
        		  	   <get>
        		         <filter>

        		         </filter>
        		  	    </get>
        		    </service-plan>
        		  </packet>
EOF;

        			$curl = curl_init();
        			curl_setopt($curl, CURLOPT_URL, $this_cPanel_url.'enterprise/control/agent.php');
        			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        			curl_setopt($curl, CURLOPT_POST, true);
        			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);


        			  $result = curl_exec($curl);


        			  if($result)
        			  {
        			  $xml= simplexml_load_string($result);

        				$systemerrortext = $xml->{"service-plan"}->get->result->errtext;
        				$systemerrorstatus = $xml->{"service-plan"}->get->result->status;
        				if($systemerrorstatus=='ok')
        				{
        				foreach ($xml->{"service-plan"}->get->result as $serviceplans)
        					{
        						$array_pakete .= $serviceplans->name.'#';
        					}
        				}

        			  if($status=='error')
        			  {
                  if($cppostid>'1')
                  {
        			    update_post_meta($cppostid, "wpwc_s_server_error", $errortext);
                  }
        			  }
        				if($status=='ok')
        			  {
                  if($cppostid>'1')
                  {
        			    update_post_meta($cppostid, "wpwc_map_pakete", $array_pakete);
        			    delete_post_meta($cppostid, "wpwc_s_server_error");
                  }
        			  }
        			}//End if result
          }//End if get package

          //customers
          if($checkcustomers=='1')
          {
          //Paket
          #wp_mail('sandner@cronema.com','customers','1');
          $request = <<<EOF
          <?xml version="1.0" encoding="UTF-8"?>
          <packet>
          <customer>
          <get>
             <filter>
             <owner-login>$this_cPanel_username</owner-login>
             </filter>
          <dataset>
            <gen_info/>
          </dataset>
          </get>
          </customer>
          </packet>
EOF;

          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $this_cPanel_url.'enterprise/control/agent.php');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $request);


            $result = curl_exec($curl);


            if($result)
            {
            $xml= simplexml_load_string($result);

            $systemerrortext = $xml->customer->get->result->errtext;
            $systemerrorstatus = $xml->customer->get->result->status;
            if($systemerrorstatus=='ok')
            {
            foreach ($xml->customer->get->result as $customer)
              {
                $array_plesk_customers .= $customer->data->gen_info->login.'-csplit-'.$customer->id.'-cend-';
              }
            }

            if($status=='ok')
            {
              if($cppostid>'1')
              {
              update_post_meta($cppostid, "wpwc_map_customers", $array_plesk_customers);
              }
            }
          }//End if result
      }//End if customer


      }


      function call_wpwcservers_get_subdomain($checkdomains)
      {
        #wp_mail('sandner@cronema.com','domaincheck auf wpwc server','domaincheck auf wpwc server');
        $wpwc_credentials = get_wpwc_creadentials();
        $secretkey = $wpwc_credentials["wpcr_id"];
        $url='https://wp-website-creator.com/wp-json/wp-website-creator/v2/wpwcservices?secretkey='.$secretkey.'&checkdomain='.$checkdomains;

        $wpwc_response = wp_remote_get( $url );
        $body = $wpwc_response['body']; // use the content
        if(substr($body,0,1) =='1')
        {
        return '1';
        }
      }

      function wpwc_website_verification($checkdomains)
      {
        #wp_mail('sandner@cronema.com','domaincheck auf wpwc server','domaincheck auf wpwc server');
        $wpwc_credentials = get_wpwc_creadentials();
        $secretkey = $wpwc_credentials["wpcr_id"];
        $url='https://wp-website-creator.com/wp-json/wp-website-creator/v2/wpwcservices?secretkey='.$secretkey.'&installerdomain='.$checkdomains;
        wp_remote_get( $url );
      }

      function wpwc_call_tld($checkdomains)
      {
        $wpwc_credentials = get_wpwc_creadentials();
        $secretkey = $wpwc_credentials["wpcr_id"];
        #wp_mail('sandner@cronema.com','domaincheck auf wpwc server','domaincheck auf wpwc server');
        $url='https://wp-website-creator.com/wp-json/wp-website-creator/v2/wpwcservices?secretkey='.$secretkey.'&checktld='.$checkdomains;

        $wpwc_response = wp_remote_get( $url );
        $body = $wpwc_response['body']; // use the content
        if(substr($body,0,1) =='1')
        {
        return '1';
        }
      }

      function call_wpwcservers_get_maindomain()
      {
        global $wpdb;
        $wpwc_credentials = get_wpwc_creadentials();
        $secretkey = $wpwc_credentials["wpcr_id"];

        $wpcr_preferred_software = $wpwc_credentials["wpcr_preferred_software"];

        if(!$wpcr_preferred_software or $wpcr_preferred_software=='')
        {
          $prefered_software = 'cPanel';
        }

        $url='https://wp-website-creator.com/wp-json/wp-website-creator/v2/wpwcservices?secretkey='.$secretkey.'&get_maindomain=1&wpcr_preferred_software='.$wpcr_preferred_software;
        $wpwc_response = wp_remote_get( $url );
        $body = $wpwc_response['body']; // use the content

        return $body;

      }

      //CPanel docroot
      function wpwc_call_docroot($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$maincpaneldomain)
      {
      	global $post;
      	global $wpdb;

        $this_cPanel_url = prepare_wpwc_server_login_url($this_cPanel_url);

      	$args = array(
      		'headers' => array(
      			'Authorization' => 'Basic ' . base64_encode( $this_cPanel_username . ':' . $this_cPanel_password )
      		)
      	);

        $url = $this_cPanel_url.'json-api/cpanel?cpanel_jsonapi_user='.$this_cPanel_username.'&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=DomainLookup&cpanel_jsonapi_func=getdocroot&domain='.$maincpaneldomain;

      	$wpwc_response = wp_remote_get( $url, $args );
      	if($wpwc_response['body'])
      	 {
      		$data = json_decode($wpwc_response['body'], TRUE);
      		//We get data from the url
      		if($data)
      			{

      					$wpwcerror = $data['cpanelresult']['error'];

      					if(!$wpwcerror)
      					{
                  if($data['cpanelresult']['data'])
                  {
                  foreach ($data['cpanelresult']['data'] as $maindocroot)
                    {
                      $existingfolder = $maindocroot[reldocroot];
                    }
                  }
      					}
      			 }//End if data
          }//End if body

          $url = $this_cPanel_url.'json-api/cpanel?cpanel_jsonapi_user='.$this_cPanel_username.'&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Fileman&recursive=0&cpanel_jsonapi_func=search&dir='.$existingfolder;

          $wpwc_response = wp_remote_get( $url, $args );
          if($wpwc_response['body'])
        	 {
        		$data = json_decode($wpwc_response['body'], TRUE);
        		//We get data from the url
        		if($data)
        			{
        					$wpwcerror = $data['cpanelresult']['error'];
                  if(!$wpwcerror)
                  {
                    if($data['cpanelresult']['data'])
                    {
                    foreach ($data['cpanelresult']['data'] as $cpfile)
                      {
                        $cpfilearray .= $cpfile[file].',';
                      }
                    }
                    #return $cpfilearray;
                    if( preg_match("/wp-config.php/i", $cpfilearray) ){return array('exists'=>'yes','folder'=>'');}else{return array('exists'=>'no','folder'=>$existingfolder);}
                  }
        					if($wpwcerror)
        					{
                    return $wpwcerror;
        					}
        			 }//End if data
            }//End if body
      }//function docroot


?>
