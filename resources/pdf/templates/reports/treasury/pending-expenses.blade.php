@include('reports.common.head')

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffc7ce;">
        <th style="text-align: left !important; padding: 4px;">A. SEDE: {{ strtoupper($infrastructure_name) }}</th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">FECHA</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">SEDE</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">TIPO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 24%;">DESCRIPCIÓN</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">N° COMP.</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">REGISTRADO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">IMPORTE</th>
    </tr>
    @foreach($rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['date'] }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['infrastructure'] }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['expense_type'] }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['description'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['voucher_number'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['registered_by'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['amount'], 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-size: 10px; font-weight: bold;">
        <td colspan="7" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffc7ce;">TOTAL PENDIENTE:</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffc7ce; font-size: 14px;">S/ {{ number_format($total, 2) }}</td>
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
