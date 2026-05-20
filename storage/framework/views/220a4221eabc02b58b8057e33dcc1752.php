<?php echo $__env->make('incomes.common.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            A. DATOS DE LA UNIDAD DE NEGOCIO Y RESPONSABLE
        </th>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="30%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">UNIDAD DE NEGOCIO:</td>
        <td width="70%" style="padding: 4px;"><?php echo e(strtoupper($branch_name)); ?></td>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000;">
        <td width="10%" style="border-right: 1px solid #000; padding: 4px; font-weight: bold;">RESPONSABLE:</td>
        <td width="40%" style="padding: 4px;"><?php echo e(strtoupper($worker_name)); ?></td>
        <td width="10%" style="border-left: 1px solid #000; padding: 4px; font-weight: bold;">CARGO:</td>
        <td width="40%" style="padding: 4px; border-left: 1px solid #000;"><?php echo e(strtoupper($worker_position)); ?></td>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    <tr style="border: 1px solid #000; background-color: #ffe598;">
        <th style="text-align: left !important; padding: 4px;">
            B. DATOS DE LOS INGRESOS
        </th>
    </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif;">
    
    <tr style="border: 1px solid #000; font-size: 9px; font-weight: bold; text-align: center;">
        <th style="padding: 4px; border: 1px solid #000; width: 4%;">N°</th>
        <th colspan="2" style="padding: 4px; border: 1px solid #000; width: 24%;">DESCRIPCIÓN DEL INGRESO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">COMPROBANTE <br> EMITIDO <br><span style="font-weight: normal; font-size: 7px;">Factura, Boleta ó Recibo</span></th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">SERIE-NÚMERO</th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">MEDIO DE <br> PAGO <br><span style="font-weight: normal; font-size: 7px;">Efectivo ó Yape/ Plin/ Transf.</span></th>
        <th style="padding: 4px; border: 1px solid #000; width: 14%;">NÚMERO DE <br> OPERACIÓN <br><span style="font-weight: normal; font-size: 7px;">De Yape, Plin o Transferencia</span></th>
        <th style="padding: 4px; border: 1px solid #000; width: 12%;">IMPORTE</th>
    </tr>

    
    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;"><?php echo e($row['number']); ?></td>
        <td colspan="2" style="padding: 4px; border: 1px solid #000;"><?php echo e($row['description']); ?></td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;"><?php echo e($row['receipt_type']); ?></td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;"><?php echo e($row['receipt_number']); ?></td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;"><?php echo e($row['payment_method']); ?></td>
        <td style="padding: 4px; border: 1px solid #000; text-align: center;"><?php echo e($row['operation_number']); ?></td>
        <td style="padding: 4px; border: 1px solid #000; text-align: right;">S/ <?php echo e(number_format($row['amount'], 2)); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php for($i = count($rows) + 1; $i <= max(20, count($rows)); $i++): ?>
        <tr style="border: 1px solid #000; font-size: 9px;">
        <td style="padding: 4px; border: 1px solid #000; text-align: center;"><?php echo e($i); ?></td>
        <td colspan="2" style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        <td style="padding: 4px; border: 1px solid #000;">&nbsp;</td>
        </tr>
    <?php endfor; ?>

        
        <tr style="font-size: 9px; font-weight: bold;">
            <td style="border: none;"></td>
            <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">TOTAL EN EFECTIVO</td>
            <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">S/ <?php echo e(number_format($total_cash, 2)); ?></td>
            <td rowspan="2" colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; vertical-align: middle; font-size: 14px; font-weight: bold;">TOTAL DÍA:</td>
            <td rowspan="2" style="padding: 4px; border: 1px solid #000; text-align: right; background-color: #ffe598; vertical-align: middle; font-size: 14px; font-weight: bold;">S/ <?php echo e(number_format($total_day, 2)); ?></td>
        </tr>
        <tr style="font-size: 9px; font-weight: bold;">
            <td style="border: none;"></td>
            <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">TOTAL BANCARIZADO</td>
            <td colspan="2" style="padding: 4px; border: 1px solid #000; text-align: right;">S/ <?php echo e(number_format($total_bank, 2)); ?></td>
        </tr>
        <tr style="font-size: 9px; font-weight: bold;">
            <td style="border: none;"></td>
            <td colspan="7" style="padding: 4px; border: none; text-align: left;">*Los pagos bancarizados incluyen Yape, Plin y Transferencias</td>
        </tr>
</table>

<table width="100%" style="border-collapse: collapse; font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 12px;">
    <tr style="border: 1px solid #000;">
        <th style="text-align: center; padding: 4px;">
            ORDEN DE DEPÓSITO EN EFECTIVO A CUENTA BANCARIA
        </th>
    </tr>
    <tr>
        <td style="padding: 8px; font-size: 10px;">
            Mediante la presente, se ordena el depósito del importe total en efectivo por S/<?php echo e(number_format($total_cash, 2)); ?> correspondiente al ingreso del día <?php echo e($report_date); ?>, en la cuenta corriente N°4052627982080 del Banco de Crédito BCP, del titular: <?php echo e($company->name ?? 'N/A'); ?>. A continuación pegue el/los voucher/vouchers dentro del siguiente espacio en blanco para dar por finalizado su Registro de Ingresos Diarios de Escuela.
        </td>
    </tr>
</table>


<table width="100%" style="font-family: 'Helvetica Neue', Arial, sans-serif; margin-top: 80px;">
    <tr>
        <td width="25%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style=" padding-top: 4px; font-size: 9px; font-weight: bold;">
                <span>
                    RESPONSABLE
                    <br>
                    <?php echo e(strtoupper($worker_name)); ?>

                </span>
            </div>
        </td>
        <td></td>
        <td width="25%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style=" padding-top: 4px; font-size: 9px; font-weight: bold;">
                V° B° GERENCIA
            </div>
        </td>
        <td></td>
        <td width="25%" style="text-align: center; padding: 0 10px; border-top: 1px solid #000;">
            <div style=" padding-top: 4px; font-size: 9px; font-weight: bold;">
                V° B° CONTROL INTERNO
            </div>
        </td>
    </tr>
</table>
<?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/reports/academy/income-per-day.blade.php ENDPATH**/ ?>