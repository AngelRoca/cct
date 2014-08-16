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
	"turno",
	"num_grupos",
	"num_alumnos",
	"num_personal",
	"cct",
	"id_turno",// La division la tendremos aqui <6
	"cct_escuelas",
	"edo_en_mapa",
	"persona_responsable",
	"nombre_en_mapa",
	"edo",
	"cp",
	"tipo",
	"fecha",
	"dato4",
	"dato3",
	"dato2",
	"dato1",
	"coord1",
	"coord2",
	"nombre",
	"telefono",
	"localidad_en_mapa",
	"status",
	"nivel",
	"municipio_en_mapa",
	"rezago_social",
	"numero_dir",
	"municipio",
	"calle"
	);

		while (($data=fgetcsv($fp))!==false) {
			$values=array();
			foreach ($data as $cct) {
				$aux = $c->find(array('cct_escuelas'=>$cct));
				$turnos="";
				 if(count($aux)>0){
				 	foreach($aux as $e) {
				 		echo "<pre>";
				 		//var_dump($e);
				 		echo "</pre>";
				 		$flag=false;
				 		$salida="";
				 		$turnos.=$e["turno"]."<br/>";
				 		for($i=0;$i<count($vars);$i++){
				 				if(!isset($values[$vars[$i]])){
				 					$values[$vars[$i]]=$e[$vars[$i]];
				 				}else{
					 				if($i<6){
					 					if($values[$vars[$i]]==$e[$vars[$i]]){
					 						$flag=true;
					 						$salida.="==>".$vars[$i]." IGUALES.<br/>";
					 					}
					 				}
					 				else{
					 					if($values[$vars[$i]]!=$e[$vars[$i]]){
					 						$flag=true;
					 						$salida.="==>".$vars[$i]." DIFERENTES.<br/>";
					 					}
					 				}
					 			}
				 		}
				 		if($flag==true){
				 			echo "<div class='left'>";
					 				echo $cct."<br/>";
					 				echo $turnos;
					 				echo $salida;
					 		echo "</div>";
					 		$turnos="";
					 			}
	                 }
			$values=null;
			$values=array();
				 }
			}
		}
}
}
?>

<html>
<head>
	<style type="text/css">.left{width: 30%;margin: 20px; background-color: #c4c4c4;}</style>
</head>
<body>
<?php 
get_mongo_info(mongo_connect());
?>
</body>
</html>