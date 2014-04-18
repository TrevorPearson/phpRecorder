<?php
function foo()
{
    $numargs = func_num_args();
    echo "Number of arguments: $numargs<br />\n";
    if ($numargs >= 2) {
        echo "Second argument is: " . func_get_arg(1) . "<br />\n";
    }
    $arg_list = func_get_args();
    for ($i = 0; $i < $numargs; $i++) {
        echo "Argument $i is: " . $arg_list[$i] . "<br />\n";
    }
}
function loadDatabase()
{
	$databaseFileName = 'data.xml';
	if (file_exists ($databaseFileName)){
		return simplexml_load_file($databaseFileName);
	}
	//create or load
	return new SimpleXMLElement("<data></data>");

}
function getToday()
{	

	date_default_timezone_set("America/Chicago");
	//return date('Y-m-d H:i:s');
	return date('Y-m-d H:i:s');
}
function printDatabase(){
	$newsXML = loadDatabase();
	Header('Content-type: text/xml');
	echo $newsXML->asXML();
}

//Only continue if it's not a specialCommand
function validateRequest(){
	if (count($_GET)==0){
		echo "ping<br>";
		$url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$url .= $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
		echo '<a href="'.$url.'?_viewOnly=true">View Database</a>';
		exit;
	}
	
	if (isset($_GET["_viewOnly"]) && strcmp($_GET["_viewOnly"],"true")==0){
		printDatabase();
		exit;
	}
	
	
}
validateRequest();

$newsXML = loadDatabase();

$newsIntro = $newsXML->addChild('record');
foreach ($_GET as $param_name => $param_val) {	
	$newsIntro->addAttribute($param_name, $param_val);
    //echo "Param: $param_name; Value: $param_val<br />\n";
}
$newsIntro->addAttribute('Date', getToday());


$newsXML->asXml('data.xml');
echo "Added Record<br>";


?>
