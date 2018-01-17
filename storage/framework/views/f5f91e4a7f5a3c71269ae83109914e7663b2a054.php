<?php $__env->startSection('head'); ?>
    <?php echo Charts::assets(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Tableau de bord</div>

            <div class="panel-body">





                <div>
                    <?php echo $chart->render(); ?>

                </div>




            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>