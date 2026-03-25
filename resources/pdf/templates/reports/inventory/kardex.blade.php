@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">A. DATOS DEL PRODUCTO</th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="15%" style="border: 1px solid #000; padding: 4px; font-weight: bold;">PRODUCTO:</td>
        <td width="35%" style="border: 1px solid #000; padding: 4px;">{{ $product_name }}</td>
        <td width="15%" style="border: 1px solid #000; padding: 4px; font-weight: bold;">SKU:</td>
        <td width="35%" style="border: 1px solid #000; padding: 4px;">{{ $sku }}</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">PRESENTACIÓN:</td>
        <td style="border: 1px solid #000; padding: 4px;">{{ $presentation_name }}</td>
        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td style="border: 1px solid #000; padding: 4px;">{{ $infrastructure_name }}</td>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">B. MOVIMIENTOS</th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 8px; font-weight: bold; text-align: center;">
        <th style="padding: 3px; border: 1px solid #000; width: 12%;">FECHA</th>
        <th style="padding: 3px; border: 1px solid #000; width: 8%;">TIPO</th>
        <th style="padding: 3px; border: 1px solid #000; width: 14%;">MOTIVO</th>
        <th style="padding: 3px; border: 1px solid #000; width: 7%;">CANT.</th>
        <th style="padding: 3px; border: 1px solid #000; width: 9%;">C. UNIT.</th>
        <th style="padding: 3px; border: 1px solid #000; width: 10%;">C. TOTAL</th>
        <th style="padding: 3px; border: 1px solid #000; width: 7%;">SALDO</th>
        <th style="padding: 3px; border: 1px solid #000; width: 9%;">C.U. SALDO</th>
        <th style="padding: 3px; border: 1px solid #000; width: 10%;">VALOR SALDO</th>
        <th style="padding: 3px; border: 1px solid #000; width: 14%;">NOTAS</th>
    </tr>
    @foreach($rows as $row)
    <tr style="border: 1px solid #000; font-size: 8px;">
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $row['date'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center; color: {{ $row['movement_type'] === 'Entrada' ? '#008000' : '#cc0000' }};">{{ $row['movement_type'] }}</td>
        <td style="padding: 3px; border: 1px solid #000;">{{ $row['reason'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $row['quantity'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['unit_cost'], 2) }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['total_cost'], 2) }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center; font-weight: bold;">{{ $row['balance_quantity'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['balance_unit_cost'], 2) }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: right; font-weight: bold;">S/ {{ number_format($row['balance_total'], 2) }}</td>
        <td style="padding: 3px; border: 1px solid #000; font-size: 7px;">{{ $row['notes'] }}</td>
    </tr>
    @endforeach
</table>

<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 80px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">ALMACÉN</div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° GERENCIA</div>
        </td>
    </tr>
</table>
