<?php

$headers = array(
  "Content-Type: text/xml",
  "HTTP_PRETTY_PRINT: TRUE",
  "HTTP_AUTH_LOGIN: $this_cPanel_username",
  "HTTP_AUTH_PASSWD: $this_cPanel_password",
  );

$request_2 = <<<EOF
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
    update_post_meta($post->ID, "wpwc_s_server_error", $errortext);
  }

  #$domainresult = $xml_2->webspace->get->result->data->gen_info->name;
  #$domainoptions = $xml_2->webspace->get->result->data->get_info->name;
  if($status == 'ok')
  {
    foreach ($xml_2->webspace->get->result as $item)
    {
      $domainresult .= $item->data->gen_info->name;
      if($item->data->gen_info->name!='')
      {
      $array_domain .= $item->data->gen_info->name.'#';
      }
    }
    update_post_meta($post->ID, "wpwc_map_domains", $array_domain);
    delete_post_meta($post->ID, "wpwc_s_server_error");
  }
}

  ?>
