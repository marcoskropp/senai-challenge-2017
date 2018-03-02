<?php

require_once './app/Config.inc.php';

$read = new Read();
$read->fullRead("SELECT * FROM maps ORDER BY id DESC LIMIT 1");

function parseToXML($htmlStr) {
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

header("Content-type: text/xml");

echo '<markers>';

foreach ($read->getResult() as $row_markers) {
    echo '<marker ';
    echo 'lat="' . $row_markers['lat'] . '" ';
    echo 'lng="' . $row_markers['lng'] . '" ';
    echo '/>';
}

echo '</markers>';

