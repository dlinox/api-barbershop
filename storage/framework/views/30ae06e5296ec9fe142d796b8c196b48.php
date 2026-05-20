<html>

<head>
    <?php echo $__env->make('enrollments.common.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body>

    
    <div class="section-title">Datos del Estudiante</div>
    <table class="info-table">
        <tr>
            <td class="label">Nombre completo</td>
            <td class="value" colspan="3"><?php echo e($student_full_name); ?></td>
        </tr>
        <tr>
            <td class="label">Documento</td>
            <td class="value"><?php echo e($document_type); ?>: <?php echo e($document_number); ?></td>
            <td class="label">F. Nacimiento</td>
            <td class="value"><?php echo e($date_birth ?? '—'); ?></td>
        </tr>
        <tr>
            <td class="label">Género</td>
            <td class="value"><?php echo e($gender ?? '—'); ?></td>
            <td class="label">Teléfono</td>
            <td class="value"><?php echo e($phone ?? '—'); ?></td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="value" colspan="3"><?php echo e($email ?? '—'); ?></td>
        </tr>
        <tr>
            <td class="label">Dirección</td>
            <td class="value" colspan="3"><?php echo e($address ?? '—'); ?></td>
        </tr>
    </table>

    
    <?php if(!empty($guardians)): ?>
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
            <?php $__currentLoopData = $guardians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guardian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($guardian['full_name']); ?></td>
                <td><?php echo e($guardian['kinship']); ?></td>
                <td><?php echo e($guardian['phone']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    
    <div class="section-title">Datos de Matrícula</div>
    <table class="info-table">
        <tr>
            <td class="label">Grupo</td>
            <td class="value" colspan="3"><?php echo e($group_name); ?></td>
        </tr>
        <tr>
            <td class="label">Nivel</td>
            <td class="value"><?php echo e($level_name); ?></td>
            <td class="label">Turno</td>
            <td class="value"><?php echo e($schedule_shift); ?></td>
        </tr>
        <tr>
            <td class="label">Horario</td>
            <td class="value"><?php echo e($schedule_time); ?></td>
            <td class="label">Días</td>
            <td class="value"><?php echo e($days_of_week); ?></td>
        </tr>
        <tr>
            <td class="label">Inicio</td>
            <td class="value"><?php echo e($start_date); ?></td>
            <td class="label">Fin</td>
            <td class="value"><?php echo e($end_date); ?></td>
        </tr>
        <tr>
            <td class="label">Fecha matrícula</td>
            <td class="value"><?php echo e($enrollment_date); ?></td>
            <td class="label">Estado</td>
            <td class="value"><?php echo e($enrollment_status); ?></td>
        </tr>
    </table>

    
    <?php if(!empty($payment_plans)): ?>
    <div class="section-title">Plan de Pagos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 20%;">Concepto</th>
                <th style="width: 20%;">Desde</th>
                <th style="width: 20%;">Hasta</th>
                <th style="width: 15%;" class="text-right">Monto</th>
                <th style="width: 17%;" class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php $__currentLoopData = $payment_plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $total += $plan['amount']; ?>
            <tr>
                <td class="text-center"><?php echo e($i + 1); ?></td>
                <td><?php echo e($plan['type']); ?></td>
                <td><?php echo e($plan['start_date']); ?></td>
                <td><?php echo e($plan['end_date']); ?></td>
                <td class="text-right">S/ <?php echo e(number_format($plan['amount'], 2)); ?></td>
                <td class="text-center">
                    <span style="color: <?php echo e($plan['is_paid'] ? '#27ae60' : '#c0392b'); ?>; font-weight: bold;">
                        <?php echo e($plan['is_paid'] ? 'Pagado' : 'Pendiente'); ?>

                    </span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td colspan="4" class="text-right" style="font-weight: bold;">Total</td>
                <td class="text-right" style="font-weight: bold;">S/ <?php echo e(number_format($total, 2)); ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>

    
    <?php if(!empty($materials)): ?>
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
            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($i + 1); ?></td>
                <td><?php echo e($material['name']); ?></td>
                <td class="text-center"><?php echo e($material['quantity']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    
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

</body>

</html><?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/enrollments/registration-certificate.blade.php ENDPATH**/ ?>