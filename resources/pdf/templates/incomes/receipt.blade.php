@include('incomes.common.head')

{{-- ═══ DATOS DEL COMPROBANTE Y CLIENTE ═══ --}}
    <table class="info-row">
        <tr>
            <td class="info-label">Fecha de emisión</td>
            <td class="info-value">{{ $transaction_date }}</td>
            <td class="info-label" style="padding-left: 20px;">Estado</td>
            <td class="info-value">{{ $status }}</td>
        </tr>
    </table>
    <table class="info-row">
        <tr>
            <td class="info-label">Cliente</td>
            <td class="info-value" colspan="3">{{ $client_name }}</td>
        </tr>
    </table>
    <table class="info-row">
        <tr>
            <td class="info-label">N° Documento</td>
            <td class="info-value" colspan="3">{{ $client_document }}</td>
        </tr>
    </table>

    <hr class="divider">

    {{-- ═══ DETALLE DE ÍTEMS ═══ --}}
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">#</th>
                <th style="width: 39%;">Descripción</th>
                <th style="width: 10%;" class="text-center">Cant.</th>
                <th style="width: 15%;" class="text-right">P. Unitario</th>
                <th style="width: 15%;" class="text-right">Descuento</th>
                <th style="width: 15%;" class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $i => $detail)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $detail['description'] }}</td>
                <td class="text-center">{{ $detail['quantity'] }}</td>
                <td class="text-right">S/ {{ number_format($detail['unit_price'], 2) }}</td>
                <td class="text-right">
                    @if($detail['discount'] > 0)
                        S/ {{ number_format($detail['discount'], 2) }}
                    @else
                        —
                    @endif
                </td>
                <td class="text-right">S/ {{ number_format($detail['subtotal'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ═══ TOTALES ═══ --}}
    <div class="totals-block clearfix">
        <div class="totals-inner">
            <table>
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td class="total-value">S/ {{ number_format($subtotal, 2) }}</td>
                </tr>
                @if($discount > 0)
                <tr>
                    <td class="total-label">Descuento</td>
                    <td class="total-value">- S/ {{ number_format($discount, 2) }}</td>
                </tr>
                @endif
                @if($tax > 0)
                <tr>
                    <td class="total-label">IGV (18%)</td>
                    <td class="total-value">S/ {{ number_format($tax, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="total-label">TOTAL</td>
                    <td class="total-value">S/ {{ number_format($total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ═══ MEDIOS DE PAGO ═══ --}}
    @if(!empty($payment_methods))
    <div class="payment-section">
        <div class="section-label">Medios de Pago</div>
        <table class="payment-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Método</th>
                    <th style="width: 30%;" class="text-right">Monto</th>
                    <th style="width: 30%;">Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment_methods as $pm)
                <tr>
                    <td>{{ $pm['method'] }}</td>
                    <td class="text-right">S/ {{ number_format($pm['amount'], 2) }}</td>
                    <td>{{ $pm['reference'] ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ═══ OBSERVACIONES ═══ --}}
    @if($observations)
    <div class="observations">
        <strong>Observaciones:</strong> {{ $observations }}
    </div>
    @endif
