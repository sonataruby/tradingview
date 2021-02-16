<?php

function getPoint($symbol){
        $arv =[
            "XAUUSD" =>   [0.001,100,3],
            "GBPUSD"  =>  [0.00001,10,5],
            "GBPJPY"  =>  [0.001,10,3],
            "GBPCHF"  =>  [0.00001,10,5],
            "GBPNZD"  =>  [0.00001,10,5],
            "GBPCAD"  =>  [0.00001,10,5],
            "GBPAUD"  =>  [0.00001,10,5],
            "EURUSD"  =>  [0.00001,10,5],
            "EURAUD"  =>  [0.00001,10,5],
            "EURCAD"  =>  [0.00001,10,5],
            "EURCHF"  =>  [0.00001,10,5],
            "EURGBP"  =>  [0.00001,10,5],
            "EURJPY"  =>  [0.001,10,3],
            "EURNZD"   => [0.00001,10,5],
            "USDJPY"   => [0.001,10,3],
            "USDCHF"   => [0.001,10,3],
            "USDCAD"   => [0.001,10,3],
            "AUDUSD"   => [0.001,10,3],
            "AUDCAD"   => [0.001,10,3],
            "AUDJPY"   => [0.001,10,3],
            "AUDCHF"   => [0.001,10,3],
            "CHFJPY"   => [0.001,10,3],
            "NZDUSD"   => [0.001,10,3],
            "NZDJPY"   => [0.001,10,3],
            "NZDCAD"   => [0.001,10,3],
            "NZDCHF"   => [0.001,10,3],
            "AUDNZD"   => [0.001,10,3],
            "CADCHF"   => [0.001,10,3],
            "CADJPY"   => [0.001,10,3]
        ];
        return $arv[$symbol];
    }

    

   
    $token = "1009655525:AAFJ_tvTk_N1xPRpTqbmwB7g0u6xh8_rZ78";
    $channel = '@killerwhaletrade';
    $data = file_get_contents('php://input');
     if($_GET["test"]) $data = $_GET["test"];
        list($symbol, $type, $open,$close,$high,$low,$slpip,$tppip) = explode('|', $data);
        list($point, $factor, $dig) = getPoint($symbol);
        $entry = number_format($close,$dig,".","");
        if($slpip == "") $slpip = 20;
        if($tppip == "") $tppip = 100;
        $center = number_format(($high + $low)/2,$dig,".","");
        if($type == "SELL"){
            $sl = number_format($entry + ($slpip * $point * $factor),$dig,".","");
            $tp = number_format($entry - ($tppip * $point * $factor),$dig,".","");
        }else if($type == "BUY"){
            $sl = number_format($entry - ($slpip * $point * $factor),$dig,".","");
            $tp = number_format($entry + ($tppip * $point * $factor),$dig,".","");
        }
        
        if($sl < $center) $sl = $center;

        $msgTelegram = "
        <b>WHALE VIP SIGNAL</b>
        <b>SYMBOL : ".$symbol."</b>
        TYPE : ".($type == "BUY" ? "BUY" : "SELL")."
        Entry : ".$entry."
        Stoploss : ".$sl."
        Take Profit : ".$tp."
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