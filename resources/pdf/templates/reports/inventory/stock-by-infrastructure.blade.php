@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">SEDE: {{ strtoupper($infrastructure_name) }}</th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 18%;">PRODUCTO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">SKU</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">CATEGORÍA</th>
        <th style="padding: 4px; border: 1px solid #000; width: 8%;">STOCK</th>
        <th style="padding: 4px; border: 1px solid #000; width: 8%;">MÍN.</th>
        <th style="padding: 4px; border: 1px solid #000; width: 8%;">ESTADO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">COSTO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">P. VENTA</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">VALOR</th>
    </tr>
    @foreach($rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['product'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['sku'] }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['category'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center; font-weight: bold; color: {{ $row['status'] === 'Agotado' ? '#cc0000' : ($row['status'] === 'Bajo' ? '#cc6600' : '#008000') }};">{{ $row['stock'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['min_stock'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center; font-weight: bold; color: {{ $row['status'] === 'Agotado' ? '#cc0000' : ($row['status'] === 'Bajo' ? '#cc6600' : '#008000') }};">{{ $row['status'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['cost_price'], 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['sale_price'], 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['stock_value'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold;">
        <td colspan="9" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598;">VALOR TOTAL:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; font-size: 12px;">S/ {{ number_format($total_value, 2) }}</td>
    </tr>
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
