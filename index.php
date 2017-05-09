<?php
error_reporting( E_ALL );
define ( 'ROOT_DIR', dirname ( __FILE__ ) );

include "core.php";
$nn = new NeuralNetwork;

/* Обучение нейронных сетей */
if(isset($_GET['teach'])){
	$tonn = array();
    $nn->addFigure("triangle", "teach/triangle/", $tonn);
    $nn->addFigure("circle", "teach/circle/", $tonn);
    $nn->addFigure("square", "teach/square/", $tonn);
    $ann = $nn->teach($tonn);
}

echo "template/index.html";


?>
