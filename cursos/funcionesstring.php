<?php

echo "Estos son funciones para los strings PHP";

echo "<br>";

echo "La palabra con string va ser:";

echo "<br>";
echo "hoy voy a aprender mucho";
echo "<br>";
$mensaje =  "hoy voy a aprender mucho";


//lingitud de la palabra
echo strlen($mensaje);

echo "<br>";

//numero de palabras
echo str_word_count($mensaje);

echo "<br>";

//Reversa del texto

echo strrev($mensaje);

echo "<br>";

//encontrar un texto

echo strpos($mensaje, "aprender");

echo "<br>";

//converir a minisculas
echo strtolower($mensaje);

echo "<br>";

///convetir a mayusculas

echo strtoupper($mensaje);

echo "<br>";

///comparar por peso de bytes

echo strcmp("a", "a"); // resultado da 0

echo "<br>";

///substraer apatir de la posicion en adelanmte ose 10 ====> hasta el final

echo substr($mensaje, 10);

echo "<br>";


///substraer apatir de la posicion en adelanmte ose 10 ====> y de ahi 7 numeros extraere
echo substr($mensaje, 10,7);

echo "<br>";


//Remover espacios enm blanco de izquierda a derecha y entre el medio solo dejara uno

echo trim('   hola      mundo   ');



?>