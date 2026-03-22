<html>
<head>
    @include('enrollments.common.head')
</head>
<body>

    {{-- ═══ ENCABEZADO ═══ --}}
    <div class="header">
        <span class="enrollment-code">N° {{ str_pad($enrollment_id, 6, '0', STR_PAD_LEFT) }}</span>
        <h1>Ficha de Matrícula</h1>
        <h2>{{ $branch_name }}</h2>
    </div>

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
            <td class="label">F. Nacimiento</td>
            <td class="value">{{ $date_birth ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Género</td>
            <td class="value">{{ $gender ?? '—' }}</td>
            <td class="label">Teléfono</td>
            <td class="value">{{ $phone ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="value" colspan="3">{{ $email ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Dirección</td>
            <td class="value" colspan="3">{{ $address ?? '—' }}</td>
        </tr>
    </table>

    {{-- ═══ APODERADOS ═══ --}}
    @if(!empty($guardians))
    <div class="section-title">Apoderado(s)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%;">Nombre completo</th>
                <th style="width: 25%;">Parentesco</th>
                <th style="width: 25%;">Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guardians as $guardian)
            <tr>
                <td>{{ $guardian['full_name'] }}</td>
                <td>{{ $guardian['kinship'] }}</td>
                <td>{{ $guardian['phone'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- ═══ DATOS DE MATRÍCULA ═══ --}}
    <div class="section-title">Datos de Matrícula</div>
    <table class="info-table">
        <tr>
            <td class="label">Grupo</td>
            <td class="value" colspan="3">{{ $group_name }}</td>
        </tr>
        <tr>
            <td class="label">Nivel</td>
            <td class="value">{{ $level_name }}</td>
            <td class="label">Turno</td>
            <td class="value">{{ $schedule_shift }}</td>
        </tr>
        <tr>
            <td class="label">Horario</td>
            <td class="value">{{ $schedule_time }}</td>
            <td class="label">Días</td>
            <td class="value">{{ $days_of_week }}</td>
        </tr>
        <tr>
            <td class="label">Inicio</td>
            <td class="value">{{ $start_date }}</td>
            <td class="label">Fin</td>
            <td class="value">{{ $end_date }}</td>
        </tr>
        <tr>
            <td class="label">Fecha matrícula</td>
            <td class="value">{{ $enrollment_date }}</td>
            <td class="label">Estado</td>
            <td class="value">{{ $enrollment_status }}</td>
        </tr>
    </table>

    {{-- ═══ PLAN DE PAGOS ═══ --}}
    @if(!empty($payment_plans))
    <div class="section-title">Plan de Pagos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 25%;">Concepto</th>
                <th style="width: 25%;">Desde</th>
                <th style="width: 25%;">Hasta</th>
                <th style="width: 15%;" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($payment_plans as $i => $plan)
            @php $total += $plan['amount']; @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $plan['type'] }}</td>
                <td>{{ $plan['start_date'] }}</td>
                <td>{{ $plan['end_date'] }}</td>
                <td class="text-right">S/ {{ number_format($plan['amount'], 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right" style="font-weight: bold;">Total</td>
                <td class="text-right" style="font-weight: bold;">S/ {{ number_format($total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- ═══ MATERIALES ═══ --}}
    @if(!empty($materials))
    <div class="section-title">Materiales Entregados</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 70%;">Material</th>
                <th style="width: 20%;" class="text-center">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $i => $material)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $material['name'] }}</td>
                <td class="text-center">{{ $material['quantity'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- ═══ FIRMAS ═══ --}}
    <table class="signatures">
        <tr>
            <td>
                <span class="line">Firma del Apoderado / Estudiante</span>
            </td>
            <td>
                <span class="line">Firma y Sello de la Academia</span>
            </td>
        </tr>
    </table>

    @include('enrollments.common.footer')

</body>
</html>
