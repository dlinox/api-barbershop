<html>
<head>
    @include('cash-sessions.common.head')
</head>
<body>

    {{-- === ENCABEZADO: EMPRESA + TITULO === --}}
    <table class="receipt-header">
        <tr>
            <td class="company-block">
                <div class="company-name">{{ $company->trade_name ?? $company->name ?? 'Mi Empresa' }}</div>
                <div class="company-detail">
                    @if($company?->ruc)<strong>RUC:</strong> {{ $company->ruc }}<br>@endif
                    @if($company?->address){{ $company->address }}<br>@endif
                    @if($company?->phone)Tel: {{ $company->phone }}@endif
                </div>
            </td>
            <td style="text-align: right;">
                <table style="margin-left: auto;">
                    <tr>
                        <td>
                            <div class="receipt-box">
                                <div class="doc-title">Cierre de Caja</div>
                                <div class="doc-number">{{ $cash_register_name }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr class="divider-bold">

    {{-- === DATOS DE LA SESION === --}}
    <table class="info-row">
        <tr>
            <td class="info-label">Apertura</td>
            <td class="info-value">{{ $opened_at }}</td>
            <td class="info-label" style="padding-left: 20px;">Cierre</td>
            <td class="info-value">{{ $closed_at }}</td>
        </tr>
    </table>
    <table class="info-row">
        <tr>
            <td class="info-label">Abierta por</td>
            <td class="info-value">{{ $opened_by }}</td>
            <td class="info-label" style="padding-left: 20px;">Cerrada por</td>
            <td class="info-value">{{ $closed_by }}</td>
        </tr>
    </table>

    <hr class="divider">

    {{-- === RESUMEN FINANCIERO === --}}
    <div class="section-title">Resumen Financiero</div>
    <table class="summary-table">
        <tr class="highlight">
            <td class="label">Monto de apertura</td>
            <td class="value">S/ {{ number_format($opening_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total ingresos ({{ $incomes_count }} operaciones)</td>
            <td class="value" style="color: #27ae60;">+ S/ {{ number_format($total_incomes, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total gastos ({{ $expenses_count }} operaciones)</td>
            <td class="value" style="color: #c0392b;">- S/ {{ number_format($total_expenses, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td class="label">Monto esperado en caja</td>
            <td class="value">S/ {{ number_format($expected_closing_amount, 2) }}</td>
        </tr>
        <tr class="highlight">
            <td class="label">Monto contado (real)</td>
            <td class="value">S/ {{ number_format($actual_closing_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Diferencia</td>
            <td class="value {{ $difference > 0 ? 'surplus' : ($difference < 0 ? 'shortage' : 'exact') }}">
                {{ $difference > 0 ? '+' : '' }}S/ {{ number_format($difference, 2) }}
                @if($difference > 0) (sobrante)
                @elseif($difference < 0) (faltante)
                @else (exacto)
                @endif
            </td>
        </tr>
    </table>

    {{-- === DETALLE DE INGRESOS === --}}
    <div class="section-title">Detalle de Ingresos</div>
    @if(count($incomes) > 0)
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 15%;">Comprobante</th>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 18%;">Fecha</th>
                <th style="width: 12%;">Estado</th>
                <th style="width: 12%;" class="text-right">Descuento</th>
                <th style="width: 13%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomes as $i => $income)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $income['receipt_number'] }}</td>
                <td>{{ $income['client'] }}</td>
                <td>{{ $income['date'] }}</td>
                <td>
                    <span class="badge {{ $income['status'] === 'completed' ? 'badge-completed' : 'badge-cancelled' }}">
                        {{ $income['status'] === 'completed' ? 'Completado' : 'Anulado' }}
                    </span>
                </td>
                <td class="text-right">
                    @if($income['discount'] > 0)
                        S/ {{ number_format($income['discount'], 2) }}
                    @else
                        —
                    @endif
                </td>
                <td class="text-right">S/ {{ number_format($income['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right">Total Ingresos</td>
                <td class="text-right">S/ {{ number_format($total_incomes, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="empty-state">No se registraron ingresos en esta sesion.</div>
    @endif

    {{-- === DETALLE POR METODO DE PAGO === --}}
    @if(count($payment_method_summary) > 0)
    <div class="section-title">Resumen por Metodo de Pago</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 60%;">Metodo</th>
                <th style="width: 20%;" class="text-center">Operaciones</th>
                <th style="width: 20%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment_method_summary as $pm)
            <tr>
                <td>{{ $pm['method'] }}</td>
                <td class="text-center">{{ $pm['count'] }}</td>
                <td class="text-right">S/ {{ number_format($pm['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- === DETALLE DE GASTOS === --}}
    <div class="section-title">Detalle de Gastos</div>
    @if(count($expenses) > 0)
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 20%;">Tipo</th>
                <th style="width: 30%;">Descripcion</th>
                <th style="width: 15%;">Metodo Pago</th>
                <th style="width: 15%;">Fecha</th>
                <th style="width: 15%;" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $i => $expense)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $expense['type'] }}</td>
                <td>{{ $expense['description'] }}</td>
                <td>{{ $expense['payment_method'] }}</td>
                <td>{{ $expense['date'] }}</td>
                <td class="text-right">S/ {{ number_format($expense['amount'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Total Gastos</td>
                <td class="text-right">S/ {{ number_format($total_expenses, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="empty-state">No se registraron gastos en esta sesion.</div>
    @endif

    {{-- === OBSERVACIONES === --}}
    @if($notes)
    <div class="observations">
        <strong>Observaciones:</strong> {{ $notes }}
    </div>
    @endif

    <div class="footer-note">
        Documento generado el {{ $generated_at }}<br>
        Generado por: {{ $generated_by }}<br>
        Este documento es un reporte interno de cierre de caja. No tiene validez tributaria.
    </div>

</body>
</html>
