@include('reports.common.head')

{{-- SECCIÓN A: DATOS DEL GRUPO --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            A. DATOS DEL GRUPO
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="12%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">GRUPO:</td>
        <td width="38%" style="padding: 4px;">{{ strtoupper($group_name) }}</td>
        <td width="12%" style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="38%" style="padding: 4px; border-left: 1px solid #000;">{{ strtoupper($branch_name) }}</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">NIVEL:</td>
        <td style="padding: 4px;">{{ strtoupper($level_name) }}</td>
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">HORARIO:</td>
        <td style="padding: 4px; border-left: 1px solid #000;">{{ $schedule_time }}</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">AULA:</td>
        <td style="padding: 4px;">{{ strtoupper($room_name) }}</td>
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">PERIODO:</td>
        <td style="padding: 4px; border-left: 1px solid #000;">{{ $month_name }} {{ $year }}</td>
    </tr>
</table>

{{-- SECCIÓN B: REGISTRO DE ASISTENCIA --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. REGISTRO DE ASISTENCIA - {{ $month_name }} {{ $year }}
        </th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    {{-- CABECERA: N° | ALUMNO | 1..31 | % --}}
    <tr style="border: 1px solid #000; font-size: 7px; font-weight: bold; text-align: center;">
        <th style="padding: 2px; border: 1px solid #000; width: 3%;">N°</th>
        <th style="padding: 2px; border: 1px solid #000; width: 18%; text-align: left;">ALUMNO</th>
        @for($d = 1; $d <= $days_in_month; $d++)
            <th style="padding: 2px; border: 1px solid #000; {{ $class_days[$d] ? 'background-color: #f5f5f5;' : '' }}">{{ $d }}</th>
        @endfor
        <th style="padding: 2px; border: 1px solid #000; width: 4%;">%</th>
    </tr>

    {{-- FILAS DE ESTUDIANTES --}}
    @foreach($students as $index => $student)
    <tr style="border: 1px solid #000; font-size: 7px;">
        <td style="padding: 2px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 2px; border: 1px solid #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $student['name'] }}</td>
        @for($d = 1; $d <= $days_in_month; $d++)
            @php
                $status = $student['days'][$d] ?? null;
                $label = '';
                $color = '';
                $bg = '';
                if ($status === 'present')           { $label = 'P';  $color = '#fff'; $bg = '#27ae60'; }
                elseif ($status === 'late')           { $label = 'T';  $color = '#000'; $bg = '#f1c40f'; }
                elseif ($status === 'absent')         { $label = 'A';  $color = '#fff'; $bg = '#e74c3c'; }
                elseif ($status === 'absent_justified') { $label = 'AJ'; $color = '#fff'; $bg = '#3498db'; }
                elseif ($status === 'late_justified')  { $label = 'TJ'; $color = '#000'; $bg = '#f39c12'; }
            @endphp
            <td style="padding: 1px; border: 1px solid #000; text-align: center; font-weight: bold; {{ $bg ? "background-color: {$bg}; color: {$color};" : '' }}">{{ $label }}</td>
        @endfor
        <td style="padding: 2px; border: 1px solid #000; text-align: center; font-weight: bold;">{{ $student['attendance_rate'] }}%</td>
    </tr>
    @endforeach
</table>

{{-- LEYENDA --}}
<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 10px; font-size: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th colspan="5" style="text-align: left !important; padding: 4px;">LEYENDA</th>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">
            <span style="background-color: #27ae60; color: #fff; padding: 2px 6px; font-weight: bold;">P</span>
            &nbsp;Presente
        </td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">
            <span style="background-color: #f1c40f; color: #000; padding: 2px 6px; font-weight: bold;">T</span>
            &nbsp;Tardanza
        </td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">
            <span style="background-color: #e74c3c; color: #fff; padding: 2px 6px; font-weight: bold;">A</span>
            &nbsp;Ausente
        </td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">
            <span style="background-color: #3498db; color: #fff; padding: 2px 6px; font-weight: bold;">AJ</span>
            &nbsp;Ausencia Justificada
        </td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;">
            <span style="background-color: #f39c12; color: #000; padding: 2px 6px; font-weight: bold;">TJ</span>
            &nbsp;Tardanza Justificada
        </td>
    </tr>
</table>
