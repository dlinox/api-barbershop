@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">SEDE: {{ strtoupper($infrastructure_name) }}</th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">N° VENTA</th>
        <th style="padding: 4px; border: 1px solid #000; width: 18%;">CLIENTE</th>
        <th style="padding: 4px; border: 1px solid #000; width: 30%;">PRODUCTOS</th>
        <th style="padding: 4px; border: 1px solid #000; width: 8%;">CANT.</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">DCTO.</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">TOTAL</th>
    </tr>
    @foreach($rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['sale_number'] }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['client'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; font-size: 8px;">{{ $row['items'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['items_count'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['discount'], 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['total'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold;">
        <td colspan="6" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598;">TOTAL VENTAS:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; font-size: 14px;">S/ {{ number_format($total, 2) }}</td>
    </tr>
</table>

<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 80px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">ALMACÉN / VENTAS</div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">V° B° GERENCIA</div>
        </td>
    </tr>
</table>
