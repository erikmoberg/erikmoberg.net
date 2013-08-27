<?php

function do_post_request($url, $postdata, $files = null)
{
    $data = "";
    $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);
      
    //Collect Postdata
    foreach($postdata as $key => $val)
    {
        $data .= "--$boundary\n";
        $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
    }
    
    $data .= "--$boundary\n";
   
    //Collect Filedata
    //foreach($files as $key => $file)
    //{
        $fileContents = '<svg height="139" width="140" version="1.1" xmlns="http://www.w3.org/2000/svg"><desc>Created with Raphael</desc><defs><linearGradient y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="r-705564d0adda4dd4850d7985d12172b5"><stop stop-color="#0039ff" offset="0%"></stop><stop stop-color="#00dbcf" offset="100%"></stop></linearGradient><linearGradient y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="r-9fcc471a57f4412e85e5e4d4b2b6a3e1"><stop stop-color="#cccccc" offset="0%"></stop><stop stop-color="#ffffff" offset="100%"></stop></linearGradient></defs><rect style="opacity: 1;" fill-opacity="1" opacity="1" stroke="none" fill="url(#r-705564d0adda4dd4850d7985d12172b5)" ry="17" rx="17" r="17" height="138" width="138" y="0" x="0"></rect><path transform="" style="opacity: 1;" fill-opacity="1" opacity="1" d="M106.979,56.5636L81.644,56.5636L30.979000000000006,56.5636L30.979000000000006,81.8946L106.97900000000001,81.8946" stroke="none" fill="url(#r-9fcc471a57f4412e85e5e4d4b2b6a3e1)"></path><path fill-opacity="0.3" d="M17.444235,-0.5036982999999999C7.693235,-0.5036982999999999,-0.19076500000000252,8.2483017,-0.19076500000000252,19.1233017L-0.19076500000000252,80.1253017C9.419234999999997,81.8973017,19.584234999999996,82.88330169999999,30.097234999999998,82.88330169999999C89.822235,82.88330169999999,138.235235,52.21330169999999,138.235235,14.367301699999985C138.235235,14.038301699999984,138.206235,13.695301699999984,138.199235,13.367301699999985C138.193235,13.342301699999984,138.205235,13.312301699999985,138.199235,13.287301699999984C135.97723499999998,5.2873016999999845,129.31123499999998,-0.5036983000000159,121.38923499999999,-0.5036983000000159L17.444234999999992,-0.5036983000000159Z" stroke="none" fill="#ffffff"></path></svg>';
       
        $data .= "Content-Disposition: form-data; name=\"stdin\"; filename=\"minus.svg\"\n";
        $data .= "Content-Type: image/svg+xml\n";
		$data .= "\r\n";
        $data .= $fileContents."\r\n";
        $data .= "--$boundary--\n";
    //}
 
    $params = array('http' => array(
           'method' => 'POST',
           'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
           'content' => $data
        ));

   $ctx = stream_context_create($params);
   $fp = fopen($url, 'rb', false, $ctx);
 
   $response = stream_get_contents($fp);

   fclose($fp);
   return $response;
}

//sample data
$postdata = array();
    //'width' => '',
    //'height' => '',
    //'save' => ''
//);

//$response = '';

$response = do_post_request("http://www.fileformat.info/convert/image/svg2png.htm", $postdata);

header("Content-type: image/png");
header("Content-Disposition: attachment; filename=\"icon.png\"");
print $response;

?>