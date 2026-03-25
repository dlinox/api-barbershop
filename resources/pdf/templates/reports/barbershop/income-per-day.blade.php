@include('reports.common.head')

{{-- SECCIÓN A: DATOS DE LA UNIDAD DE NEGOCIO --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            A. DATOS DE LA UNIDAD DE NEGOCIO
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="30%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">UNIDAD DE NEGOCIO:</td>
        <td width="70%" style="padding: 4px;">{{ strtoupper($branch_name) }}</td>
    </tr>
</table>

{{-- SECCIÓN B: DATOS DE LOS INGRESOS --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. DATOS DE LOS INGRESOS
        </th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 24%;">DESCRIPCIÓN DEL INGRESO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">COMPROBANTE <br> EMITIDO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">SERIE-NÚMERO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">MEDIO DE <br> PAGO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 14%;">NÚMERO DE <br> OPERACIÓN</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">IMPORTE</th>
    </tr>

    @foreach($rows as $index => $row)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000;">{{ $row['description'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['receipt_type'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['receipt_number'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['payment_method'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $row['payment_reference'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($row['amount'], 2) }}</td>
    </tr>
    @endforeach

    @for($i = count($rows) + 1; $i <= max(20, count($rows)); $i++)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">{{ $i }}</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
    </tr>
    @endfor

    <tr style="font-size: 9px; font-weight: bold;">
        <td style="border: none;"></td>
        <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">TOTAL EN EFECTIVO</td>
        <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($total_cash, 2) }}</td>
        <td rowspan="2" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; vertical-align: middle; font-size: 14px; font-weight: bold;">TOTAL DÍA:</td>
        <td rowspan="2" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; vertical-align: middle; font-size: 14px; font-weight: bold;">S/ {{ number_format($total_day, 2) }}</td>
    </tr>
    <tr style="font-size: 9px; font-weight: bold;">
        <td style="border: none;"></td>
        <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">TOTAL BANCARIZADO</td>
        <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">S/ {{ number_format($total_bank, 2) }}</td>
    </tr>
    <tr style="font-size: 9px;">
        <td style="border: none;"></td>
        <td colspan="6" style="padding: 4px; border: none; text-align: left;">*Los pagos bancarizados incluyen Yape, Plin y Transferencias</td>
    </tr>
</table>

{{-- FIRMAS --}}
<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 80px;">
    <tr>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">
                V° B° GERENCIA
            </div>
        </td>
        <td></td>
        <td width="30%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style="padding-top: 4px; font-size: 9px; font-weight: bold;">
                V° B° CONTROL INTERNO
            </div>
        </td>
    </tr>
</table>
