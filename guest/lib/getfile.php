<?php
define('_CONTROL', 1);
include "db.php";
include '../PHPExcel-1.8/Classes/PHPExcel.php';

session_start();
if (empty($_SESSION["admin"])) {
    header('Location: /');
}


class Chan extends Db
{
    public function mySqli()
    {
        $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        $res = $db->query("SELECT `id`, `name`, `msg`, `note`, `datetime`, `filename` FROM `msgs`");
        $query1 = $res->fetch_all();
        return $query1;
    }
}

$a = new Chan();
$row = $a->mySqli();


$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$rowCount = 1;
$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'id');
$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'name');
$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'msg');
$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'note');
$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'date');
$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'file');

foreach ($row as $rowCount => $val) {
    $rowCount+=2;
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val['0']);
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val['1']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val['2']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val['3']);
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val['4']);
    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val['5']);
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=msg.xlsx");
header("Content-Transfer-Encoding: binary ");
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setOffice2003Compatibility(true);
$objWriter->save('php://output');