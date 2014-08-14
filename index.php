<?php
ini_set('display_errors', '1');


function mongo_connect(){
	$mongo_server = '23.253.231.237';
    	try{
    		$m = new MongoClient("mongodb://{$mongo_server}:27017");
    		return $m;
    	}catch(Exception $e){
    		echo $e;
    		return false;
    	}
    }

function get_mongo_info($client){
	if($client){			
		//Produccion
		$db = $client->selectDB("censo_completo_2013");
		$c = $db->selectCollection('datos_escuelas_v2');

		$fp=fopen("cct.csv", "r");

$vars = array(
	"turno",			//0
	"num_grupos",		//1
	"num_alumnos",		//2
	"num_personal",		//3
	"cct",				//4
	"cct_escuelas",		//5

	"nombre",			//6
	"edo",				//7
	"calle",			//8
	"numero_dir",		//9
	"cp",				//10
	"tipo",				//11
	"telefono",			//12
	"status",			//13
	"nivel"			    //14
	);

		while (($data=fgetcsv($fp))!==false) {
			$values=array();
			foreach ($data as $cct) {
				$aux = $c->find(array('cct_escuelas'=>$cct));
echo "<div class='left'>";
				echo $cct."<br/>";
				 if(count($aux)>0){
				 	foreach($aux as $e) {
				 		echo $e["cct"]."<br/>";
				 		for($i=0;$i<15;$i++){
				 			try{
				 				if(!isset($values[$vars[$i]])){
				 					$values[$vars[$i]]=$e[$vars[$i]];
				 				}else{
					 				if($i<5){
					 					if($values[$vars[$i]]==$e[$vars[$i]]){
					 						echo "==>".$vars[$i]." IGUALES.<br/>";
					 					}
					 				}
					 				else{
					 					if($values[$vars[$i]]!=$e[$vars[$i]]){
					 						echo "==>".$vars[$i]." DIFERENTES.<br/>";
					 					}
					 				}
					 			}
					 		}catch(Exception $e){}
				 		}

	                 }
			$values=null;
			$values=array();
				 }
echo "</div>";
			}
		}
}
}
?>

<html>
<head>
	<style type="text/css">.left{width: 22%;margin: 20px; background-color: #c4c4c4;}</style>
</head>
<body>
<?php 
get_mongo_info(mongo_connect());
?>
</body>
</html>