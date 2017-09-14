<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$result = file_get_contents("./bike.txt");

    $options = array('resultado' => iconv("iso-8859-1","utf-8",$result));
    $json = json_encode($options);
    echo $json;          