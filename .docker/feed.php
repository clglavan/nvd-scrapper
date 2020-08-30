<?php
require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

try{
    $bucket = getenv('BUCKET');
    $projectId = getenv('PROJECTID');

    $raw_data = file_get_contents('https://nvd.nist.gov/feeds/xml/cve/misc/nvd-rss.xml');

    $xml = simplexml_load_string($raw_data);
    unset($xml->channel);

    if($bucket){
        $filename =  'results.json';
        
        $fp = fopen($filename, 'w');
    }

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

        if($bucket){
            fwrite($fp, json_encode($vuln_json));
        }

    }

    if($bucket){
        fclose($fp);
        $config = [
            'projectId' => $projectId,
            'overwrite' => true,
        ];
        $storage = new StorageClient($config);
        $bucketObj = $storage->bucket($bucket);

        // Upload results to the bucket.
        $bucketObj->upload(
            fopen($filename, 'r')
        );
    }

}catch(Exception $e){
    echo $e;
}
?>