<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/fpdf/fpdf.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/** CSV einlesen **/
function parse_csv(string $filename): array {
    $rows = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);
    }
    return $rows;
}

/** Datum parsen **/
function parse_date(string $str): ?DateTime {
    $parts = explode('.', $str);
    return count($parts) === 3
        ? DateTime::createFromFormat('d.m.Y', $str)
        : null;
}

/** Ohrmarke-Name aufsplitten **/
function teile_ohrmarke_und_name(string $wert): array {
    $teile = explode(' - ', $wert, 2);
    return [
        'Ohrmarke' => $teile[0] ?? '',
        'Name'     => $teile[1] ?? ''
    ];
}

/** Excel-Export **/
function create_excel(array $rows, int $omi, int $gdi, int $ab_v, int $ab_b, int $jg_v, int $jg_b, int $max_m, string $datum): string {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $headers = $rows[0];
    array_shift($headers);
    array_splice($headers, $omi, 1, ['Ohrmarke','Name']);
    $headers = array_merge($headers, ['Absetzfenster','Jahresfenster','Gewicht']);
    $sheet->fromArray($headers, NULL, 'A1');

    // Auto-size aller Spalten
    foreach (range('A', chr(64 + count($headers))) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $heute = new DateTime();
    $now   = time();
    $zeile = 2;

    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        array_shift($row);
        $geb = parse_date($row[$gdi]);
        if (!$geb) continue;

        // Alter in Monaten
        $diff  = $heute->diff($geb);
        $alter = $diff->y * 12 + $diff->m + round($diff->d / 30, 2);
        if ($alter > $max_m) continue;

        // Ohrmarke / Name aufsplitten
        $teile = teile_ohrmarke_und_name($row[$omi]);
        array_splice($row, $omi, 1, [$teile['Ohrmarke'], $teile['Name']]);

        // Zeitfenster berechnen
        $ab_von_d  = (clone $geb)->modify("+{$ab_v} days");
        $ab_bis_d  = (clone $geb)->modify("+{$ab_b} days");
        $jg_von_d  = (clone $geb)->modify("+{$jg_v} days");
        $jg_bis_d  = (clone $geb)->modify("+{$jg_b} days");
        $ab_f      = $ab_von_d->format('d.m.Y') . ' bis ' . $ab_bis_d->format('d.m.Y');
        $jg_f      = $jg_von_d->format('d.m.Y') . ' bis ' . $jg_bis_d->format('d.m.Y');

        $row = array_merge($row, [$ab_f, $jg_f, '']);

        // Zeile in Excel schreiben
        $sheet->fromArray($row, NULL, "A{$zeile}");

        // Rahmenlinien
        $sheet->getStyle("A{$zeile}:" . chr(64 + count($row)) . $zeile)
              ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Farb-Logik
        $ab_b_ts = $ab_bis_d->getTimestamp();
        $jg_b_ts = $jg_bis_d->getTimestamp();
        $d3m     = strtotime('-3 months');
        $col     = [255,255,255];
        if ($ab_b_ts > $d3m || $jg_b_ts > $d3m) {
            $ab_s = $ab_von_d->getTimestamp();
            $jg_s = $jg_von_d->getTimestamp();
            if ($ab_s - $now <= 14*86400 || $jg_s - $now <= 14*86400) {
                $col = [255,0,0];
            } elseif ($ab_s - $now <= 60*86400 || $jg_s - $now <= 60*86400) {
                $col = [255,165,0];
            }
        }
        $sheet->getStyle("A{$zeile}:" . chr(64 + count($row)) . $zeile)
              ->getFill()->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setRGB(sprintf('%02X%02X%02X', $col[0], $col[1], $col[2]));

        $zeile++;
    }

    $file = sys_get_temp_dir() . "/wiegung_" . date('Y-m-d', strtotime($datum)) . ".xlsx";
    (new Xlsx($spreadsheet))->save($file);
    return $file;
}

