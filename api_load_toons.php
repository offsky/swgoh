<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
    require_once("../vars.php");
    require_once("../libs.php");
    require_once("../api_swgoh_help3.php");
    $db = new mymysqli("swgoh");
    error_reporting(0);
} else {
    require_once("../../vars.php");
    require_once("../libs.php");
    require_once("../api_swgoh_help3.php");
    $db = new mymysqli("toodledo");
    error_reporting(E_ALL);
}
?>
<pre>
<?
try {
    
    //https://api.swgoh.help/swgoh
    $swgoh = new SwgohHelp($swgohHelpKeys);
    
    $data = $swgoh->fetchData('unitsList',"eng_us",array("rarity"=>7),array("id"=>1,"nameKey"=>1,"combatType"=>1));
    print_r($data);
    foreach($data as $toon) {
        $id = explode(":", $toon->id);
        $db->query("REPLACE INTO swgoh_toons2(id,name,type) VALUES('".$db->str($id[0])."','".$db->str($toon->nameKey)."',".intval($toon->combatType).")");
    }

//TODO: Get farming locations
// $data = $swgoh->fetchData('materialList',"eng_us",array("type"=>3),array("id"=>1,"nameKey"=>1,"lookupMissionList"=>1));
// print_r($data);

   

} catch(Exception $e) {
    echo $e;
}
?>