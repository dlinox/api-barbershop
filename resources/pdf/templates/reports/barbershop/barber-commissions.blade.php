@include('reports.common.head')

{{-- SECCIÓN A: DATOS --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            A. DATOS GENERALES
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="15%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="35%" style="padding: 4px;">{{ strtoupper($branch_name) }}</td>
        <td width="15%" style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">PERIODO:</td>
        <td width="35%" style="padding: 4px; border-left: 1px solid #000;">{{ $date_from }} - {{ $date_to }}</td>
    </tr>
</table>

{{-- SECCIÓN B: DETALLE POR BARBERO --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. COMISIONES POR BARBERO
        </th>
    </tr>
</table>

@foreach($rows as $index => $barber)
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 6px;">
    <tr style="border: 1px solid #000; background-color: #f5f5f5; font-size: 10px; font-weight: bold;">
        <td style="padding: 4px; border: 1px solid #000; width: 4%;">{{ $index + 1 }}</td>
        <td style="padding: 4px; border: 1px solid #000; width: 40%;">{{ strtoupper($barber['barber_name']) }}</td>
        <td style="padding: 4px; border: 1px solid #000; width: 15%; text-align: center;">Comisión: {{ $barber['commission_rate'] }}%</td>
        <td style="padding: 4px; border: 1px solid #000; width: 15%; text-align: center;">Tickets: {{ $barber['ticket_count'] }}</td>
        <td style="padding: 4px; border: 1px solid #000; width: 13%; text-align: right;">Total: S/ {{ number_format($barber['total_services'], 2) }}</td>
        <td style="padding: 4px; border: 1px solid #000; width: 13%; text-align: right; background-color: #ffe598;">Comisión: S/ {{ number_format($barber['commission_amount'], 2) }}</td>
    </tr>
</table>

{{-- Detalle de servicios del barbero --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; font-size: 8px; font-weight: bold; text-align: center;">
        <th style="padding: 3px; border: 1px solid #000; width: 50%;">SERVICIO</th>
        <th style="padding: 3px; border: 1px solid #000; width: 20%;">CANTIDAD</th>
        <th style="padding: 3px; border: 1px solid #000; width: 30%;">TOTAL</th>
    </tr>
    @foreach($barber['services'] as $service)
    <tr style="border: 1px solid #000; font-size: 8px;">
        <td style="padding: 3px; border: 1px solid #000;">{{ $service->service_name }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $service->quantity }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: right;">S/ {{ number_format($service->total, 2) }}</td>
    </tr>
    @endforeach
</table>
@endforeach

{{-- TOTALES GENERALES --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 12px;">
    <tr style="border: 1px solid #000; background-color: #ffe598; font-size: 11px; font-weight: bold;">
        <td style="padding: 6px; border: 1px solid #000; width: 50%;">RESUMEN GENERAL</td>
        <td style="padding: 6px; border: 1px solid #000; width: 25%; text-align: right;">Total Servicios: S/ {{ number_format($total_services, 2) }}</td>
        <td style="padding: 6px; border: 1px solid #000; width: 25%; text-align: right;">Total Comisiones: S/ {{ number_format($total_commission, 2) }}</td>
    </tr>
</table>

{{-- FIRMAS --}}
<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 60px;">
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
