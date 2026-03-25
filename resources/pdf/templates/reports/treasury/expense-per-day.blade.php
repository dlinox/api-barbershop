@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">A. DATOS DE LA SEDE</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="30%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="70%" style="padding: 4px;">{{ strtoupper($infrastructure_name) }}</td>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">B. DETALLE DE GASTOS</th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 26%;">DESCRIPCIÓN</th>
        <th style="padding: 4px; border: 1px solid #000; width: 14%;">TIPO DE GASTO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 14%;">MEDIO PAGO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 14%;">N° COMPROBANTE</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">REGISTRADO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">IMPORTE</th>
    </tr>
    @foreach($rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['description'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['expense_type'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['payment_method'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['voucher_number'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['registered_by'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['amount'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold;">
        <td style="border: none;"></td>
        <td colspan="4"></td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; font-size: 14px;">TOTAL:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; font-size: 14px;">S/ {{ number_format($total, 2) }}</td>
    </tr>
</table>

<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 80px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° GERENCIA</div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° CONTROL INTERNO</div>
        </td>
    </tr>
</table>
