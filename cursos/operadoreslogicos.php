 <?php 
 
 $x = 5;
 $y = "5";


 ///importante cuando son 3 signos tiene que ser el mismo si no 
// php convierte el valor a numero
 var_dump($x !== $y);

 //operadores de incremento y decremento  
 //primero toma el valor despues hace la operacion 
 echo $x++;

 //primero hace la operacion y despues la recupera 
 echo ++$x;

 ?>