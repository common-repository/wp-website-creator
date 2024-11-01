<?php
add_action('admin_head', 'wpwc_admin_header_function');
function wpwc_admin_header_function()
{
  global $post;
  global $wpdb;
  $array_design = array();
  $array_design_2 = array();
  $custom = get_post_custom($post->ID);

  $wpwc_map_source = $custom["wpwc_map_source"][0];
	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];

  if($_POST['wpwc_map_designs'])
  {

  //We first grab the designs already in use
  $designsarray = get_post_meta($post->ID,'wpwc_map_all_designs_'.$formularid);

  foreach($designsarray as $key=>$val)
  {
    foreach($val as $key2=>$val2)
    {
      $designid = $val2['id'];
      if($designid > '1')
      {
        if($_POST['wpwc_map_design_'.$designid] == 'yes')
        {
          $design_small = $_POST["wpwc_design_small_".$designid];
          $design_medium = $_POST["wpwc_design_medium_".$designid];
          $design_large = $_POST["wpwc_design_large_".$designid];
          $design_demourl = $_POST["wpwc_design_demourl_".$designid];

          $array_design1 = array('id'=>$designid,'small'=>$design_small,'medium'=>$design_medium,'large'=>$design_large,'design_demourl'=>$design_demourl);
          array_push($array_design,$array_design1);

          $arrayd_2 = array('id'=>$designid,'pos'=>'0');
          array_push($array_design_2,$arrayd_2);

          update_post_meta($post->ID, "wpwc_map_".$formularid."_designs_".$designid, '1');
          update_post_meta($post->ID, "wpwc_map_".$formularid."_design_values_".$designid, $array_design);
          $array_design = array();
        }
      }
    }
  }

  //Then add the new imported
  foreach($_POST['wpwc_map_designs'] as $save_design=>$desid)
    {

      $des = $_POST['wpwc_map_design_'.$desid];
      $alreadyselected = get_post_meta($post->ID, "wpwc_map_".$formularid."_designs_".$desid, true);

      if($des=='yes' && $alreadyselected != '1')
      {
        $design_small = $_POST["wpwc_design_small_".$desid];
        $design_medium = $_POST["wpwc_design_medium_".$desid];
        $design_large = $_POST["wpwc_design_large_".$desid];
        $design_demourl = $_POST["wpwc_design_demourl_".$desid];

        $array_design1 = array('id'=>$desid,'small'=>$design_small,'medium'=>$design_medium,'large'=>$design_large,'design_demourl'=>$design_demourl);
        array_push($array_design,$array_design1);

        $arrayd_2 = array('id'=>$desid,'pos'=>'0');
        array_push($array_design_2,$arrayd_2);

        update_post_meta($post->ID, "wpwc_map_".$formularid."_designs_".$desid, '1');
        update_post_meta($post->ID, "wpwc_map_".$formularid."_design_values_".$desid, $array_design);
        $array_design = array();
      }
      else if($des=='no')
      {
        $delpost = 'wpwc_map_'.$formularid.'_design_values_'.$desid;
        delete_post_meta($post->ID,$delpost);
        $delpost = 'wpwc_map_'.$formularid.'_designs_'.$desid;
        delete_post_meta($post->ID,$delpost);
      }

    }
    update_post_meta($post->ID, "wpwc_map_all_designs_".$formularid, $array_design_2);

  }

  $thisdesigns = wpwc_get_themes();
  $importdesigns = $thisdesigns['themeimporter'];

	echo '<!-- This is the modal -->
  <div id="modal-designimport" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
      <h2 style="text-align:center;font-size:30px;">Theme importer</h2>
      <a target="_blank" style="float:right;" href="https://wp-website-creator.com">Offer your own prebuilt websites with a BIG PLAN membership</a>
      <form action="" method="post">
      <button class="modalclosebutton uk-modal-close" type="button">Close modal</button>
      <button type="submit" class="designsimortierenbutton" type="button">Import</button>
      '.$importdesigns.'
      <button class="modalclosebutton uk-modal-close" type="button">Close modal</button>
      <button type="submit" class="designsimortierenbutton" type="button">Import</button>
      </form>
      </p>
  </div>
  </div>';
}
?>
