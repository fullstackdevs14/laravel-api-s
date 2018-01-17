<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']})

    google.charts.setOnLoadCallback(draw<?php echo e($model->id); ?>)

    function draw<?php echo e($model->id); ?>() {
        var data = google.visualization.arrayToDataTable([
            [
                '',
                <?php for($i = 0; $i < count($model->datasets); $i++): ?>
                    "<?php echo e($model->datasets[$i]['label']); ?>",
                <?php endfor; ?>
            ],

            <?php for($l = 0; $l < count($model->labels); $l++): ?>
                [
                    "<?php echo e($model->labels[$l]); ?>",
                    <?php for($i = 0; $i < count($model->datasets); $i++): ?>
                        <?php echo e($model->datasets[$i]['values'][$l]); ?>,
                    <?php endfor; ?>
                ],
            <?php endfor; ?>
        ])

        var options = {
            chart: {
              <?php if($model->title): ?>
                title: "<?php echo $model->title; ?>",
              <?php endif; ?>
            },
            <?php if($model->colors): ?>
                colors: [
                    <?php $__currentLoopData = $model->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        "<?php echo e($c); ?>",
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
            <?php endif; ?>
        };

        var <?php echo e($model->id); ?> = new google.charts.Bar(document.getElementById("<?php echo e($model->id); ?>"))

        <?php echo e($model->id); ?>.draw(data, options)
    }
</script>

<?php echo $__env->make('charts::_partials.container.div', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
