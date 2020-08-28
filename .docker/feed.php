<?php

try{
    /* where to send the data - for future */
    $host = getenv('HOST');
    $port = getenv('PORT');

    $raw_data = file_get_contents('https://nvd.nist.gov/feeds/xml/cve/misc/nvd-rss.xml');

    $xml = simplexml_load_string($raw_data);
    unset($xml->channel);

    foreach ($xml as $item){

        /* get the CVE ID */ 
        $link_split = parse_url($item->link);
        parse_str($link_split['query'], $params); 
        $vuln = [];
        $vuln["vulnId"]     = $params['vulnId'];

        $vuln["title"] = (string)$item->title;
        $vuln["link"]  = (string)$item->link;
        $vuln["description"] = (string)$item->description;
        $vuln["@timestamp"] = (string)$item->xpath("dc:date")[0];
        $vuln["source"] = "nvd.nist.gov";
        
        $vuln_json = json_encode($vuln);
        print($vuln_json."\n");

        /* TBD - send data to a relay sever */
        // $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        // $msg = $vuln_json;
        // $len = strlen($msg);

        // socket_connect($sock, $host, $port);
        // socket_sendto($sock, $msg, $len, 0, $host, $port);
        // socket_close($sock);

    }
}catch(Exception $e){
    echo $e;
}
?>