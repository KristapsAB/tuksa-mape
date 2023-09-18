<?php
require_once 'DataDisplay.php';

$dataDisplay = new DataDisplay();

if (isset($_POST['data'])) {
    $selectedData = $_POST['data'];
    $dataDisplay->getData($selectedData);
}

$dataDisplay->closeConnection();
?>
