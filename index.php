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
    if($_FILES['getimage']['type']=="image/jpeg"){
        $uploaddir = 'uploads/';
        $uploadfile = $uploaddir . basename($_FILES['getimage']['name']);
        move_uploaded_file($_FILES['getimage']['tmp_name'], $uploadfile);
        copy("uploads/".$_FILES['getimage']['name'], "thumbs/".$_FILES['getimage']['name']);
        $Input = $nn->input("thumbs/".$_FILES['getimage']['name']);
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
    }else{
        $answer = "К загрузке разрешены только jpg изображения";
    }
   echo <<<JSCRIPT
    <br>
<script>
$(".result").html('<span class="text">Вероятно, на изображении </span><span class="answer">$answer</span>');
</script>
JSCRIPT;
}
?>
