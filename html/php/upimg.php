<?php
session_start();
echo $_SESSION['token'];
$encodeimg=$_POST["base64"];
echo $encodeimg;

$data = array("Image" =>'$encodeimg', "token" =>$_SESSION['token']);                                        
$data_string = json_encode($data);                                                                          
$ch = curl_init('http://35.221.196.173/image/Upload');                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);             
$result = curl_exec($ch);
echo $result;
?>