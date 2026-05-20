<?php echo $__env->make('reports.common.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


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
        <td width="35%" style="padding: 4px;"><?php echo e(strtoupper($group_name)); ?></td>
        <td width="15%" style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">SEDE:</td>
        <td width="35%" style="padding: 4px; border-left: 1px solid #000;"><?php echo e(strtoupper($branch_name)); ?></td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">NIVEL:</td>
        <td style="padding: 4px;"><?php echo e(strtoupper($level_name)); ?></td>
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">HORARIO:</td>
        <td style="padding: 4px; border-left: 1px solid #000;"><?php echo e($schedule_time); ?></td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">AULA:</td>
        <td style="padding: 4px;"><?php echo e(strtoupper($room_name)); ?></td>
        <td style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">TOTAL:</td>
        <td style="padding: 4px; border-left: 1px solid #000;"><?php echo e($student_count); ?> alumnos matriculados</td>
    </tr>
</table>


<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 8px;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. LISTA DE ALUMNOS (<?php echo e($student_count); ?> matriculados)
        </th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    
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

    
    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e($index + 1); ?></td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e($student['document_number']); ?></td>
        <td style="padding: 3px; border: 1px solid #000;"><?php echo e(strtoupper($student['paternal_surname'] . ' ' . $student['maternal_surname'] . ', ' . $student['name'])); ?></td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e($student['phone']); ?></td>
        <td style="padding: 3px; border: 1px solid #000; font-size: 8px;"><?php echo e($student['email']); ?></td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e(\Carbon\Carbon::parse($student['enrollment_date'])->format('d/m/Y')); ?></td>
        <td style="padding: 3px; border: 1px solid #000;"><?php echo e($student['guardian_name']); ?></td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e($student['guardian_kinship']); ?></td>
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e($student['guardian_phone']); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php for($i = count($students) + 1; $i <= max(20, count($students)); $i++): ?>
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 3px; border: 1px solid #000; text-align: center;"><?php echo e($i); ?></td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 3px; border: 1px solid #000;">&nbsp;</td>
    </tr>
    <?php endfor; ?>
</table>
<?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/reports/academy/student-list-by-group.blade.php ENDPATH**/ ?>