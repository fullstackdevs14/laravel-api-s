<?php $__env->startSection('content'); ?>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Tableau de bord</div>

        <div class="panel-body">
            Vous êtes connecté !
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>