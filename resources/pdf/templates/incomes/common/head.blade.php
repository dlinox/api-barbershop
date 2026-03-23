<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #2d2d2d; line-height: 1.5; }

    /* ─── Header / Empresa ─── */
    .receipt-header { width: 100%; margin-bottom: 14px; }
    .receipt-header td { vertical-align: top; }
    .company-block { padding-right: 15px; }
    .company-name { font-size: 16px; font-weight: bold; color: #1a1a2e; margin-bottom: 2px; }
    .company-detail { font-size: 9.5px; color: #666; line-height: 1.6; }

    /* ─── Caja del número de comprobante ─── */
    .receipt-box { border: 2px solid #1a1a2e; text-align: center; padding: 8px 14px; width: 220px; }
    .receipt-box .doc-title { font-size: 13px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; }
    .receipt-box .doc-number { font-size: 15px; font-weight: bold; color: #c0392b; margin-top: 3px; letter-spacing: 1px; }

    /* ─── Línea separadora ─── */
    .divider { border: none; border-top: 1px solid #ddd; margin: 10px 0; }
    .divider-bold { border: none; border-top: 2px solid #1a1a2e; margin: 10px 0; }

    /* ─── Info del cliente y comprobante ─── */
    .info-row { width: 100%; margin-bottom: 3px; }
    .info-row td { padding: 2px 0; font-size: 11px; }
    .info-label { font-weight: bold; color: #555; width: 120px; font-size: 10px; text-transform: uppercase; }
    .info-value { color: #222; }

    /* ─── Tabla de detalle ─── */
    .detail-table { width: 100%; border-collapse: collapse; margin: 8px 0; }
    .detail-table thead th {
        background-color: #1a1a2e; color: #fff; padding: 6px 8px;
        font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.3px;
        border: none;
    }
    .detail-table tbody td {
        padding: 6px 8px; border-bottom: 1px solid #e8e8e8; font-size: 10.5px;
    }
    .detail-table tbody tr:last-child td { border-bottom: 2px solid #1a1a2e; }
    .detail-table .text-right { text-align: right; }
    .detail-table .text-center { text-align: center; }

    /* ─── Totales ─── */
    .totals-block { width: 100%; margin-top: 2px; }
    .totals-inner { width: 240px; float: right; }
    .totals-inner table { width: 100%; border-collapse: collapse; }
    .totals-inner td { padding: 3px 8px; font-size: 11px; }
    .totals-inner .total-label { text-align: right; color: #555; }
    .totals-inner .total-value { text-align: right; font-weight: bold; }
    .totals-inner .grand-total td {
        border-top: 2px solid #1a1a2e; font-size: 14px; font-weight: bold; color: #1a1a2e; padding-top: 6px;
    }

    /* ─── Medios de pago ─── */
    .payment-section { margin-top: 18px; clear: both; }
    .section-label { font-size: 10px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #1a1a2e; padding-bottom: 3px; margin-bottom: 6px; }
    .payment-table { width: 100%; border-collapse: collapse; }
    .payment-table th { background-color: #f5f5f5; padding: 4px 8px; font-size: 9.5px; text-transform: uppercase; text-align: left; border-bottom: 1px solid #ddd; }
    .payment-table td { padding: 5px 8px; font-size: 10.5px; border-bottom: 1px solid #eee; }

    /* ─── Observaciones ─── */
    .observations { margin-top: 14px; padding: 8px 10px; background-color: #fafafa; border-left: 3px solid #1a1a2e; font-size: 10.5px; color: #444; }

    /* ─── Footer ─── */
    .footer-note { text-align: center; margin-top: 20px; font-size: 8.5px; color: #999; line-height: 1.6; }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .clearfix::after { content: ""; display: table; clear: both; }
</style>
