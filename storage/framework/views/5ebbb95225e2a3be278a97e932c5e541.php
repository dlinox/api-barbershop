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
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.data-table th { background-color: #e8e8e8; padding: 4px 6px; font-size: 10px; text-transform: uppercase; text-align: left; border: 1px solid #ccc; }
    table.data-table td { padding: 4px 6px; border: 1px solid #ccc; font-size: 10.5px; }
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

    
    <div class="section-title">Datos Personales</div>
    <table class="info-table">
        <tr>
            <td class="label">Nombre completo</td>
            <td class="value" colspan="3"><?php echo e($full_name); ?></td>
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

    
    <div class="section-title">Datos del Docente</div>
    <table class="info-table">
        <tr>
            <td class="label">Sede / Sucursal</td>
            <td class="value" colspan="3"><?php echo e($branch_name); ?></td>
        </tr>
        <tr>
            <td class="label">Tipo de Pago</td>
            <td class="value"><?php echo e($payment_type); ?></td>
            <td class="label">Salario Mensual</td>
            <td class="value"><?php echo e($monthly_salary ? 'S/ ' . number_format($monthly_salary, 2) : '—'); ?></td>
        </tr>
        <tr>
            <td class="label">Estado</td>
            <td class="value" colspan="3">
                <span class="<?php echo e($is_active ? 'badge-active' : 'badge-inactive'); ?>">
                    <?php echo e($is_active ? 'Activo' : 'Inactivo'); ?>

                </span>
            </td>
        </tr>
    </table>

    
    <?php if(!empty($groups)): ?>
    <div class="section-title">Grupos Asignados (activos)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 40%;">Grupo</th>
                <th style="width: 25%;">Nivel</th>
                <th style="width: 15%;">Desde</th>
                <th style="width: 12%;" class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($i + 1); ?></td>
                <td><?php echo e($group['name']); ?></td>
                <td><?php echo e($group['level_name']); ?></td>
                <td><?php echo e($group['start_date']); ?></td>
                <td class="text-center"><?php echo e($group['status']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    
    <table class="signatures">
        <tr>
            <td>
                <span class="line">Firma del Docente</span>
            </td>
            <td>
                <span class="line">Firma y Sello de la Academia</span>
            </td>
        </tr>
    </table>

</body>

</html>
<?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/teachers/profile.blade.php ENDPATH**/ ?>