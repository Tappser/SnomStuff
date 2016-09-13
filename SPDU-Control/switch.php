<?php
header('Content-Type: application/xhtml+xml; charset=UTF-8');
/*
------------------------------------------------------------------------------

Shrinked version of:
  APC_Masterswitch-SNMP-Tool - AP9212 - v.1.0.6 (C) 2009 Martin Fuchs
                          -> https://github.com/trendchiller/AP9212

Call over HTTP: switch.php?outlet=#&state=# [State: on, off, restart]
------------------------------------------------------------------------------
*/

$host = "192.168.1.4"; // APC Masterswitch IP
$community = "private";
$codeStatus =".1.3.6.1.4.1.318.1.1.4.4.2.1.3.";

$statusdescr = array(
  "on" => "1",
  "off" => "2",
  "restart" => "3" 
);


if (!empty($_GET['outlet'])) { // Get Outlet-Value
  $outlet = $_GET['outlet'];   
} else
  die("Outlet must be set\n");

  
if (!empty($_GET['state'])) { // Get State-Value
    if ($_GET['state'] == 'on' || $_GET['state'] == 'off' || $_GET['state'] == 'restart') {
      $status = $statusdescr[$_GET['state']];
      $set = snmpset($host, $community, $codeStatus.$outlet,"i",$status);
    }
}

if( $set ){
  echo("<state>".(($status==1)?"1":"0")."</state>");
}
?>
