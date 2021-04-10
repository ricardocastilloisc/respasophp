
<?php

//esto es un array asociado ana llavve es como json aunque la representacion es diferente 

//importante cuando no se quiera asociar solo es array normal

$edades = array("Marcos" => 34, "Tania" => 23, "Omar" => 27);

print_r($edades);

echo "<br>";

echo $edades['Tania'];

echo "<br>";


//key va ser la llave o el trabuto y el value es el valor
foreach($edades as $key => $value){
    // el punto es para concatenar
    echo "La key de  [". $key . "] tiene el valor de " . $value . "<br>";
}


?>
