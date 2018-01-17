<?php $__env->startSection('content'); ?>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Modification du menu</div>
            <div class="panel-body">
                <div class="col-sm-12">

                    <div class="row">
                        <div class="col-sm-2 col-sm-offset-9">
                            <span style="vertical-align: middle"><?php echo e(link_to_route('item.create', 'Ajouter une boisson', [$partner->id], ['class' => 'btn btn-default'])); ?></span>
                            <br />
                            <br />
                        </div>
                    </div>

                <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo e($category); ?></div>
                            <div class="panel-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Prix</th>
                                        <th>Prix HH</th>
                                        <th>Disponibilité</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="vertical-align: middle"><?php echo e($item->id); ?></td>
                                            <td style="vertical-align: middle"><?php echo e($item->name); ?></td>
                                            <td style="vertical-align: middle"><?php echo e($item->price); ?> €</td>
                                            <td style="vertical-align: middle"><?php echo e($item->HHPrice); ?> €</td>
                                            <td style="vertical-align: middle"><?php echo e($item->availability); ?></td>
                                            <td style="vertical-align: middle">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['item.destroy', $partner->id, $item->id]]); ?>

                                                <?php echo Form::submit('Supprimer', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment supprimer cette boisson ?\')']); ?>

                                                <?php echo Form::close(); ?>

                                            </td>
                                            <td style="vertical-align: middle"><?php echo e(link_to_route('item.edit', 'Modifier', [$partner->id, $item->id], ['class' => 'btn btn-default'])); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <a href="<?php echo e(route('partner.edit', $partner->id)); ?>" class="btn btn-default">
            <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>