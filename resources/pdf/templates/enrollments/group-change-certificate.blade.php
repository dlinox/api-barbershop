<html>

<head>
    @include('enrollments.common.head')
    <style>
        .comparison-wrapper { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .comparison-wrapper td { vertical-align: top; width: 50%; padding: 0 4px; }
        .group-card { border: 1px solid #ccc; border-radius: 4px; padding: 6px 8px; }
        .group-card-title { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #fff; padding: 3px 8px; margin-bottom: 6px; border-radius: 2px; }
        .group-card-origin { background-color: #546e7a; }
        .group-card-dest   { background-color: #00796b; }
        .group-label { font-weight: bold; color: #555; font-size: 9.5px; width: 90px; vertical-align: top; }
        .group-value { color: #222; font-size: 10px; }
        .group-row td { padding: 2px 4px; }
        .arrow-cell { width: 20px; text-align: center; vertical-align: middle; font-size: 20px; color: #1a1a2e; font-weight: bold; }
        .badge-paid    { color: #27ae60; font-weight: bold; }
        .badge-pending { color: #c0392b; font-weight: bold; }
    </style>
</head>

<body>

    {{-- ═══ METADATOS DEL CAMBIO ═══ --}}
    <div class="section-title">Datos del Cambio</div>
    <table class="info-table">
        <tr>
            <td class="label">Fecha del cambio</td>
            <td class="value">{{ $changed_at }}</td>
            <td class="label">Realizado por</td>
            <td class="value">{{ $changed_by }}</td>
        </tr>
        <tr>
            <td class="label">Motivo</td>
            <td class="value" colspan="3">{{ $reason }}</td>
        </tr>
    </table>

    {{-- ═══ DATOS DEL ESTUDIANTE ═══ --}}
    <div class="section-title">Datos del Estudiante</div>
    <table class="info-table">
        <tr>
            <td class="label">Nombre completo</td>
            <td class="value" colspan="3">{{ $student_full_name }}</td>
        </tr>
        <tr>
            <td class="label">Documento</td>
            <td class="value">{{ $document_type }}: {{ $document_number }}</td>
            <td class="label">Teléfono</td>
            <td class="value">{{ $phone ?? '—' }}</td>
        </tr>
    </table>

    {{-- ═══ COMPARACIÓN DE GRUPOS ═══ --}}
    <div class="section-title">Grupos</div>
    <table class="comparison-wrapper">
        <tr>
            {{-- ORIGEN --}}
            <td>
                <div class="group-card">
                    <div class="group-card-title group-card-origin">Grupo Origen &nbsp;· N° {{ str_pad($origin_enrollment_id, 6, '0', STR_PAD_LEFT) }}</div>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr class="group-row"><td class="group-label">Grupo</td><td class="group-value">{{ $origin_group_name }}</td></tr>
                        <tr class="group-row"><td class="group-label">Nivel</td><td class="group-value">{{ $origin_level_name }}</td></tr>
                        <tr class="group-row"><td class="group-label">Sede</td><td class="group-value">{{ $origin_branch_name }}</td></tr>
                        <tr class="group-row"><td class="group-label">Turno</td><td class="group-value">{{ $origin_schedule_shift }}</td></tr>
                        <tr class="group-row"><td class="group-label">Horario</td><td class="group-value">{{ $origin_schedule_time }}</td></tr>
                        <tr class="group-row"><td class="group-label">Días</td><td class="group-value">{{ $origin_days_of_week }}</td></tr>
                        <tr class="group-row"><td class="group-label">Período</td><td class="group-value">{{ $origin_start_date }} – {{ $origin_end_date }}</td></tr>
                    </table>
                </div>
            </td>

            {{-- FLECHA --}}
            <td class="arrow-cell">&#8594;</td>

            {{-- DESTINO --}}
            <td>
                <div class="group-card">
                    <div class="group-card-title group-card-dest">Grupo Destino · N° {{ str_pad($dest_enrollment_id, 6, '0', STR_PAD_LEFT) }}</div>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr class="group-row"><td class="group-label">Grupo</td><td class="group-value">{{ $dest_group_name }}</td></tr>
                        <tr class="group-row"><td class="group-label">Nivel</td><td class="group-value">{{ $dest_level_name }}</td></tr>
                        <tr class="group-row"><td class="group-label">Sede</td><td class="group-value">{{ $dest_branch_name }}</td></tr>
                        <tr class="group-row"><td class="group-label">Turno</td><td class="group-value">{{ $dest_schedule_shift }}</td></tr>
                        <tr class="group-row"><td class="group-label">Horario</td><td class="group-value">{{ $dest_schedule_time }}</td></tr>
                        <tr class="group-row"><td class="group-label">Días</td><td class="group-value">{{ $dest_days_of_week }}</td></tr>
                        <tr class="group-row"><td class="group-label">Período</td><td class="group-value">{{ $dest_start_date }} – {{ $dest_end_date }}</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- ═══ COMPARACIÓN DE PLANES DE PAGO ═══ --}}
    @if(!empty($origin_payment_plans) || !empty($payment_plans))
    @php
        $originTotal = 0;
        $destTotal   = 0;
        $maxRows = max(count($origin_payment_plans), count($payment_plans));
    @endphp
    <div class="section-title">Planes de Pago</div>
    <table class="data-table" style="margin-bottom:4px;">
        <thead>
            <tr>
                {{-- Grupo Origen (colspan 2) --}}
                <th colspan="2" style="background:#546e7a; color:#fff; text-align:center; border-right:2px solid #fff;">
                    Grupo Origen
                </th>
                {{-- Grupo Destino (colspan 4) --}}
                <th colspan="5" style="background:#00796b; color:#fff; text-align:center;">
                    Grupo Destino
                </th>
            </tr>
            <tr>
                <th style="width:14%;">Concepto</th>
                <th class="text-right" style="width:10%; border-right:2px solid #ccc;">Monto</th>
                <th style="width:14%;">Concepto</th>
                <th style="width:18%; font-size:8px;">Período</th>
                <th class="text-right" style="width:10%;">Monto</th>
                <th class="text-right" style="width:10%;">Diferencia</th>
                <th class="text-center" style="width:10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < $maxRows; $i++)
            @php
                $orig = $origin_payment_plans[$i] ?? null;
                $dest = $payment_plans[$i] ?? null;
                if ($orig) $originTotal += $orig['amount'];
                if ($dest) $destTotal   += $dest['amount'];
                $diff = ($orig && $dest) ? ($dest['amount'] - $orig['amount']) : null;
                $diffColor  = $diff === null ? '' : ($diff > 0.001 ? '#c0392b' : ($diff < -0.001 ? '#27ae60' : '#555'));
                $diffPrefix = $diff === null ? '' : ($diff > 0.001 ? '+' : '');
            @endphp
            <tr>
                {{-- ORIGEN --}}
                <td style="color:#444;">{{ $orig ? $orig['type'] : '—' }}</td>
                <td class="text-right" style="border-right:2px solid #ccc;">
                    {{ $orig ? 'S/ ' . number_format($orig['amount'], 2) : '—' }}
                </td>
                {{-- DESTINO --}}
                <td>{{ $dest ? $dest['type'] : '—' }}</td>
                <td style="font-size:8px;">
                    {{ $dest ? $dest['start_date'] . ' – ' . $dest['end_date'] : '—' }}
                </td>
                <td class="text-right">
                    {{ $dest ? 'S/ ' . number_format($dest['amount'], 2) : '—' }}
                </td>
                <td class="text-right" style="font-weight:bold; color:{{ $diffColor }};">
                    @if($diff === null)
                        —
                    @elseif(abs($diff) < 0.001)
                        =
                    @else
                        {{ $diffPrefix }}S/ {{ number_format($diff, 2) }}
                    @endif
                </td>
                <td class="text-center">
                    @if($dest)
                    <span class="{{ $dest['is_paid'] ? 'badge-paid' : 'badge-pending' }}">
                        {{ $dest['is_paid'] ? 'Pagado' : 'Pendiente' }}
                    </span>
                    @else
                        —
                    @endif
                </td>
            </tr>
            @endfor
            {{-- TOTALS ROW --}}
            <tr>
                <td class="text-right" style="font-weight:bold;">Total</td>
                <td class="text-right" style="font-weight:bold; border-right:2px solid #ccc;">
                    S/ {{ number_format($originTotal, 2) }}
                </td>
                <td colspan="2" class="text-right" style="font-weight:bold;">Total</td>
                <td class="text-right" style="font-weight:bold;">S/ {{ number_format($destTotal, 2) }}</td>
                @php $totalDiff = $destTotal - $originTotal; @endphp
                <td class="text-right" style="font-weight:bold; color:{{ $totalDiff > 0.001 ? '#c0392b' : ($totalDiff < -0.001 ? '#27ae60' : '#555') }};">
                    @if(abs($totalDiff) < 0.001)
                        =
                    @else
                        {{ $totalDiff > 0 ? '+' : '' }}S/ {{ number_format($totalDiff, 2) }}
                    @endif
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- ═══ FIRMAS ═══ --}}
    <table class="signatures">
        <tr>
            <td><span class="line">Firma del Apoderado / Estudiante</span></td>
            <td><span class="line">Firma y Sello de la Academia</span></td>
        </tr>
    </table>

</body>

</html>
