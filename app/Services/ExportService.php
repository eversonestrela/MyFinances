<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Service para geração de PDF e Excel dos relatórios financeiros
 */
class ExportService
{
    private static array $MESES = [
        1  => 'Janeiro',  2  => 'Fevereiro', 3  => 'Março',    4  => 'Abril',
        5  => 'Maio',     6  => 'Junho',     7  => 'Julho',     8  => 'Agosto',
        9  => 'Setembro', 10 => 'Outubro',   11 => 'Novembro', 12 => 'Dezembro',
    ];

    // =========================================================
    // PDF — gerado via dompdf
    // =========================================================

    /**
     * Gera e faz streaming do PDF para o browser.
     * Chame este método a partir do Controller.
     *
     * @param array $dados  Dados montados pelo RelatorioService
     * @param int   $mes
     * @param int   $ano
     * @param string $nomeUsuario
     */
    public function gerarPdf(array $dados, int $mes, int $ano, string $nomeUsuario): void
    {
        $html = $this->buildHtmlPdf($dados, $mes, $ano, $nomeUsuario);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nomeMes  = self::$MESES[$mes] ?? $mes;
        $filename = "relatorio_{$nomeMes}_{$ano}.pdf";

        $dompdf->stream($filename, ['Attachment' => true]);
    }

    // =========================================================
    // EXCEL — gerado via PhpSpreadsheet
    // =========================================================

    /**
     * Gera e faz streaming do arquivo .xlsx para o browser.
     */
    public function gerarExcel(array $dados, int $mes, int $ano, string $nomeUsuario): void
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('MyFinances')
            ->setTitle("Relatório {$mes}/{$ano}")
            ->setDescription("Relatório financeiro gerado pelo MyFinances");

        // ---- Aba 1: Resumo ----
        $resumo = $spreadsheet->getActiveSheet();
        $resumo->setTitle('Resumo');
        $this->buildAbaResumo($resumo, $dados['resumo'], $mes, $ano, $nomeUsuario);

        // ---- Aba 2: Receitas ----
        $shReceitas = $spreadsheet->createSheet();
        $shReceitas->setTitle('Receitas');
        $this->buildAbaLista(
            $shReceitas,
            $dados['receitas'],
            ['Descrição', 'Tipo', 'Data', 'Valor (R$)'],
            function (array $r) {
                return [
                    $r['descricao'],
                    $r['tipo_receita'] === 'recorrente' ? 'Recorrente' : 'Única',
                    date('d/m/Y', strtotime($r['data_recebimento'])),
                    (float) $r['valor'],
                ];
            },
            '00FF99', // verde claro
            'Receitas do Mês'
        );

        // ---- Aba 3: Despesas ----
        $shDesp = $spreadsheet->createSheet();
        $shDesp->setTitle('Despesas');
        $this->buildAbaLista(
            $shDesp,
            $dados['despesas'],
            ['Descrição', 'Categoria', 'Valor (R$)', 'Status'],
            function (array $r) {
                return [
                    $r['descricao'],
                    $r['categoria'],
                    (float) $r['valor'],
                    $r['status_pago'] ? 'Pago' : 'Pendente',
                ];
            },
            'FFCCCC', // vermelho claro
            'Despesas (Parcelas) do Mês'
        );

        // ---- Aba 4: Dívidas ----
        $shDiv = $spreadsheet->createSheet();
        $shDiv->setTitle('Dívidas');
        $this->buildAbaLista(
            $shDiv,
            $dados['dividas'],
            ['Descrição', 'Categoria', 'Valor (R$)'],
            function (array $r) {
                return [
                    $r['descricao'],
                    $r['categoria'],
                    (float) $r['valor'],
                ];
            },
            'FFE5B4', // laranja claro
            'Dívidas do Mês'
        );

        // ---- Aba 5: Evolução Anual ----
        $shEv = $spreadsheet->createSheet();
        $shEv->setTitle('Evolução Anual');
        $this->buildAbaEvolucao($shEv, $dados['evolucao']);

        $spreadsheet->setActiveSheetIndex(0);

        // Output
        $nomeMes  = self::$MESES[$mes] ?? $mes;
        $filename = "relatorio_{$nomeMes}_{$ano}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    // =========================================================
    // PRIVATE — construtores de HTML do PDF
    // =========================================================

