<?php echo $__env->make('incomes.common.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <table class="info-row">
        <tr>
            <td class="info-label">Fecha de emisión</td>
            <td class="info-value"><?php echo e($transaction_date); ?></td>
            <td class="info-label" style="padding-left: 20px;">Estado</td>
            <td class="info-value"><?php echo e($status); ?></td>
        </tr>
    </table>
    <table class="info-row">
        <tr>
            <td class="info-label">Cliente</td>
            <td class="info-value" colspan="3"><?php echo e($client_name); ?></td>
        </tr>
    </table>
    <table class="info-row">
        <tr>
            <td class="info-label">N° Documento</td>
            <td class="info-value" colspan="3"><?php echo e($client_document); ?></td>
        </tr>
    </table>

    <hr class="divider">

    
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
            <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($i + 1); ?></td>
                <td><?php echo e($detail['description']); ?></td>
                <td class="text-center"><?php echo e($detail['quantity']); ?></td>
                <td class="text-right">S/ <?php echo e(number_format($detail['unit_price'], 2)); ?></td>
                <td class="text-right">
                    <?php if($detail['discount'] > 0): ?>
                        S/ <?php echo e(number_format($detail['discount'], 2)); ?>

                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td class="text-right">S/ <?php echo e(number_format($detail['subtotal'], 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    
    <div class="totals-block clearfix">
        <div class="totals-inner">
            <table>
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td class="total-value">S/ <?php echo e(number_format($subtotal + $discount, 2)); ?></td>
                </tr>
                <?php if($discount > 0): ?>
                <tr>
                    <td class="total-label">Descuento</td>
                    <td class="total-value">- S/ <?php echo e(number_format($discount, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($tax > 0): ?>
                <tr>
                    <td class="total-label">IGV (18%)</td>
                    <td class="total-value">S/ <?php echo e(number_format($tax, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td class="total-label">TOTAL</td>
                    <td class="total-value">S/ <?php echo e(number_format($total, 2)); ?></td>
                </tr>
            </table>
        </div>
    </div>

    
    <?php if(!empty($payment_methods)): ?>
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
                <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($pm['method']); ?></td>
                    <td class="text-right">S/ <?php echo e(number_format($pm['amount'], 2)); ?></td>
                    <td><?php echo e($pm['reference'] ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    
    <?php if($observations): ?>
    <div class="observations">
        <strong>Observaciones:</strong> <?php echo e($observations); ?>

    </div>
    <?php endif; ?>
<?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/incomes/receipt.blade.php ENDPATH**/ ?>