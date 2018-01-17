<?php $__env->startSection('content'); ?>
    <div class=" col-sm-9">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">Fiche d'utilisateur</div>
            <div class="panel-body">
                <div class="col-sm-6">
                    <p>
                    <?php if($sipperUser->picture != null): ?>
                        <div class="avatar-circle">
                            <img style="width: 100px; height: 100px; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;" src="<?php echo e(asset('uploads/sipper_users_img/' . $sipperUser->picture)); ?>" />
                        </div>
                    <?php else: ?>
                        <span class="glyphicon glyphicon-user"><i>   -   Pas de photo de profil</i></span>
                    <?php endif; ?>
                    <br />
                    <p>Date d'inscription : <?php echo e($sipperUser->created_at); ?></p>
                    <p>Dernière modification : <?php echo e($sipperUser->updated_at); ?></p>

                    <p>Prénom : <?php echo e($sipperUser->firstName); ?></p>
                    <p>Nom : <?php echo e($sipperUser->lastName); ?></p>
                    <p>Email : <?php echo e($sipperUser->email); ?></p>
                    <p>Téléphone : <?php echo e($sipperUser->tel); ?></p>
                    <p>Date de naissance: <?php echo e($sipperUser->birthday); ?></p>
                    <p>Actif :
                        <?php if($sipperUser->activated == 1): ?>
                            Actif
                        <?php else: ?>
                            Inactif
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo e(route('sipperUser.orders_list', $sipperUser->id)); ?>" class="btn btn-default">
                        <span class="glyphicon glyphicon-barcode"></span><span>  Historique des commandes</span>
                    </a>
                </div>
            </div>
        </div>
        <a href="javascript:history.back()" class="btn btn-default">
            <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>