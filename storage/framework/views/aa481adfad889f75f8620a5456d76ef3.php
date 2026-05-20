<html>
<head>
    <?php echo $__env->make('cash-sessions.common.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body>

    
    <table class="receipt-header">
        <tr>
            <td class="company-block">
                <div class="company-name"><?php echo e($company->trade_name ?? $company->name ?? 'Mi Empresa'); ?></div>
                <div class="company-detail">
                    <?php if($company?->ruc): ?><strong>RUC:</strong> <?php echo e($company->ruc); ?><br><?php endif; ?>
                    <?php if($company?->address): ?><?php echo e($company->address); ?><br><?php endif; ?>
                    <?php if($company?->phone): ?>Tel: <?php echo e($company->phone); ?><?php endif; ?>
                </div>
            </td>
            <td style="text-align: right;">
                <table style="margin-left: auto;">
                    <tr>
                        <td>
                            <div class="receipt-box">
                                <div class="doc-title">Cierre de Caja</div>
                                <div class="doc-number"><?php echo e($cash_register_name); ?></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr class="divider-bold">

    
    <table class="info-row">
        <tr>
            <td class="info-label">Apertura</td>
            <td class="info-value"><?php echo e($opened_at); ?></td>
            <td class="info-label" style="padding-left: 20px;">Cierre</td>
            <td class="info-value"><?php echo e($closed_at); ?></td>
        </tr>
    </table>
    <table class="info-row">
        <tr>
            <td class="info-label">Abierta por</td>
            <td class="info-value"><?php echo e($opened_by); ?></td>
            <td class="info-label" style="padding-left: 20px;">Cerrada por</td>
            <td class="info-value"><?php echo e($closed_by); ?></td>
        </tr>
    </table>

    <hr class="divider">

    
    <div class="section-title">Resumen Financiero</div>
    <table class="summary-table">
        <tr class="highlight">
            <td class="label">Monto de apertura</td>
            <td class="value">S/ <?php echo e(number_format($opening_amount, 2)); ?></td>
        </tr>
        <tr>
            <td class="label">Total ingresos (<?php echo e($incomes_count); ?> operaciones)</td>
            <td class="value" style="color: #27ae60;">+ S/ <?php echo e(number_format($total_incomes, 2)); ?></td>
        </tr>
        <tr>
            <td class="label">Total gastos (<?php echo e($expenses_count); ?> operaciones)</td>
            <td class="value" style="color: #c0392b;">- S/ <?php echo e(number_format($total_expenses, 2)); ?></td>
        </tr>
        <tr class="total-row">
            <td class="label">Monto esperado en caja</td>
            <td class="value">S/ <?php echo e(number_format($expected_closing_amount, 2)); ?></td>
        </tr>
        <tr class="highlight">
            <td class="label">Monto contado (real)</td>
            <td class="value">S/ <?php echo e(number_format($actual_closing_amount, 2)); ?></td>
        </tr>
        <tr>
            <td class="label">Diferencia</td>
            <td class="value <?php echo e($difference > 0 ? 'surplus' : ($difference < 0 ? 'shortage' : 'exact')); ?>">
                <?php echo e($difference > 0 ? '+' : ''); ?>S/ <?php echo e(number_format($difference, 2)); ?>

                <?php if($difference > 0): ?> (sobrante)
                <?php elseif($difference < 0): ?> (faltante)
                <?php else: ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    
    <div class="section-title">Detalle de Ingresos</div>
    <?php if(count($incomes) > 0): ?>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 15%;">Comprobante</th>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 18%;">Fecha</th>
                <th style="width: 12%;">Estado</th>
                <th style="width: 12%;" class="text-right">Descuento</th>
                <th style="width: 13%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $incomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($i + 1); ?></td>
                <td><?php echo e($income['receipt_number']); ?></td>
                <td><?php echo e($income['client']); ?></td>
                <td><?php echo e($income['date']); ?></td>
                <td>
                    <span class="badge <?php echo e($income['status'] === 'completed' ? 'badge-completed' : 'badge-cancelled'); ?>">
                        <?php echo e($income['status'] === 'completed' ? 'Completado' : 'Anulado'); ?>

                    </span>
                </td>
                <td class="text-right">
                    <?php if($income['discount'] > 0): ?>
                        S/ <?php echo e(number_format($income['discount'], 2)); ?>

                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td class="text-right">S/ <?php echo e(number_format($income['total'], 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right">Total Ingresos</td>
                <td class="text-right">S/ <?php echo e(number_format($total_incomes, 2)); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php else: ?>
    <div class="empty-state">No se registraron ingresos en esta sesion.</div>
    <?php endif; ?>

    
    <?php if(count($payment_method_summary) > 0): ?>
    <div class="section-title">Resumen por Metodo de Pago</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 60%;">Metodo</th>
                <th style="width: 20%;" class="text-center">Operaciones</th>
                <th style="width: 20%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payment_method_summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($pm['method']); ?></td>
                <td class="text-center"><?php echo e($pm['count']); ?></td>
                <td class="text-right">S/ <?php echo e(number_format($pm['total'], 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    
    <div class="section-title">Detalle de Gastos</div>
    <?php if(count($expenses) > 0): ?>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 20%;">Tipo</th>
                <th style="width: 30%;">Descripcion</th>
                <th style="width: 15%;">Metodo Pago</th>
                <th style="width: 15%;">Fecha</th>
                <th style="width: 15%;" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($i + 1); ?></td>
                <td><?php echo e($expense['type']); ?></td>
                <td><?php echo e($expense['description']); ?></td>
                <td><?php echo e($expense['payment_method']); ?></td>
                <td><?php echo e($expense['date']); ?></td>
                <td class="text-right">S/ <?php echo e(number_format($expense['amount'], 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Total Gastos</td>
                <td class="text-right">S/ <?php echo e(number_format($total_expenses, 2)); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php else: ?>
    <div class="empty-state">No se registraron gastos en esta sesion.</div>
    <?php endif; ?>

    
    <?php if($notes): ?>
    <div class="observations">
        <strong>Observaciones:</strong> <?php echo e($notes); ?>

    </div>
    <?php endif; ?>

    <div class="footer-note">
        Documento generado el <?php echo e($generated_at); ?><br>
        Generado por: <?php echo e($generated_by); ?><br>
        Este documento es un reporte interno de cierre de caja. No tiene validez tributaria.
    </div>

</body>
</html>
<?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/cash-sessions/summary.blade.php ENDPATH**/ ?>