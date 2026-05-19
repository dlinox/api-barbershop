<table style="width: 100%; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr>
        {{-- 2. DATOS DE EMPRESA (centrado) --}}
        <td style="width: 10%; text-align: left; vertical-align: middle;">
            @php
                $sede = $branch ?? \App\Common\Helpers\PdfLogoHelper::branchFromInfrastructure($infrastructure ?? null);
                $logoPath = \App\Common\Helpers\PdfLogoHelper::resolve($sede, $company ?? null);
            @endphp
            @if($logoPath)
                <img src="{{ $logoPath }}" style="max-height: 80px; max-width: 150px;" />
            @endif
        </td>
        <td style="width: 65%; text-align: center; vertical-align: middle;">
            <div style="font-size: 14px; font-weight: bold; color: #1a1a2e;">
                {{ $report_title ?? 'REGISTRO DE INGRESOS DIARIOS' }}
            </div>
            <div style="font-size: 12px; font-weight: bold; color: #1a1a2e;">
                {{ $report_subtitle ?? 'ESCUELA' }}
            </div>
        </td>

        {{-- 3. FECHA Y DÍA (derecha) --}}
        <td style="width: 25%; vertical-align: middle; border: 1px solid #1a1a2e; padding: 0;">
            <table style="width: 100%; border-collapse: collapse; font-size: 11px; font-family: 'Helvetica Neue', Arial, sans-serif;">
                <tr>
                    <td style="padding: 5px 8px; font-weight: bold; color: #555; white-space: nowrap; border-bottom: 1px solid #e0e0e0;">FECHA</td>
                    <td style="padding: 5px 8px; font-weight: bold; color: #1a1a2e; text-align: right; border-bottom: 1px solid #e0e0e0;">{{ $report_date }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 8px; font-weight: bold; color: #555; white-space: nowrap;">DÍA</td>
                    <td style="padding: 5px 8px; font-weight: bold; color: #1a1a2e; text-align: right; text-transform: uppercase;">{{ $report_day }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>