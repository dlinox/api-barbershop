@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">A. DATOS DE LA CAJA</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="20%" style="border: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="30%" style="border: 1px solid #000; padding: 4px;">{{ strtoupper($infrastructure_name) }}</td>
        <td width="20%" style="border: 1px solid #000; padding: 4px; font-weight: bold;">CAJA:</td>
        <td width="30%" style="border: 1px solid #000; padding: 4px;">{{ $register_name }}</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">APERTURA:</td>
        <td style="border: 1px solid #000; padding: 4px;">{{ $opened_at }} ({{ $opened_by }})</td>
        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">CIERRE:</td>
        <td style="border: 1px solid #000; padding: 4px;">{{ $closed_at }} ({{ $closed_by }})</td>
    </tr>
</table>

{{-- INGRESOS --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #c6efce;">
        <th style="text-align: left !important; padding: 4px;">B. INGRESOS</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 5%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 55%;">DESCRIPCIÓN</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">MEDIO PAGO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">IMPORTE</th>
    </tr>
    @foreach($income_rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['description'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['payment_method'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['amount'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold;">
        <td colspan="3" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #c6efce;">TOTAL INGRESOS:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #c6efce;">S/ {{ number_format($total_income, 2) }}</td>
    </tr>
</table>

{{-- GASTOS --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffc7ce;">
        <th style="text-align: left !important; padding: 4px;">C. GASTOS</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 5%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 35%;">DESCRIPCIÓN</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">TIPO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">MEDIO PAGO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">IMPORTE</th>
    </tr>
    @foreach($expense_rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['description'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['expense_type'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['payment_method'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['amount'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold;">
        <td colspan="4" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffc7ce;">TOTAL GASTOS:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffc7ce;">S/ {{ number_format($total_expense, 2) }}</td>
    </tr>
</table>

{{-- RESUMEN --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">D. RESUMEN DE CAJA</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 10px;">
        <td style="padding: 4px; border: 1px solid #000; font-weight: bold;">Monto Apertura:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($opening_amount, 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; font-weight: bold;">Cierre Esperado:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($expected_closing, 2) }}</td>
    </tr>
    <tr style="border: 1px solid #000; font-size: 10px;">
        <td style="padding: 4px; border: 1px solid #000; font-weight: bold;">Cierre Real:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($actual_closing, 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; font-weight: bold; background-color: #ffe598;">DIFERENCIA:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; font-weight: bold; background-color: #ffe598; font-size: 14px;">S/ {{ number_format($difference, 2) }}</td>
    </tr>
</table>

@if($notes)
<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr>
        <td style="padding: 4px; font-size: 9px; font-style: italic; color: #555;">
            <strong>Observaciones:</strong> {{ $notes }}
        </td>
    </tr>
</table>
@endif

<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 60px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">CAJERO</div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° GERENCIA</div>
        </td>
    </tr>
</table>
