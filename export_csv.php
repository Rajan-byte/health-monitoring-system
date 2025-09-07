<?php
require 'config.php';

$filename = "readings_export_" . date('Ymd_His') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$filename);

$out = fopen('php://output', 'w');
fputcsv($out, ['id','user','recorded_at','systolic','diastolic','heart_rate','glucose','weight','notes']);

$stmt = $pdo->query("SELECT r.*, CONCAT(u.first_name,' ',u.last_name) as user FROM readings r JOIN users u ON r.user_id = u.id ORDER BY r.recorded_at DESC");
while($row = $stmt->fetch()){
    fputcsv($out, [
      $row['id'],
      $row['user'],
      $row['recorded_at'],
      $row['systolic'],
      $row['diastolic'],
      $row['heart_rate'],
      $row['glucose'],
      $row['weight'],
      $row['notes']
    ]);
}
fclose($out);
exit;
