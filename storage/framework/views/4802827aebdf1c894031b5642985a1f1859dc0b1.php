<?php $__env->startSection('content'); ?>
    <div class=" col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Détails de la commande</div>
            <div class="panel-body">
                <p>

                <p>Date de la commande : <?php echo e($order_info['created_at']); ?></p>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom de la boisson</th>
                        <th>Prix de la boisson</th>
                        <th>Quantité</th>
                        <th>Taxe</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($category); ?></strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->itemName); ?></td>
                                <?php if($item->HHStatus == 0): ?>
                                    <td><?php echo e($item->itemPrice); ?> € TTC</td>
                                <?php else: ?>
                                    <td><?php echo e($item->itemHHPrice); ?> € TTC</td>
                                <?php endif; ?>
                                <td><?php echo e($item->quantity); ?> consommation(s)</td>
                                <td><?php echo e($item->tax); ?> %</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong><?php echo e($sum); ?> € TTC</strong></td>
                        <td><strong><?php echo e($quantity); ?> consommation(s)</strong></td>
                        <td><strong><?php echo e($tax_excluding_sum); ?> € HT</strong></td>
                    </tr>
                    </tfoot>
                </table>
                <p>Commande en happy hour :
                    <?php if($order_info->HHStatus == 1): ?>
                        Oui
                    <?php else: ?>
                        Non
                    <?php endif; ?>
                </p>
                <p>Status :
                    <?php if($order_info->accepted == 1): ?>
                        Acceptée
                    <?php elseif($order_info->accepeted == 0): ?>
                        Refusée
                    <?php else: ?>
                        En attente
                    <?php endif; ?>
                </p>
                <p>Délivrée :
                <?php if($order_info->delivered == 1): ?>
                    Oui
                <?php else: ?>
                    Non
                    <?php endif; ?>
                </p>
                <p>Incident :
                <?php if($order_info->incident == 1): ?>
                    Oui
                <?php else: ?>
                    Non
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <a href="javascript:history.back()" class="btn btn-default">
            <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>