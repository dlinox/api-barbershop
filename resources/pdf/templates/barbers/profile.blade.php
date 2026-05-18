<html>

<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
    .section-title { background-color: #1a1a2e; color: #fff; padding: 4px 10px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin: 10px 0 6px 0; letter-spacing: 0.5px; }
    table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    table.info-table td { padding: 3px 6px; vertical-align: top; }
    table.info-table .label { font-weight: bold; color: #555; width: 140px; font-size: 10px; text-transform: uppercase; }
    table.info-table .value { color: #222; border-bottom: 1px dotted #ccc; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .badge-active { color: #27ae60; font-weight: bold; }
    .badge-inactive { color: #c0392b; font-weight: bold; }
    .signatures { margin-top: 40px; width: 100%; }
    .signatures td { width: 50%; text-align: center; padding-top: 40px; vertical-align: bottom; }
    .signatures .line { border-top: 1px solid #333; display: inline-block; width: 200px; padding-top: 4px; font-size: 10px; color: #555; }
</style>
</head>

<body>

    {{-- ═══ DATOS PERSONALES ═══ --}}
    <div class="section-title">Datos Personales</div>
    <table class="info-table">
        <tr>
            <td class="label">Nombre completo</td>
            <td class="value" colspan="3">{{ $full_name }}</td>
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

    {{-- ═══ DATOS DEL BARBERO ═══ --}}
    <div class="section-title">Datos del Barbero</div>
    <table class="info-table">
        <tr>
            <td class="label">Sede</td>
            <td class="value" colspan="3">{{ $branch_name }}</td>
        </tr>
        <tr>
            <td class="label">% Comisión</td>
            <td class="value">{{ $commission_percentage !== null ? $commission_percentage . '%' : '—' }}</td>
            <td class="label">Estado</td>
            <td class="value">
                <span class="{{ $is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
        </tr>
    </table>

    {{-- ═══ FIRMAS ═══ --}}
    <table class="signatures">
        <tr>
            <td>
                <span class="line">Firma del Barbero</span>
            </td>
            <td>
                <span class="line">Firma y Sello de la Barbería</span>
            </td>
        </tr>
    </table>

</body>

</html>
