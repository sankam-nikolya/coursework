<?php
class NeuralNetwork
{

	/**
	 * Изменение размеров изображения
	 *
	 * @param  string $infile    Файл
	 * @param  int $neww 		 Новая ширина изображения
	 * @param  int $newh 		 Новая высота изображения
	 * @param  int $quality 	 Качество изображения
	 *
	 * @return object             Объект изображения
 	*/

    public function imageresize($infile,$neww,$newh,$quality) {
        $im=imagecreatefromjpeg($infile);
        $im1=imagecreatetruecolor($neww,$newh);
        imagecopyresampled($im1,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
        imagejpeg($im1,$infile,$quality);
        return $im1;
    }

	/**
	 * Инициализация фигуры
	 *
	 * @param  string $name    		Название фигуры
	 * @param  string $dir 		 	Папка для обучения
	 * @param  array &$tonn 		Указатель на массив
	 *
	 * @return array             	Готовый массив для обучения сети
 	*/

    public function addFigure($name, $dir, &$tonn)
    {
        $j = 0;
        $d = dir($dir);
        while($entry = $d->read()) 
        {
            if ( preg_match("/jpg/", $entry) )
            {
                $im = $this->imageresize($dir . $entry,16,16,75);
                imagefilter($im, IMG_FILTER_GRAYSCALE);
                $cur_array = array();
                $cnt = 0;
                for($y=0; $y<16; $y++)
                {
                    for($x=0; $x < 16; $x++)
                    {
                        $rgb = imagecolorat($im, $x, $y) / 16777215;
                        $rgb = round($rgb, 6);
                        $cur_array[$cnt] = $rgb;
                        $cnt++;
                    }
                }
                imagedestroy($im);
                $tonn[$name][$j] = $cur_array;
                $j++;
            }
        }
        return $tonn;
    }

	/**
	 * Подготовка изображения к проверке
	 *
	 * @param  string $img    		Ссылка на изображение
	 *
	 * @return array             	Массив данных для нейронной сети
 	*/

    public function input($img)
    {
        $Input = array();
        $im = $this->imageresize($img,16,16,75);
        imagefilter($im, IMG_FILTER_GRAYSCALE);
        $cnt = 0;
        for($y=0; $y<16; $y++)
        {
            $count = 0;
            for($x=0; $x < 16; $x++)
            {
                $rgb = imagecolorat($im, $x, $y) / 255;
                $rgb = round($rgb, 6);
                $Input[$cnt] = $rgb;
                $cnt++;
            }
        }
        $count = 0;

        imagedestroy($im);
        return $Input;
    }

	/**
	 * Обучение нейронной сети
	 *
	 * @param  string $tonn    		Массив данных для обучения сети
	 *
	 * @return string             	Строка с путем к файлу нейронной сети
 	*/

    public function teach($tonn)
    {
        $num = count($tonn);
        $ann = fann_create_standard_array(3, array(256, 128, $num));
        for ($i=0; $i < 10000; $i++) {
            $j= 0;
            $arout = array();
            foreach ($tonn as $key => $value) {
                for($k = 0; $k<count($tonn);$k++){
                    if($k==$j){
                        $arout[$key][$k] = 1;
                    }else{
                        $arout[$key][$k] = 0;
                    }
                }
                $j++;
                for ($s=0; $s < count($tonn[$key]); $s++) { 
                    fann_train($ann, $tonn[$key][$s], $arout[$key]);
                }
            }

        }
        fann_save($ann,"base/base.ann");
        return "base/base.ann";
        fann_destroy($ann);
    }

	/**
	 * Распознование образов
	 *
	 * @param  string $file    		Ссылка к файлу Нейронных Сетей
	 * @param  string $img    		Ссылка на изображение
	 *
	 * @return array             	Массив результатов
 	*/

    public function think($file, $img)
    {
        $ann = fann_create_from_file($file);
        $output = fann_run($ann, $img);
        return $output;
    }

	/**
	 * Обработка результата
	 *
	 * @param  array $output    	Массив результатов
	 *
	 * @return array             	Массив результатов
 	*/

    public function showResult($output)
    {
        foreach($output as $k => $v)
        { 
            if( ! is_numeric($v)) return array();
            
            if( ! isset($max))
            {
                $max = $v;
                continue;
            }
            
            if($v > $max) $max = $v;
        }
        
        $arr = array();
        foreach($output as $k => $v)
        { 
            if($v == $max) $arr[] = $k;
        }
        
        return $arr;
    }
}
?>
