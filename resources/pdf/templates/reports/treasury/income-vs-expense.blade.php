@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">A. DATOS DE LA SEDE</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="20%" style="border: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="80%" style="border: 1px solid #000; padding: 4px;">{{ strtoupper($infrastructure_name) }}</td>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">B. RESUMEN DIARIO - INGRESOS VS GASTOS</th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 15%;">FECHA</th>
        <th style="padding: 4px; border: 1px solid #000; width: 25%;">INGRESOS</th>
        <th style="padding: 4px; border: 1px solid #000; width: 25%;">GASTOS</th>
        <th style="padding: 4px; border: 1px solid #000; width: 25%;">NETO</th>
    </tr>
    @foreach($day_rows as $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['date'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; color: #008000;">S/ {{ number_format($row['income'], 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; color: #cc0000;">S/ {{ number_format($row['expense'], 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; font-weight: bold;">S/ {{ number_format($row['net'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold; border: 1px solid #000;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center; background-color: #ffe598;">TOTALES</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; color: #008000;">S/ {{ number_format($total_income, 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; color: #cc0000;">S/ {{ number_format($total_expense, 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; font-size: 12px;">S/ {{ number_format($net_profit, 2) }}</td>
    </tr>
</table>

@if(count($expense_by_type) > 0)
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffc7ce;">
        <th style="text-align: left !important; padding: 4px;">C. GASTOS POR TIPO</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 40%;">TIPO DE GASTO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 20%;">CANTIDAD</th>
        <th style="padding: 4px; border: 1px solid #000; width: 30%;">MONTO TOTAL</th>
    </tr>
    @foreach($expense_by_type as $type)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000;">{{ $type['type'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $type['count'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($type['amount'], 2) }}</td>
    </tr>
    @endforeach
</table>
@endif

<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 80px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° GERENCIA</div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° CONTABILIDAD</div>
        </td>
    </tr>
</table>
