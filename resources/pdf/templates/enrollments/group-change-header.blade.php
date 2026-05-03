<table style="width: 100%; font-family: 'Helvetica Neue', Arial, sans-serif; border-bottom: 2px solid #1a1a2e; padding-bottom: 6px;">
    <tr>
        {{-- 1. LOGO --}}
        <td style="width: 10%; text-align: left; vertical-align: middle;">
            @if($company?->logo)
                <img src="{{ public_path('storage/company_logos/' . basename($company->logo)) }}" style="max-height: 80px; max-width: 150px;" />
            @endif
        </td>

        {{-- 2. DATOS DE EMPRESA --}}
        <td style="width: 65%; text-align: left; vertical-align: middle;">
            <div style="font-size: 14px; font-weight: bold; color: #1a1a2e;">
                {{ $company->trade_name ?? $company->name ?? 'Mi Empresa' }}
            </div>
            <div style="font-size: 8.5px; color: #666; line-height: 1.5;">
                @if($company?->ruc)RUC: {{ $company->ruc }}<br>@endif
                @if($company?->address){{ $company->address }}<br>@endif
                @if($company?->phone)Tel: {{ $company->phone }}@endif
            </div>
        </td>

        {{-- 3. DATOS DEL ACTA --}}
        <td style="width: 25%; text-align: center; vertical-align: middle; border: 2px solid #1a1a2e; padding: 6px 10px;">
            <span style="font-size: 10px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px;">
                Acta de Cambio de Grupo
            </span>
            <br>
            <span style="font-size: 12px; font-weight: bold; color: #00796b; letter-spacing: 1px;">
                N° {{ str_pad($change_id, 6, '0', STR_PAD_LEFT) }}
            </span>
        </td>
    </tr>
</table>
