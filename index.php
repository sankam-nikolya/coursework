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

include "template/index.html";

if(isset($_POST['checkfigure'])){
    $uploaddir = 'uploads/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);

    copy("uploads/".$_FILES['userfile']['name'], "thumbs/".$_FILES['userfile']['name']);

    $Input = $nn->input("thumbs/".$_FILES['userfile']['name']);

    $output = $nn->think("base/base.ann", $Input);
    $result = $nn->showResult($output);

    switch ($result[0]) {
        case '0':
            $answer = "Треугольник";
            break;
        case '1':
            $answer = "Круг";
            break;
        case '2':
            $answer = "Квадрат";
            break;
        default:
            $answer = "неизвестно";
            break;
    }
}
?>
