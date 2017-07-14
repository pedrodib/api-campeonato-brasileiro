<?php 
require "championship.php";

$championship = new Championship();


header('Content-Type: application/json');
echo json_encode($championship->getClassification());
