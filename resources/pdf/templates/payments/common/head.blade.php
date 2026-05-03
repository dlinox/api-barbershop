<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #2d2d2d; line-height: 1.5; }

    .divider { border: none; border-top: 1px solid #ddd; margin: 10px 0; }
    .divider-bold { border: none; border-top: 2px solid #1a1a2e; margin: 10px 0; }

    .info-row { width: 100%; margin-bottom: 3px; }
    .info-row td { padding: 2px 0; font-size: 11px; }
    .info-label { font-weight: bold; color: #555; width: 140px; font-size: 10px; text-transform: uppercase; }
    .info-value { color: #222; }

    .section-label { font-size: 10px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #1a1a2e; padding-bottom: 3px; margin-bottom: 6px; }

    .summary-table { width: 100%; border-collapse: collapse; margin: 8px 0; }
    .summary-table th, .summary-table td { padding: 6px 8px; font-size: 11px; }
    .summary-table thead th { background-color: #1a1a2e; color: #fff; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.3px; }
    .summary-table tbody td { border-bottom: 1px solid #e8e8e8; }
    .summary-table tbody tr:last-child td { border-bottom: 2px solid #1a1a2e; }
    .summary-table .text-right { text-align: right; }
    .summary-table .label-cell { color: #555; font-weight: bold; }
    .summary-table .value-cell { text-align: right; font-weight: bold; }
    .summary-table .deduction { color: #c0392b; }
    .summary-table .total-row td { background-color: #f5f5f5; font-size: 13px; font-weight: bold; color: #1a1a2e; border-top: 2px solid #1a1a2e; padding-top: 6px; }

    .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.3px; }
    .badge-paid { background-color: #d4edda; color: #155724; }
    .badge-pending { background-color: #fff3cd; color: #856404; }

    .clearfix::after { content: ""; display: table; clear: both; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .mt-8 { margin-top: 8px; }
    .mt-12 { margin-top: 12px; }
</style>
