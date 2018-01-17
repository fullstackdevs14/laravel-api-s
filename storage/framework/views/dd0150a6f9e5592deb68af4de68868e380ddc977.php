<?php $__env->startSection('content'); ?>
    <div class="col-md-9">
        <?php if(session()->has('ok')): ?>
            <div class="alert alert-success alert-dismissible"><?php echo session('ok'); ?></div>
        <?php endif; ?>

        <div>
            <?php echo Form::open(['method' => 'GET', 'route' => 'sipperUser.index', 'class' => 'form-horizontal', 'role' => 'search']); ?>


            <div class="input-group">
                <?php echo Form::text('search', '', ['class' => 'form-control', 'placeholder' => 'Chercher un utilisateur']); ?>

                <span class="input-group-btn"><button class="btn btn-default" type="submit">Chercher</button></span>
            </div>
            <?php echo Form::close(); ?>

            <br/>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Liste des utilisateurs</h3>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actif</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $sipperUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="vertical-align: middle"><?php echo $user->id; ?></td>
                            <td style="vertical-align: middle">
                                <?php if($user->photoPath != null): ?>
                                    <div class="avatar">
                                        <img src="<?php echo $user->photoPath; ?>}}" />
                                    </div>
                                <?php else: ?>
                                    <span class="glyphicon glyphicon-user"></span>
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle" class="text-default"><?php echo e($user->firstName); ?></td>
                            <td style="vertical-align: middle" class="text-default"><?php echo e($user->lastName); ?></td>
                            <td style="vertical-align: middle" class="text-default"><?php echo e($user->email); ?></td>
                            <td style="vertical-align: middle" class="text-default">
                                <?php if($user->activated): ?>
                                    Actif
                                <?php else: ?>
                                    Inactif
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle"><?php echo e(link_to_route('sipperUser.show', 'Voir', [$user->id], ['class' => 'btn btn-default'])); ?></td>
                            <td style="vertical-align: middle"><?php echo e(link_to_route('sipperUser.edit', 'Modifier', [$user->id], ['class' => 'btn btn-default'])); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo $links; ?>

        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>