<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekapExportController extends Controller
{
    public function pdf(Request $request)
    {
        $table = $this->tableForSelectedSubBidang($request);
        $subBidang = $request->query('sub_bidang');
        $html = '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><style>
            @page { size: A4 landscape; margin: 12mm; }
            body { font-family: sans-serif; font-size: 9px; color: #1f2937; }
            h2 { text-align: center; font-size: 14px; margin: 0 0 4px; }
            p { text-align: center; margin: 0 0 12px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #94a3b8; padding: 4px; vertical-align: top; }
            th { background: #e2e8f0; text-align: center; }
        </style></head><body><h2>Rekapitulasi Data Konservasi</h2><p>'
            . e($subBidang) . ' | Dicetak ' . now()->format('d-m-Y H:i')
            . '</p>' . $table . '</body></html>';

        return Pdf::loadHTML($html)->setPaper('a4', 'landscape')->download(
            'Rekap_' . str_replace('.', '-', $subBidang) . '_' . date('Y-m-d') . '.pdf'
        );
    }

    public function excel(Request $request)
    {
        $table = $this->tableForSelectedSubBidang($request);
        $subBidang = $request->query('sub_bidang');
        $html = '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><style>
            table { border-collapse: collapse; }
            th, td { border: 1px solid #94a3b8; padding: 5px; }
            th { background: #e2e8f0; }
        </style></head><body><h2>Rekapitulasi Sub-Bidang ' . e($subBidang)
            . '</h2>' . $table . '</body></html>';
        $filename = 'Rekap_' . str_replace('.', '-', $subBidang) . '_' . date('Y-m-d') . '.xls';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function tableForSelectedSubBidang(Request $request): string
    {
        $request->validate([
            'sub_bidang' => ['required', 'string', 'regex:/^[A-F]\.\d{2}$/'],
        ]);

        $request->query->remove('search');
        $request->query->set('export_all', '1');
        $html = app(KonservasiController::class)->index($request)->render();

        $previousErrorMode = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorMode);

        $xpath = new \DOMXPath($document);
        $table = $xpath->query('//main//table[1]')->item(0);
        abort_unless($table, 500, 'Tabel rekapitulasi tidak ditemukan.');

        foreach ($xpath->query('.//tr', $table) as $row) {
            foreach (iterator_to_array($row->childNodes) as $cell) {
                if ($cell instanceof \DOMElement && strtolower(trim($cell->textContent)) === 'aksi') {
                    $row->removeChild($cell);
                }
            }

            $cells = [];
            foreach ($row->childNodes as $cell) {
                if ($cell instanceof \DOMElement && in_array(strtolower($cell->tagName), ['td', 'th'], true)) {
                    $cells[] = $cell;
                }
            }

            $lastCell = $cells[array_key_last($cells)] ?? null;
            if (count($cells) > 1 && $lastCell instanceof \DOMElement && strtolower($lastCell->tagName) === 'td') {
                $row->removeChild($lastCell);
            }
        }

        return $document->saveHTML($table);
    }
}