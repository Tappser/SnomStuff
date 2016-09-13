<?php
header('Content-Type: application/xhtml+xml; charset=UTF-8');

$codeStatAll=".1.3.6.1.4.1.318.1.1.4.2.2.0";
$host = "192.168.1.3"; // IP des APC Masterswitch
$community="private";
$homeServer="192.168.1.2"; // IP or Name of your homeserver (where this script runs on)

// Getting the state of all Outputs
$outputs=snmpget($host,$community,$codeStatAll);
$outputs=substr(substr($outputs,9),0,-2);
$outArr=split(" ",$outputs);
// now outArr contains an Array of the values (Off,On)

// vkey_red defines, which states of the button turn it to red color.
// setting it to its default (RINGING DND RECORDING PICKUP) + OFF
?>
<settings>
<vkey_red perm="RW">OFF RINGING DND RECORDING PICKUP</vkey_red>
<functionKeys e="2">
<?php
// buttonTxt: array containing the descriptions for the virtual buttons (on snom 870).
$buttonTxt=array("Rechner","Monitor 3","Outlet 4","Outlet 4","3D-Printer","LJ2100","Scanner","Outlet 8");

$start=0;// with the startindex you can set the startbutton. 
$count=8;// the number of buttons to be provisioned.
for($i = $start ; $i < ($start+$count) ; $i++){
  //Creating the Button-Settings
  echo('  <fkey idx="'.($i).'" context="active" label="'.$buttonTxt[$i].'" perm="RW">
');
?>
<general type="RPCToggle"/>
<initialization>
<state value="<?php print((($outArr[$i]=="Off")?"OFF":"AVAILABLE")); ?>"/>
</initialization>
<action>
<?php
    echo('<url target="http://'.$homeServer.'/switch.php?outlet='.($i+1).'&state=on" when="on press" states="OFF"/>
<url target="http://'.$homeServer.'/switch.php?outlet='.($i+1).'&state=off" when="on press" states="AVAILABLE"/>
');
?>
<assign when="on press" states="AVAILABLE">
<source context="this entity" value="OFF"/>
<destination context="this entity" id="state"/>
</assign>
<assign when="on press" states="OFF">
<source context="this entity" value="AVAILABLE"/>
<destination context="this entity" id="state"/>
</assign>
</action>
</fkey>
<?php
}
?>
</functionKeys>
</settings>
