@include('reports.common.head')

{{-- SECCIÓN A: DATOS DE LA CAJA --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            A. DATOS DE LA SESIÓN DE CAJA
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="15%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="35%" style="padding: 4px;">{{ strtoupper($infrastructure_name) }}</td>
        <td width="15%" style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">CAJA:</td>
        <td width="35%" style="padding: 4px; border-left: 1px solid #000;">{{ strtoupper($cash_register_name) }}</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">APERTURA:</td>
        <td style="padding: 4px;">{{ $opened_at }} ({{ $opened_by }})</td>
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">CIERRE:</td>
        <td style="padding: 4px; border-left: 1px solid #000;">{{ $closed_at }} ({{ $closed_by }})</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">ESTADO:</td>
        <td style="padding: 4px;">{{ strtoupper($status) }}</td>
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">MONTO APERTURA:</td>
        <td style="padding: 4px; border-left: 1px solid #000;">S/ {{ number_format($opening_amount, 2) }}</td>
    </tr>
</table>

{{-- SECCIÓN B: RESUMEN DE TICKETS --}}
@if(!empty($ticket_summary))
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. RESUMEN DE TICKETS
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 40%;">ESTADO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 30%;">CANTIDAD</th>
        <th style="padding: 4px; border: 1px solid #000; width: 30%;">TOTAL</th>
    </tr>
    @php
        $statusLabels = ['confirmed' => 'Confirmados', 'pending' => 'Pendientes', 'cancelled' => 'Cancelados'];
    @endphp
    @foreach($ticket_summary as $status => $data)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000;">{{ $statusLabels[$status] ?? strtoupper($status) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $data->count }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($data->total ?? 0, 2) }}</td>
    </tr>
    @endforeach
</table>
@endif

{{-- SECCIÓN C: INGRESOS POR MÉTODO DE PAGO --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            C. INGRESOS POR MÉTODO DE PAGO
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 30%;">MÉTODO DE PAGO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 15%;">TIPO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">N° OPERACIONES</th>
        <th style="padding: 4px; border: 1px solid #000; width: 35%;">TOTAL</th>
    </tr>
    @foreach($payment_summary as $pm)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000;">{{ $pm->method_name }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $pm->method_type === 'cash' ? 'Efectivo' : 'Bancarizado' }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $pm->income_count }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($pm->total, 2) }}</td>
    </tr>
    @endforeach
    <tr style="border: 1px solid #000; font-size: 10px; font-weight: bold; background-color: #f5f5f5;">
        <td colspan="3" style="padding: 4px; border: 1px solid #000; text-align: right;">TOTAL INGRESOS:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($total_incomes, 2) }}</td>
    </tr>
</table>

{{-- SECCIÓN D: EGRESOS --}}
@if(!empty($expense_rows))
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            D. EGRESOS
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 25%;">TIPO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 46%;">DESCRIPCIÓN</th>
        <th style="padding: 4px; border: 1px solid #000; width: 25%;">MONTO</th>
    </tr>
    @foreach($expense_rows as $index => $expense)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $expense['type'] }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $expense['description'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($expense['amount'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="border: 1px solid #000; font-size: 10px; font-weight: bold; background-color: #f5f5f5;">
        <td colspan="3" style="padding: 4px; border: 1px solid #000; text-align: right;">TOTAL EGRESOS:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($total_expenses, 2) }}</td>
    </tr>
</table>
@endif

{{-- SECCIÓN E: CUADRE DE CAJA --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            E. CUADRE DE CAJA
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 10px;">
        <td width="50%" style="padding: 4px; border: 1px solid #000; font-weight: bold;">Monto de Apertura</td>
        <td width="50%" style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($opening_amount, 2) }}</td>
    </tr>
    <tr style="border: 1px solid #000; font-size: 10px;">
        <td style="padding: 4px; border: 1px solid #000; font-weight: bold;">Total Ingresos</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; color: #27ae60;">+ S/ {{ number_format($total_incomes, 2) }}</td>
    </tr>
    <tr style="border: 1px solid #000; font-size: 10px;">
        <td style="padding: 4px; border: 1px solid #000; font-weight: bold;">Total Egresos</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; color: #e74c3c;">- S/ {{ number_format($total_expenses, 2) }}</td>
    </tr>
    <tr style="border: 1px solid #000; font-size: 11px; font-weight: bold; background-color: #f5f5f5;">
        <td style="padding: 6px; border: 1px solid #000;">Monto Esperado al Cierre</td>
        <td style="padding: 6px; border: 1px solid #000; text-align: right;">S/ {{ number_format($expected_closing, 2) }}</td>
    </tr>
    <tr style="border: 1px solid #000; font-size: 11px; font-weight: bold;">
        <td style="padding: 6px; border: 1px solid #000;">Monto Real al Cierre</td>
        <td style="padding: 6px; border: 1px solid #000; text-align: right;">S/ {{ number_format($actual_closing, 2) }}</td>
    </tr>
    <tr style="border: 1px solid #000; font-size: 12px; font-weight: bold; background-color: {{ $difference >= 0 ? '#d5f5e3' : '#fadbd8' }};">
        <td style="padding: 6px; border: 1px solid #000;">DIFERENCIA</td>
        <td style="padding: 6px; border: 1px solid #000; text-align: right; color: {{ $difference >= 0 ? '#27ae60' : '#e74c3c' }};">S/ {{ number_format($difference, 2) }}</td>
    </tr>
</table>

{{-- FIRMAS --}}
<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 60px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">RESPONSABLE DE CAJA</div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° GERENCIA</div>
        </td>
    </tr>
</table>