    private function buildHtmlPdf(array $dados, int $mes, int $ano, string $nomeUsuario): string
    {
        $nomeMes  = self::$MESES[$mes] ?? $mes;
        $resumo   = $dados['resumo'];
        $gerado   = date('d/m/Y \à\s H:i');

        $fmt = fn(float $v) => 'R$ ' . number_format($v, 2, ',', '.');
        $saldoCor = $resumo['saldo'] >= 0 ? '#27ae60' : '#e74c3c';

        // Tabela de receitas
        $tbReceitas = $this->htmlTabela(
            ['Descrição', 'Tipo', 'Data', 'Valor'],
            $dados['receitas'],
            fn($r) => [
                htmlspecialchars($r['descricao']),
                $r['tipo_receita'] === 'recorrente' ? 'Recorrente' : 'Única',
                date('d/m/Y', strtotime($r['data_recebimento'])),
                $fmt((float)$r['valor']),
            ]
        );

        // Tabela de despesas
        $tbDespesas = $this->htmlTabela(
            ['Descrição', 'Categoria', 'Valor', 'Status'],
            $dados['despesas'],
            fn($r) => [
                htmlspecialchars($r['descricao']),
                htmlspecialchars($r['categoria']),
                $fmt((float)$r['valor']),
                $r['status_pago'] ? '<span style="color:#27ae60">Pago</span>' : '<span style="color:#e74c3c">Pendente</span>',
            ]
        );

        // Tabela de dívidas
        $tbDividas = $this->htmlTabela(
            ['Descrição', 'Categoria', 'Valor'],
            $dados['dividas'],
            fn($r) => [
                htmlspecialchars($r['descricao']),
                htmlspecialchars($r['categoria']),
                $fmt((float)$r['valor']),
            ]
        );

        // Tabela categorias despesas
        $tbCatDesp = $this->htmlTabela(
            ['Categoria', 'Qtd.', 'Total'],
            $dados['cat_despesas'],
            fn($r) => [
                htmlspecialchars($r['categoria']),
                $r['qtd'],
                $fmt((float)$r['total']),
            ]
        );

        // Tabela categorias dívidas
        $tbCatDiv = $this->htmlTabela(
            ['Categoria', 'Qtd.', 'Total'],
            $dados['cat_dividas'],
            fn($r) => [
                htmlspecialchars($r['categoria']),
                $r['qtd'],
                $fmt((float)$r['total']),
            ]
        );

        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #333; }
  /* Header */
  .header { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; padding: 20px 30px; margin-bottom: 20px; }
  .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
  .header p  { font-size: 11px; opacity: .85; }
  .header .right { text-align: right; }
  /* Cards */
  .cards { display: flex; gap: 10px; margin: 0 30px 20px; }
  .card  { flex: 1; border-radius: 8px; padding: 12px 14px; text-align: center; }
  .card .label  { font-size: 9px; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; opacity: .8; }
  .card .value  { font-size: 15px; font-weight: 700; }
  .card-green   { background: #e8f8f0; color: #27ae60; }
  .card-red     { background: #fde8e8; color: #e74c3c; }
  .card-orange  { background: #fef3e2; color: #e67e22; }
  .card-blue    { background: #e8f0fe; color: #3b82f6; }
  /* Sections */
  .section       { margin: 0 30px 22px; }
  .section-title { font-size: 13px; font-weight: 700; border-left: 4px solid #667eea; padding-left: 8px; margin-bottom: 10px; }
  /* Tables */
  table { width: 100%; border-collapse: collapse; font-size: 10px; }
  th    { background: #667eea; color: #fff; padding: 6px 8px; text-align: left; font-weight: 600; }
  td    { padding: 5px 8px; border-bottom: 1px solid #eee; }
  tr:nth-child(even) td { background: #f8f9fa; }
  .empty { color: #aaa; font-style: italic; text-align: center; padding: 12px; }
  /* Footer */
  .footer { margin: 30px; padding-top: 10px; border-top: 1px solid #eee; font-size: 9px; color: #aaa; text-align: center; }
</style>
</head>
<body>

<div class="header">
  <table style="border:none;background:transparent;"><tr>
    <td style="border:none;background:transparent;color:#fff;padding:0;">
      <h1>&#128179; MyFinances</h1>
      <p>Relatório Financeiro — {$nomeMes} de {$ano}</p>
    </td>
    <td style="border:none;background:transparent;color:#fff;padding:0;text-align:right;">
      <p><strong>{$nomeUsuario}</strong></p>
      <p>Gerado em {$gerado}</p>
    </td>
  </tr></table>
</div>

<div class="cards">
  <div class="card card-green">
    <div class="label">Receitas</div>
    <div class="value">{$fmt($resumo['total_receitas'])}</div>
  </div>
  <div class="card card-red">
    <div class="label">Despesas</div>
    <div class="value">{$fmt($resumo['total_despesas'])}</div>
  </div>
  <div class="card card-orange">
    <div class="label">Dívidas</div>
    <div class="value">{$fmt($resumo['total_dividas'])}</div>
  </div>
  <div class="card card-blue">
    <div class="label">Saldo</div>
    <div class="value" style="color:{$saldoCor}">{$fmt($resumo['saldo'])}</div>
  </div>
</div>

<div class="section">
  <div class="section-title">Situação das Parcelas</div>
  <table>
    <tr>
      <th>Parcelas Pagas</th>
      <th>Parcelas Pendentes</th>
    </tr>
    <tr>
      <td style="color:#27ae60;font-weight:700">{$fmt($resumo['parcelas_pagas'])} ({$resumo['qtd_pago']} parcelas)</td>
      <td style="color:#e74c3c;font-weight:700">{$fmt($resumo['parcelas_pend'])} ({$resumo['qtd_pendente']} parcelas)</td>
    </tr>
  </table>
</div>

<div class="section">
  <div class="section-title">Receitas do Período</div>
  {$tbReceitas}
</div>

<div class="section">
  <div class="section-title">Despesas por Categoria</div>
  {$tbCatDesp}
</div>

<div class="section">
  <div class="section-title">Despesas Detalhadas</div>
  {$tbDespesas}
</div>

<div class="section">
  <div class="section-title">Dívidas por Categoria</div>
  {$tbCatDiv}
</div>

<div class="section">
  <div class="section-title">Dívidas Detalhadas</div>
  {$tbDividas}
</div>

<div class="footer">
  Documento gerado automaticamente pelo sistema MyFinances &bull; {$gerado}
</div>

</body>
</html>
HTML;
    }

    private function htmlTabela(array $headers, array $rows, callable $mapper): string
    {
        if (empty($rows)) {
            return '<table><tr><td class="empty" colspan="' . count($headers) . '">Nenhum registro no período.</td></tr></table>';
        }

        $th = implode('', array_map(fn($h) => "<th>{$h}</th>", $headers));
        $body = '';
        foreach ($rows as $row) {
            $cols = $mapper($row);
            $tds  = implode('', array_map(fn($c) => "<td>{$c}</td>", $cols));
            $body .= "<tr>{$tds}</tr>";
        }

        return "<table><thead><tr>{$th}</tr></thead><tbody>{$body}</tbody></table>";
    }

    // =========================================================
    // PRIVATE — construtores de abas do Excel
    // =========================================================

    private function buildAbaResumo(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws, array $resumo, int $mes, int $ano, string $nomeUsuario): void
    {
        $nomeMes = self::$MESES[$mes] ?? $mes;
        $fmt = fn(float $v) => $v; // valores numéricos; formatação via cell format

        // Título
        $ws->setCellValue('A1', 'MYFINANCES — Relatório Financeiro');
        $ws->setCellValue('A2', "{$nomeMes} de {$ano} | Usuário: {$nomeUsuario}");
        $ws->setCellValue('A3', 'Gerado em: ' . date('d/m/Y H:i'));

        $this->styleHeader($ws, 'A1:F1', '667EEA', 18);
        $this->styleHeader($ws, 'A2:F2', '764BA2', 11);
        $ws->getStyle('A3')->getFont()->setItalic(true)->setSize(9);
        $ws->getStyle('A3')->getFont()->getColor()->setARGB('FF999999');

        // Linha 5: Labels
        $row = 5;
        $ws->setCellValue("A{$row}", 'Indicador');
        $ws->setCellValue("B{$row}", 'Valor (R$)');
        $this->styleHeader($ws, "A{$row}:B{$row}", '495057', 11);

        $items = [
            ['Total Receitas',   $resumo['total_receitas'],  '00AA66'],
            ['Total Despesas',   $resumo['total_despesas'],  'CC3333'],
            ['Total Dívidas',    $resumo['total_dividas'],   'E67E22'],
            ['Saldo do Mês',     $resumo['saldo'],           $resumo['saldo'] >= 0 ? '27AE60' : 'E74C3C'],
            ['Parcelas Pagas',   $resumo['parcelas_pagas'],  '27AE60'],
            ['Parcelas Pend.',   $resumo['parcelas_pend'],   'E74C3C'],
        ];

        $row++;
        foreach ($items as [$label, $valor, $cor]) {
            $ws->setCellValue("A{$row}", $label);
            $ws->setCellValue("B{$row}", $valor);
            $ws->getStyle("B{$row}")->getFont()->getColor()->setARGB("FF{$cor}");
            $ws->getStyle("B{$row}")->getFont()->setBold(true);
            $ws->getStyle("B{$row}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
            $ws->getStyle("A{$row}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFEEEEEE');
            $ws->getStyle("B{$row}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFEEEEEE');
            $row++;
        }

        $ws->getColumnDimension('A')->setWidth(28);
        $ws->getColumnDimension('B')->setWidth(20);
        $ws->mergeCells('A1:F1');
        $ws->mergeCells('A2:F2');
        $ws->mergeCells('A3:F3');
    }

    private function buildAbaLista(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws, array $rows, array $headers, callable $mapper, string $headerColor, string $titulo): void
    {
        // Título
        $colCount = count($headers);
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        $ws->setCellValue('A1', $titulo);
        $ws->mergeCells("A1:{$lastCol}1");
        $this->styleHeader($ws, "A1:{$lastCol}1", '667EEA', 13);

        // Cabeçalhos
        foreach ($headers as $i => $h) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $ws->setCellValue("{$col}2", $h);
        }
        $this->styleHeader($ws, "A2:{$lastCol}2", '333333', 10);

        // Dados
        $rowNum = 3;
        $totalVal = 0;
        $valorColIdx = array_search('Valor (R$)', $headers);

        foreach ($rows as $row) {
            $cols = $mapper($row);
            foreach ($cols as $i => $val) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
                $ws->setCellValue("{$col}{$rowNum}", $val);

                // Formatar coluna de valor
                if ($i === $valorColIdx && is_numeric($val)) {
                    $ws->getStyle("{$col}{$rowNum}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
                    $totalVal += $val;
                }
            }
            // Zebra
            if ($rowNum % 2 === 0) {
                $ws->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');
            }
            $rowNum++;
        }

        // Linha de total se houver coluna de valor
        if ($valorColIdx !== false && $rowNum > 3) {
            $ws->setCellValue("A{$rowNum}", 'TOTAL');
            $valCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($valorColIdx + 1);
            $ws->setCellValue("{$valCol}{$rowNum}", $totalVal);
            $ws->getStyle("{$valCol}{$rowNum}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
            $ws->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->setBold(true);
            $this->styleHeader($ws, "A{$rowNum}:{$lastCol}{$rowNum}", $headerColor, 10);
        }

        // Auto-fit colunas
        foreach (range(1, $colCount) as $i) {
            $ws->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    private function buildAbaEvolucao(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws, array $evolucao): void
    {
        $ws->setCellValue('A1', 'Evolução Financeira — Últimos 12 Meses');
        $ws->mergeCells('A1:E1');
        $this->styleHeader($ws, 'A1:E1', '667EEA', 13);

        $headers = ['Mês/Ano', 'Receitas (R$)', 'Despesas (R$)', 'Dívidas (R$)', 'Saldo (R$)'];
        foreach ($headers as $i => $h) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $ws->setCellValue("{$col}2", $h);
        }
        $this->styleHeader($ws, 'A2:E2', '333333', 10);

        $row = 3;
        foreach ($evolucao as $m) {
            $saldo = $m['total_receitas'] - $m['total_despesas'] - $m['total_dividas'];
            $ws->setCellValue("A{$row}", $m['label']);
            $ws->setCellValue("B{$row}", $m['total_receitas']);
            $ws->setCellValue("C{$row}", $m['total_despesas']);
            $ws->setCellValue("D{$row}", $m['total_dividas']);
            $ws->setCellValue("E{$row}", $saldo);

            foreach (['B', 'C', 'D', 'E'] as $col) {
                $ws->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
            }

            $saldoColor = $saldo >= 0 ? 'FF27AE60' : 'FFE74C3C';
            $ws->getStyle("E{$row}")->getFont()->getColor()->setARGB($saldoColor);

            if ($row % 2 === 0) {
                $ws->getStyle("A{$row}:E{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');
            }
            $row++;
        }

        foreach (range(1, 5) as $i) {
            $ws->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    private function styleHeader(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws, string $range, string $hex, int $size = 10): void
    {
        $style = $ws->getStyle($range);
        $style->getFont()->setBold(true)->setSize($size);
        $style->getFont()->getColor()->setARGB('FFFFFFFF');
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FF{$hex}");
        $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $ws->getRowDimension(explode(':', $range)[0][1] ?? 1)->setRowHeight(20);
    }
}