/** PDF-Export im Querformat (Landscape), ohne Spalte Rasse(n) **/
function create_pdf(array $rows, int $omi, int $gdi, string $datum, int $ab_v, int $ab_b, int $jg_v, int $jg_b, int $max_m): string {
    $pdf = new FPDF('L','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial');

    // Header ohne Rasse(n)
    $hdr = ['Ohrmarke','Name','Geb.dat.','Geschl.','Alter','Absetzfenster','Jahresfenster','Gewicht'];
    $w   = [38,40,28,18,17,54,54,20];

    foreach ($hdr as $i => $h) {
        $pdf->Cell($w[$i], 9, utf8_decode($h), 1, 0, 'C');
    }
    $pdf->Ln();

    $heute = new DateTime();
    $now   = time();

    for ($i = 1; $i < count($rows); $i++) {
        $r = $rows[$i];
        array_shift($r);
        $geb = parse_date($r[$gdi]);
        if (!$geb) continue;

        // Alter in Monaten
        $diff  = $heute->diff($geb);
        $alter = $diff->y * 12 + $diff->m + round($diff->d / 30, 2);
        if ($alter > $max_m) continue;

        // Ohrmarke/Name
        $t     = teile_ohrmarke_und_name($r[$omi]);
        $gesch = $r[$gdi+1] ?? '';

        // Zeitfenster
        $ab_v_d = (clone $geb)->modify("+{$ab_v} days");
        $ab_b_d = (clone $geb)->modify("+{$ab_b} days");
        $jg_v_d = (clone $geb)->modify("+{$jg_v} days");
        $jg_b_d = (clone $geb)->modify("+{$jg_b} days");
        $ab_f   = $ab_v_d->format('d.m.Y') . ' bis ' . $ab_b_d->format('d.m.Y');
        $jg_f   = $jg_v_d->format('d.m.Y') . ' bis ' . $jg_b_d->format('d.m.Y');

        // Farb-Logik
        $d3m = strtotime('-3 months');
        $col = [255,255,255];
        if ($ab_b_d->getTimestamp() > $d3m || $jg_b_d->getTimestamp() > $d3m) {
            $ab_s = $ab_v_d->getTimestamp();
            $jg_s = $jg_v_d->getTimestamp();
            if ($ab_s - $now <= 14*86400 || $jg_s - $now <= 14*86400) {
                $col = [255,0,0];
            } elseif ($ab_s - $now <= 60*86400 || $jg_s - $now <= 60*86400) {
                $col = [255,165,0];
            }
        }
        $pdf->SetFillColor($col[0], $col[1], $col[2]);

        // Zeilendaten ohne Rasse
        $data = [
            $t['Ohrmarke'],
            $t['Name'],
            $geb->format('d.m.Y'),
            utf8_decode($gesch),
            number_format($alter, 2, ',', ''),
            utf8_decode($ab_f),
            utf8_decode($jg_f),
            ''
        ];

        foreach ($data as $j => $cell) {
            $pdf->Cell($w[$j], 9, $cell, 1, 0, 'L', true);
        }
        $pdf->Ln();
    }

    $file = sys_get_temp_dir() . "/wiegung_" . date('Y-m-d', strtotime($datum)) . ".pdf";
    $pdf->Output('F', $file);
    return $file;
}

/** Haupt-Handler **/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_datei'])) {
    $tmp   = $_FILES['csv_datei']['tmp_name'];
    $rows  = parse_csv($tmp);
    $hdr   = $rows[0]; array_shift($hdr);
    $omi   = array_search('Ohrmarke-Name', $hdr);
    $gdi   = array_search('Geburtsdatum',   $hdr);
    $ab_v  = intval($_POST['absetz_von']);
    $ab_b  = intval($_POST['absetz_bis']);
    $jg_v  = intval($_POST['jahres_von']);
    $jg_b  = intval($_POST['jahres_bis']);
    $max_m = intval($_POST['max_alter']);
    $datum = $_POST['wiegung_datum'];
    $outX  = isset($_POST['output_excel']);
    $outP  = isset($_POST['output_pdf']);
    $files = [];

    if ($outX) {
        $files[] = create_excel($rows, $omi, $gdi, $ab_v, $ab_b, $jg_v, $jg_b, $max_m, $datum);
    }
    if ($outP) {
        $files[] = create_pdf(  $rows, $omi, $gdi, $datum, $ab_v, $ab_b, $jg_v, $jg_b, $max_m);
    }

    if (count($files) === 1) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($files[0]).'"');
        readfile($files[0]);
        unlink($files[0]);
        exit;
    } elseif (count($files) > 1) {
        $zip  = sys_get_temp_dir()."/wiegefenster_output_".date('Y-m-d', strtotime($datum)).".zip";
        $zipA = new ZipArchive();
        $zipA->open($zip, ZipArchive::CREATE);
        foreach ($files as $f) {
            $zipA->addFile($f, basename($f));
        }
        $zipA->close();
        foreach ($files as $f) {
            unlink($f);
        }
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zip).'";');
        readfile($zip);
        unlink($zip);
        exit;
    } else {
        echo "Keine Ausgabeformate ausgewählt.";
    }
}
?>
