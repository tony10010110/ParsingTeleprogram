<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 10.01.18
 * Time: 12:05
 */


$file = 'test.csv';

$test = parsingFileCSV($file);
print_r($test);


function parsingFileCSV($file)
{
    $teleprogram_arr = [];
    $row = 1;
    if (($teleprogram_file = fopen($file, 'r')) !== false) { //перевірка підключення файлу

        while (($data = fgetcsv($teleprogram_file)) !== false) { //перебір всіх рядків файлу

//            if (validationTeleprogram($data) == false){
//                die("\n THIS ERROR, BITCH \n");
//            }

            if (is_string($res = validationTeleprogram($data))){
                die("\n ERROR: \n $res");
            }

            $teleprogram_arr[$row] =
                [
                    'day' => dayTeleprogram($data[0], $data[1]),
                    'date' => $data[0],
                    'time' => $data[1],
                    'name' => $data[2]
                ];

            $row++;
        }
    }

    return $teleprogram_arr;
}

function validationTeleprogram($teleprogram){
    $count = count($teleprogram);

    //перевірка на кількість стовпців
    if ($count != 3){
//        return false;
        return 'кількість стовпців не є допустима!';
    }

    //перевірка на правильність дати yyyy-mm-dd
    if (!(validDate($teleprogram[0]))){
//        return false;
        return 'не вірний формат дати формат дата';
    }

    //перевірка на правильність часу hh:mm:ss

    if (!(validTime($teleprogram[1]))){
//        return false;
        return 'не вірний формат часу';
    }

    return true;
}

function validDate($date){
    $data_arr = explode('-', $date);

    //0 - рік, 1 - місяць, 2 - день
    $result = checkdate($data_arr[1], $data_arr[2], $data_arr[0]);

    return $result;
}

function validTime($time){
    $time_arr = explode(':', $time);

    //0 - години, 1 - хвилини, 2 - секунди
    $result = checktime($time_arr[0], $time_arr[1], $time_arr[2]);

    return $result;
}

//як checkdate() тільки для часу:)
function checktime($hour, $min, $sec) {
    if ($hour < 0 || $hour > 23 || !is_numeric($hour)) {
        return false;
    }
    if ($min < 0 || $min > 59 || !is_numeric($min)) {
        return false;
    }
    if ($sec < 0 || $sec > 59 || !is_numeric($sec)) {
        return false;
    }
    return true;
}

function dayTeleprogram($date, $time){

    //кінець для дня програми
    $end_time = new DateTime('5:30:30');

    $curr_time = new DateTime($time);
    $curr_date = new DateTime($date);

    if ($curr_time > $end_time){
        $day = $curr_date->format('D');
    } else {
        $curr_date->add(new DateInterval('P1D'));
        $day = $curr_date->format('D');
    }

    return $day;
}
