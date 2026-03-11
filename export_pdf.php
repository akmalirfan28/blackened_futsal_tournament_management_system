<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

ob_start();
include 'report.php';
include "connection.php";
$tournamentID = $_SESSION['tournamentID'];

// Capture the HTML output of report.php

$html = ob_get_clean();

// Remove the export buttons from the PDF
$html = preg_replace('/<div class=\"btn-group\">.*?<\/div>/s', '', $html);
$html = preg_replace('/<aside class=\"sidebar\">.*?<\/aside>/s', '', $html);
$html = preg_replace('/<div class=\"container\">.*?<\/div>/s', '', $html);

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('tournament_report.pdf', ['Attachment' => true]);
exit;