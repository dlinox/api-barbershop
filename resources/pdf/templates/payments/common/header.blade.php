<table style="width: 100%; font-family: 'Helvetica Neue', Arial, sans-serif; border-bottom: 2px solid #1a1a2e; padding-bottom: 6px;">
    <tr>
        <td style="width: 10%; text-align: left; vertical-align: middle;">
            @php
                $sede = $branch ?? \App\Common\Helpers\PdfLogoHelper::branchFromInfrastructure($infrastructure ?? null);
                $logoPath = \App\Common\Helpers\PdfLogoHelper::resolve($sede, $company ?? null);
            @endphp
            @if($logoPath)
                <img src="{{ $logoPath }}" style="max-height: 70px; max-width: 130px;" />
            @endif
        </td>
        <td style="width: 65%; text-align: left; vertical-align: middle;">
            <div style="font-size: 14px; font-weight: bold; color: #1a1a2e;">
                {{ $sede?->name ?? $company->trade_name ?? $company->name ?? 'Mi Empresa' }}
            </div>
            <div style="font-size: 8.5px; color: #666; line-height: 1.5;">
                @if($sede?->name){{ $company->trade_name ?? $company->name ?? 'Mi Empresa' }}<br>@endif
                @if($company?->ruc)RUC: {{ $company->ruc }}<br>@endif
                @if($company?->address){{ $company->address }}<br>@endif
                @if($company?->phone)Tel: {{ $company->phone }}@endif
            </div>
        </td>
        <td style="width: 25%; text-align: center; vertical-align: middle; border: 2px solid #1a1a2e; padding: 6px 10px;">
            <span style="font-size: 10px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px;">
                Comprobante de Pago
            </span>
            <br>
            <span style="font-size: 11px; font-weight: bold; color: #1a1a2e; letter-spacing: 0.5px;">
                N° {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}
            </span>
        </td>
    </tr>
</table>
