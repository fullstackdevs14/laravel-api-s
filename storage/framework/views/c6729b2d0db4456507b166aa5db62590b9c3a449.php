<?php $__env->startSection('content'); ?>
    <div class="col-md-9">
        <?php if(session()->has('ok')): ?>
            <div class="alert alert-success alert-dismissible"><?php echo session('ok'); ?></div>
        <?php endif; ?>

        <div>
            <?php echo Form::open(['method' => 'GET', 'route' => 'order.index', 'class' => 'form-horizontal', 'role' => 'search']); ?>


            <div class="input-group">
                <?php echo Form::text('search', '', ['class' => 'form-control', 'placeholder' => 'Chercher une commande (numéro de commande uniquement)']); ?>

                <span class="input-group-btn"><button class="btn btn-default" type="submit">Chercher</button></span>
            </div>
            <?php echo Form::close(); ?>

            <br/>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Liste des commandes</h3>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>N° de commande</th>
                        <th>Date</th>
                        <th>Acceptée</th>
                        <th>Délivrée</th>
                        <th>Incident</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="vertical-align: middle"><?php echo $order->id; ?></td>
                            <td style="vertical-align: middle"><?php echo $order->orderId; ?></td>
                            <td style="vertical-align: middle"><?php echo $order->created_at; ?></td>
                            <?php if($order->accepted == 1): ?>
                                <td style="vertical-align: middle">Acceptée</td>
                            <?php elseif($order->accepted == 0): ?>
                                <td style="vertical-align: middle">Refusée</td>
                            <?php else: ?>
                                <td style="vertical-align: middle">En attente</td>
                            <?php endif; ?>
                            <?php if( $order->delivered == 1): ?>
                                <td style="vertical-align: middle">Délivrée</td>
                            <?php else: ?>
                                <td style="vertical-align: middle">Non délivrée</td>
                            <?php endif; ?>
                            <?php if($order->incident == 1): ?>
                                <td style="vertical-align: middle">Oui</td>
                            <?php else: ?>
                                <td style="vertical-align: middle">Non</td>
                            <?php endif; ?>
                            <td style="vertical-align: middle"><?php echo e(link_to_route('order.show', 'Voir', [$order->id], ['class' => 'btn btn-default'])); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo $links; ?>

        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>