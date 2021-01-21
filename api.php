<?php
$data = file_get_contents('php://input');
list($symbol, $type, $open,$close,$high,$low) = explode('|', $data);
$token = "1009655525:AAFJ_tvTk_N1xPRpTqbmwB7g0u6xh8_rZ78";
$channel = '@smartiqx';
$msgTelegram = "
<b>SMART MARGIN ".$symbol."</b>
TYPE : ".($type == "BUY" ? "Long" : "Short")."
Entry : ".$entry."
Stoploss : 10%
Take Profit : 3%-7%
";
$msgTelegram = str_replace("\t", "", $msgTelegram);
if($msgTelegram != ""){
    
    $postFields = array(
        'chat_id' => $channel,
        'text' => '<pre>'.$msgTelegram.'</pre>',
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
        
    );
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_exec($ch);
}

//Send order to service

?>