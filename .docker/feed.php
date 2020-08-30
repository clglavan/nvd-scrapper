<?php
require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

try{
    $bucket = getenv('BUCKET');
    $projectId = getenv('PROJECTID');
    $filename = getenv('FILENAME');
    $format = getenv('FORMAT');

    $raw_data = file_get_contents('https://nvd.nist.gov/feeds/xml/cve/misc/nvd-rss.xml');

    $xml = simplexml_load_string($raw_data);
    unset($xml->channel);

    if($bucket){
        $fp = fopen($filename, 'w');
    }

    $vulns = [];

    foreach ($xml as $item){

        /* get the CVE ID */ 
        $link_split = parse_url($item->link);
        parse_str($link_split['query'], $params); 

        if($format == 'json'){
            $vuln = [];
            $vuln["vulnId"]     = $params['vulnId'];
            $vuln["title"] = (string)$item->title;
            $vuln["link"]  = (string)$item->link;
            $vuln["description"] = (string)$item->description;
            $vuln["timestamp"] = (string)$item->xpath("dc:date")[0];
            $vuln["source"] = "nvd.nist.gov";
            
            $vuln_json = json_encode($vuln);
            print($vuln_json."\n");

            if($bucket){
                $vulns[] = $vuln;
            }

        }else{
            $controlchar="`";
            $vuln = $params['vulnId'].$controlchar.(string)$item->title.$controlchar.(string)$item->link.$controlchar.(string)$item->description.$controlchar.(string)$item->xpath("dc:date")[0].$controlchar."nvd.nist.gov"."\r\n";
            print($vuln);
            
            if($bucket){
                // fputcsv($fp,)
                fwrite($fp, $vuln);
            }
        }
        
    }

    if($format == 'json'){
        if($bucket){
            fwrite($fp, json_encode(["vulns" => $vulns]));
        }
    }

    if($bucket){
        fclose($fp);
        $config = [
            'projectId' => $projectId
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