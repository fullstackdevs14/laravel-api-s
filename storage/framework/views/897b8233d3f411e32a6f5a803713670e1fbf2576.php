<?php $__env->startSection('content'); ?>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Liste des commandes de l'utilisateur</div>
            <div class="panel-body">
                <div class="col-sm-12">

                    <?php if(session()->has('message')): ?>
                        <div class="alert alert-success alert-dismissible"><?php echo session('message'); ?></div>
                    <?php endif; ?>

                    <div>
                        <?php echo Form::open(['method' => 'GET', 'route' => ['sipperUser.orders_list', $id], 'class' => 'form-horizontal', 'role' => 'search']); ?>


                        <div class="input-group">
                            <?php echo Form::text('search', '', ['class' => 'form-control', 'placeholder' => 'Chercher une commande (numéro de commande uniquement)']); ?>

                            <span class="input-group-btn"><button class="btn btn-default" type="submit">Chercher</button></span>
                        </div>
                        <?php echo Form::close(); ?>

                        <br/>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>N° commande</th>
                                <th>Date</th>
                                <th>Happy Hour</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $orders_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order['orderId']); ?></td>
                                    <td><?php echo e($order['created_at']); ?></td>
                                    <?php if($order['HHStatus'] == 0): ?>
                                        <td>Hors HH</td>
                                    <?php else: ?>
                                        <td>En HH</td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if($order['incident'] === 1): ?>
                                            Incident
                                        <?php elseif($order['delivered'] === 1): ?>
                                            Délivrée
                                        <?php elseif($order['accepted'] === 1): ?>
                                            Refusée
                                        <?php elseif($order['accepted'] === 0): ?>
                                            Acceptée
                                        <?php else: ?>
                                            En attente
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e(link_to_route('order.show', 'Voir', [$order->id], ['class' => 'btn btn-default'])); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo $links; ?>

                </div>
            </div>
        </div>
        <a href="javascript:history.back()" class="btn btn-default">
            <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>