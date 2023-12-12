<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

$modelSheet = __DIR__ . '/model.xlsx';

$meses = [
  "01" => ["abreviado" => "jan", "completo" => "Janeiro"],
  "02" => ["abreviado" => "fev", "completo" => "Fevereiro"],
  "03" => ["abreviado" => "mar", "completo" => "Março"],
  "04" => ["abreviado" => "abr", "completo" => "Abril"],
  "05" => ["abreviado" => "mai", "completo" => "Maio"],
  "06" => ["abreviado" => "jun", "completo" => "Junho"],
  "07" => ["abreviado" => "jul", "completo" => "Julho"],
  "08" => ["abreviado" => "ago", "completo" => "Agosto"],
  "09" => ["abreviado" => "set", "completo" => "Setembro"],
  "10" => ["abreviado" => "out", "completo" => "Outubro"],
  "11" => ["abreviado" => "nov", "completo" => "Novembro"],
  "12" => ["abreviado" => "dez", "completo" => "Dezembro"]
];

$weekDays = [
  0 => 'dom.',
  1 => 'seg.',
  2 => 'ter.',
  3 => 'qua.',
  4 => 'qui.',
  5 => 'sex.',
  6 => 'sab.'
];



$spreadsheet = IOFactory::load($modelSheet);
$spreadsheet->setActiveSheetIndex(0);
$worksheet = $spreadsheet->getActiveSheet();

$now = Carbon::now()->setMonth((int) $_POST['mes']);
$lastDayOfMonth = $now->clone()->endOfMonth()->format('d');

$worksheet->setCellValue('B1', 'Gerado por Ponto Fácil (v3.0.60) em ' . $now->format('d/m/Y H:i'))
  ->setCellValue('B3', '01/' . $meses[$now->format('m')]['abreviado'] . './' . $now->format('Y') . ' - ' .
    $lastDayOfMonth . '/' .
    $meses[$now->format('m')]['abreviado'] . './' . $now->format('Y'))
  ->setCellValue('B5', strtolower($meses[$now->format('m')]['completo']) . '/' . $now->format('Y'));

$index = 7;
$workedHours = 0;

for ($i = 1; $i <= $lastDayOfMonth; $i++) {
  if ($_POST['excluir-feriados'] === '1') {
    if ($_POST['mes'] === '10' && $i === 12) {
      // $worksheet->removeRow($index);
      // $worksheet->removeRow($index);
      // $worksheet->removeRow($index);
      // $worksheet->removeRow($index);
      // $worksheet->unmergeCells('C' . $index . ':D' . $index);
      // var_dump('B' . $index . ':B' . $index + 2);
      // $worksheet->mergeCells('B' . $index . ':B' . $index + 2);
      $index += 4;
      continue;
    }
  }

  $diffMinutesStartEnd = rand(0, 7);
  $diffBeforeAfterStartEnd = rand(0, 1);

  $diffMinutesLunch = rand(0, 7);
  $diffBeforeAfterLunch = rand(0, 1);

  $dayOfWeek = $now->clone()->day($i)->dayOfWeek;

  if ($dayOfWeek === 0 || $dayOfWeek === 6) {
    continue;
  }

  $enterHour = Carbon::createFromFormat('H:i', '09:00');
  $enterHour = $diffBeforeAfterStartEnd ? $enterHour->addMinutes($diffMinutesStartEnd) : $enterHour->subMinutes($diffMinutesStartEnd);

  $leaveHour = Carbon::createFromFormat('H:i', '18:00');
  $leaveHour = $diffBeforeAfterStartEnd ? $leaveHour->addMinutes($diffMinutesStartEnd) : $leaveHour->subMinutes($diffMinutesStartEnd);

  $enterLunchHour = Carbon::createFromFormat('H:i', '12:00');
  $enterLunchHour = $diffBeforeAfterLunch ? $enterLunchHour->addMinutes($diffMinutesLunch) : $enterLunchHour->subMinutes($diffMinutesLunch);

  $leaveLunchHour = Carbon::createFromFormat('H:i', '13:00');
  $leaveLunchHour = $diffBeforeAfterLunch ? $leaveLunchHour->addMinutes($diffMinutesLunch) : $leaveLunchHour->subMinutes($diffMinutesLunch);

  $workedHours += 8;

  $worksheet->setCellValue('B' . $index, $weekDays[$dayOfWeek] . ', ' . str_pad($i, 2, "0", STR_PAD_LEFT) . ' ' . $meses[$now->format('m')]['abreviado'] . '. ' . $now->format('Y'))
    ->setCellValue('C' . $index, $enterHour->format('H:i'))
    ->setCellValue('D' . $index, $enterLunchHour->format('H:i'))
    ->setCellValue('C' . ($index + 1), $leaveLunchHour->format('H:i'))
    ->setCellValue('D' . ($index + 1), $leaveHour->format('H:i'))
    ->setCellValue('F' . ($index + 2), str_pad($workedHours, 2, "0", STR_PAD_LEFT) . 'h 00m');

  $index += 4;
}

for ($i = $index; $i < $index + 20; $i++) {
  $worksheet->removeRow($index);
}

$filename = '01 ' . $meses[$now->format('m')]['abreviado'] . '. ' . $now->format('Y') .
  ' - ' . $lastDayOfMonth . ' ' . $meses[$now->format('m')]['abreviado'] . '. ' . $now->format('Y');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
