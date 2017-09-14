<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$linha = $_GET["checado"];
file_put_contents("./bike.txt", $linha);

$result = 1;

    $options = array('resultado' => iconv("iso-8859-1","utf-8",$result));
    $json = json_encode($options);
    echo $json;          