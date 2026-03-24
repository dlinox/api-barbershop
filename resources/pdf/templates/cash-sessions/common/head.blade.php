<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #2d2d2d; line-height: 1.5; }

    /* --- Header / Empresa --- */
    .receipt-header { width: 100%; margin-bottom: 14px; }
    .receipt-header td { vertical-align: top; }
    .company-block { padding-right: 15px; }
    .company-name { font-size: 16px; font-weight: bold; color: #1a1a2e; margin-bottom: 2px; }
    .company-detail { font-size: 9.5px; color: #666; line-height: 1.6; }

    /* --- Caja del titulo --- */
    .receipt-box { border: 2px solid #1a1a2e; text-align: center; padding: 8px 14px; width: 220px; }
    .receipt-box .doc-title { font-size: 13px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; }
    .receipt-box .doc-number { font-size: 12px; font-weight: bold; color: #c0392b; margin-top: 3px; letter-spacing: 0.5px; }

    /* --- Separadores --- */
    .divider { border: none; border-top: 1px solid #ddd; margin: 10px 0; }
    .divider-bold { border: none; border-top: 2px solid #1a1a2e; margin: 10px 0; }

    /* --- Info rows --- */
    .info-row { width: 100%; margin-bottom: 3px; }
    .info-row td { padding: 2px 0; font-size: 11px; }
    .info-label { font-weight: bold; color: #555; width: 160px; font-size: 10px; text-transform: uppercase; }
    .info-value { color: #222; }

    /* --- Section title --- */
    .section-title {
        font-size: 11px; font-weight: bold; color: #1a1a2e; text-transform: uppercase;
        letter-spacing: 0.5px; border-bottom: 2px solid #1a1a2e; padding-bottom: 3px;
        margin: 16px 0 8px 0;
    }

    /* --- Summary cards --- */
    .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .summary-table td { padding: 6px 10px; font-size: 11px; }
    .summary-table .label { color: #555; width: 60%; }
    .summary-table .value { text-align: right; font-weight: bold; color: #222; }
    .summary-table .highlight { background-color: #f8f9fa; }
    .summary-table .total-row td {
        border-top: 2px solid #1a1a2e; font-size: 13px; font-weight: bold; color: #1a1a2e; padding-top: 8px;
    }
    .summary-table .surplus { color: #e67e22; }
    .summary-table .shortage { color: #c0392b; }
    .summary-table .exact { color: #27ae60; }

    /* --- Detail tables --- */
    .detail-table { width: 100%; border-collapse: collapse; margin: 6px 0 12px 0; }
    .detail-table thead th {
        background-color: #1a1a2e; color: #fff; padding: 5px 8px;
        font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px; border: none;
    }
    .detail-table tbody td {
        padding: 4px 8px; border-bottom: 1px solid #e8e8e8; font-size: 10px;
    }
    .detail-table tbody tr:last-child td { border-bottom: 2px solid #1a1a2e; }
    .detail-table tfoot td {
        padding: 5px 8px; font-size: 11px; font-weight: bold; border-top: 1px solid #ccc;
    }

    /* --- Badge --- */
    .badge {
        display: inline-block; padding: 2px 8px; border-radius: 3px;
        font-size: 9px; font-weight: bold; text-transform: uppercase;
    }
    .badge-completed { background-color: #d4edda; color: #155724; }
    .badge-cancelled { background-color: #f8d7da; color: #721c24; }
    .badge-approved { background-color: #d4edda; color: #155724; }

    /* --- Observations --- */
    .observations { margin-top: 14px; padding: 8px 10px; background-color: #fafafa; border-left: 3px solid #1a1a2e; font-size: 10.5px; color: #444; }

    /* --- Footer --- */
    .footer-note { text-align: center; margin-top: 20px; font-size: 8.5px; color: #999; line-height: 1.6; }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-muted { color: #999; }
    .clearfix::after { content: ""; display: table; clear: both; }

    /* --- Empty state --- */
    .empty-state { text-align: center; padding: 12px; color: #999; font-size: 10px; font-style: italic; }
</style>
