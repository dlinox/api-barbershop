<table style="width: 100%; font-family: 'Helvetica Neue', Arial, sans-serif; border-bottom: 2px solid #1a1a2e; padding-bottom: 6px;">
    <tr>
        
        <td style="width: 10%; text-align: left; vertical-align: middle;">
            <?php
                $sede = $branch ?? \App\Common\Helpers\PdfLogoHelper::branchFromInfrastructure($infrastructure ?? null);
                $logoPath = \App\Common\Helpers\PdfLogoHelper::resolve($sede, $company ?? null);
            ?>
            <?php if($logoPath): ?>
                <img src="<?php echo e($logoPath); ?>" style="max-height: 80px; max-width: 150px;" />
            <?php endif; ?>
        </td>
        <td style="width: 65%; text-align: left; vertical-align: middle;">
            <div style="font-size: 14px; font-weight: bold; color: #1a1a2e;">
                <?php echo e($sede?->name ?? $company->trade_name ?? $company->name ?? 'Mi Empresa'); ?>

            </div>
            <div style="font-size: 8.5px; color: #666; line-height: 1.5;">
                <?php if($sede?->name): ?><?php echo e($company->trade_name ?? $company->name ?? 'Mi Empresa'); ?><br><?php endif; ?>
                <?php if($company?->ruc): ?>RUC: <?php echo e($company->ruc); ?><br><?php endif; ?>
                <?php if($company?->address): ?><?php echo e($company->address); ?><br><?php endif; ?>
                <?php if($company?->phone): ?>Tel: <?php echo e($company->phone); ?><?php endif; ?>
            </div>
        </td>

        
        <td style="width: 25%; text-align: center; vertical-align: middle; border: 2px solid #1a1a2e; padding: 6px 10px;">
            <span style="font-size: 10px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px;">
                Comprobante de Ingreso
            </span>
            <br>
            <span style="font-size: 12px; font-weight: bold; color: #c0392b; letter-spacing: 1px;">
                <?php echo e($receipt_full_number); ?>

            </span>
        </td>
    </tr>
</table><?php /**PATH /var/www/html/gruposamanez/api/resources/pdf/templates/incomes/common/header.blade.php ENDPATH**/ ?>