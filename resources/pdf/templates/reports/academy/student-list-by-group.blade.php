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
        <td width="15%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">GRUPO:</td>
        <td width="35%" style="padding: 4px;">{{ strtoupper($group_name) }}</td>
        <td width="15%" style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="35%" style="padding: 4px; border-left: 1px solid #000;">{{ strtoupper($branch_name) }}</td>
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
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">TOTAL:</td>
        <td style="padding: 4px; border-left: 1px solid #000;">{{ $student_count }} alumnos matriculados</td>
    </tr>
</table>

{{-- SECCIÓN B: LISTA DE ALUMNOS --}}
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. LISTA DE ALUMNOS ({{ $student_count }} matriculados)
        </th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    {{-- CABECERA --}}
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th style="padding: 4px; border: 1px solid #000; width: 10%;">DOCUMENTO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 22%;">APELLIDOS Y NOMBRES</th>
        <th style="padding: 4px; border: 1px solid #000; width: 9%;">TELÉFONO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 15%;">EMAIL</th>
        <th style="padding: 4px; border: 1px solid #000; width: 9%;">F. MATRÍCULA</th>
        <th style="padding: 4px; border: 1px solid #000; width: 16%;">APODERADO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 6%;">PARENTESCO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 9%;">TEL. APODERADO</th>
    </tr>

    {{-- FILAS DE DATOS --}}
    @foreach($students as $index => $student)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $student['document_number'] }}</td>
        <td style="padding: 3px; border: 1px solid #000;">{{ strtoupper($student['paternal_surname'] . ' ' . $student['maternal_surname'] . ', ' . $student['name']) }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $student['phone'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; font-size: 8px;">{{ $student['email'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ \Carbon\Carbon::parse($student['enrollment_date'])->format('d/m/Y') }}</td>
        <td style="padding: 3px; border: 1px solid #000;">{{ $student['guardian_name'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $student['guardian_kinship'] }}</td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $student['guardian_phone'] }}</td>
    </tr>
    @endforeach

    {{-- FILAS VACÍAS --}}
    @for($i = count($students) + 1; $i <= max(20, count($students)); $i++)
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 3px; border: 1px solid #000; text-align: center;">{{ $i }}</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
    </tr>
    @endfor
</table>
