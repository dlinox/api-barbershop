<table style="width: 100%; font-family: 'Helvetica Neue', Arial, sans-serif; border-bottom: 2px solid #1a1a2e; padding-bottom: 6px;">
    <tr>
        <td style="width: 10%; text-align: left; vertical-align: middle;">
            <?php if($company?->logo): ?>
                <img src="<?php echo e(public_path('storage/company_logos/' . basename($company->logo))); ?>" style="max-height: 80px; max-width: 150px;" />
            <?php endif; ?>
        </td>
        <td style="width: 65%; text-align: left; vertical-align: middle;">
            <div style="font-size: 14px; font-weight: bold; color: #1a1a2e;">
                <?php echo e($company->trade_name ?? $company->name ?? 'Mi Empresa'); ?>

            </div>
            <div style="font-size: 8.5px; color: #666; line-height: 1.5;">
                <?php if($company?->ruc): ?>RUC: <?php echo e($company->ruc); ?><br><?php endif; ?>
                <?php if($company?->address): ?><?php echo e($company->address); ?><br><?php endif; ?>
                <?php if($company?->phone): ?>Tel: <?php echo e($company->phone); ?><?php endif; ?>
            </div>
        </td>
        <td style="width: 25%; text-align: center; vertical-align: middle; border: 2px solid #1a1a2e; padding: 6px 10px;">
            <span style="font-size: 10px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px;">
                Ficha del Docente
            </span>
            <br>
            <span style="font-size: 12px; font-weight: bold; color: #1a1a2e; letter-spacing: 1px;">
                N° <?php echo e(str_pad($teacher_id, 6, '0', STR_PAD_LEFT)); ?>

            </span>
        </td>
    </tr>
</table>
<?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/teachers/header.blade.php ENDPATH**/ ?>