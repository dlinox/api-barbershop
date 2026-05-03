@include('payments.common.head')

{{-- ═══ DATOS DEL DOCENTE ═══ --}}
<div class="section-label mt-8">Datos del Docente</div>

<table class="info-row">
    <tr>
        <td class="info-label">Nombre</td>
        <td class="info-value">{{ $teacher?->person?->full_name ?? '—' }}</td>
    </tr>
</table>
<table class="info-row">
    <tr>
        <td class="info-label">Documento</td>
        <td class="info-value">
            {{ $teacher?->person?->document_type ? strtoupper($teacher->person->document_type) . ': ' : '' }}{{ $teacher?->person?->document_number ?? '—' }}
        </td>
    </tr>
</table>
<table class="info-row">
    <tr>
        <td class="info-label">Sede</td>
        <td class="info-value">{{ $teacher?->branch?->name ?? $infrastructure?->name ?? '—' }}</td>
    </tr>
</table>
<table class="info-row">
    <tr>
        <td class="info-label">Tipo de Pago</td>
        <td class="info-value">{{ $payment->payment_type === 'hourly' ? 'Por hora' : 'Mensual' }}</td>
    </tr>
</table>

<hr class="divider">

{{-- ═══ PERÍODO DE PAGO ═══ --}}
<div class="section-label mt-8">Período de Pago</div>

<table class="info-row">
    <tr>
        <td class="info-label">Período</td>
        <td class="info-value">{{ $payment->period ?? '—' }}</td>
    </tr>
</table>
<table class="info-row">
    <tr>
        <td class="info-label">Desde</td>
        <td class="info-value">{{ $payment->period_start?->format('d/m/Y') ?? '—' }}</td>
        <td class="info-label" style="padding-left: 20px;">Hasta</td>
        <td class="info-value">{{ $payment->period_end?->format('d/m/Y') ?? '—' }}</td>
    </tr>
</table>
<table class="info-row">
    <tr>
        <td class="info-label">Fecha de Pago</td>
        <td class="info-value">{{ $payment->payment_date?->format('d/m/Y') ?? '—' }}</td>
        <td class="info-label" style="padding-left: 20px;">Método</td>
        <td class="info-value">{{ $payment->paymentMethod?->name ?? '—' }}</td>
    </tr>
</table>
@if($payment->payment_reference)
<table class="info-row">
    <tr>
        <td class="info-label">Referencia</td>
        <td class="info-value">{{ $payment->payment_reference }}</td>
    </tr>
</table>
@endif

<hr class="divider">

{{-- ═══ DETALLE POR GRUPOS (solo pago por hora) ═══ --}}
@if(isset($calculation_details['groups']) && count($calculation_details['groups']) > 0)
<div class="section-label mt-8">Detalle por Grupos</div>

<table class="summary-table" style="font-size: 9px;">
    <thead>
        <tr>
            <th>Grupo</th>
            <th class="text-right">Hrs/día</th>
            <th class="text-right">Días</th>
            <th class="text-right">Tarifa</th>
            <th class="text-right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($calculation_details['groups'] as $group)
        <tr>
            <td class="label-cell">{{ $group['group_name'] }}</td>
            <td class="value-cell">{{ $group['hours_per_day'] }}</td>
            <td class="value-cell">{{ $group['attended_days'] }}</td>
            <td class="value-cell">S/ {{ number_format($group['hourly_rate'], 2) }}</td>
            <td class="value-cell">S/ {{ number_format($group['subtotal'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="label-cell" style="font-weight: bold;">Total por horas</td>
            <td class="value-cell" style="font-weight: bold;">S/ {{ number_format($calculation_details['hourly_total'] ?? 0, 2) }}</td>
        </tr>
    </tfoot>
</table>
<hr class="divider">
@endif

{{-- ═══ DETALLE DEL PAGO ═══ --}}
<div class="section-label mt-8">Detalle del Pago</div>

<table class="summary-table">
    <thead>
        <tr>
            <th>Concepto</th>
            <th class="text-right" width="150">Monto</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="label-cell">
                @if(isset($calculation_details['hourly_total']))
                    Total por Horas
                @else
                    Sueldo Base
                @endif
            </td>
            <td class="value-cell">S/ {{ number_format($payment->base_amount, 2) }}</td>
        </tr>
        @if(isset($calculation_details['absences_count']) && $calculation_details['absences_count'] > 0)
        <tr>
            <td class="label-cell deduction">
                Descuento por Faltas ({{ $calculation_details['absences_count'] }})
            </td>
            <td class="value-cell deduction">- S/ {{ number_format($calculation_details['absence_deduction'] ?? 0, 2) }}</td>
        </tr>
        @endif
        @if(isset($calculation_details['discount']) && $calculation_details['discount'] > 0)
        <tr>
            <td class="label-cell deduction">Descuento adicional</td>
            <td class="value-cell deduction">- S/ {{ number_format($calculation_details['discount'], 2) }}</td>
        </tr>
        @endif
        @if(isset($calculation_details['advances_total']) && $calculation_details['advances_total'] > 0)
        <tr>
            <td class="label-cell deduction">Adelantos descontados</td>
            <td class="value-cell deduction">- S/ {{ number_format($calculation_details['advances_total'], 2) }}</td>
        </tr>
        @endif
        @if($payment->bonus > 0)
        <tr>
            <td class="label-cell">Bonificación</td>
            <td class="value-cell">+ S/ {{ number_format($payment->bonus, 2) }}</td>
        </tr>
        @endif
        @if($payment->deductions > 0)
        <tr>
            <td class="label-cell deduction">Total Deducciones</td>
            <td class="value-cell deduction">- S/ {{ number_format($payment->deductions, 2) }}</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td>TOTAL A PAGAR</td>
            <td class="text-right">S/ {{ number_format($payment->total_amount, 2) }}</td>
        </tr>
    </tfoot>
</table>

@if($payment->notes)
<div class="mt-12">
    <div class="section-label">Observaciones</div>
    <p style="font-size: 10.5px; color: #444; padding: 6px 0;">{{ $payment->notes }}</p>
</div>
@endif

{{-- ═══ FIRMA ═══ --}}
<div style="margin-top: 40px;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 45%; text-align: center; border-top: 1px solid #1a1a2e; padding-top: 4px; font-size: 9.5px; color: #555;">
                Firma del Docente
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%; text-align: center; border-top: 1px solid #1a1a2e; padding-top: 4px; font-size: 9.5px; color: #555;">
                Responsable de Pago
                @if($payment->paidBy)
                    <br><strong>{{ $payment->paidBy->username }}</strong>
                @endif
            </td>
        </tr>
    </table>
</div>
