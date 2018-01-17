<?php $__env->startSection('content'); ?>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Création d'un utilisateur</div>
            <div class="panel-body">
                <div class="col-sm-12">

                    <?php echo Form::open(['route' => 'sipperUser.store', 'class' => 'form-horizontal panel', 'files' => true]); ?>


                    <div class="form-group <?php echo $errors->has('picture') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('picture', 'Photo de profil')); ?>

                        <div class="input-group"><label class="input-group-btn"><span class="btn btn-default">Choisir&hellip;<div style="display: none"><?php echo e(Form::file('picture')); ?></div></span></label>
                            <input type="text" class="form-control" readonly>
                        </div>
                        <?php echo $errors->first('picture', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('firstName') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('firstName', 'Prénom')); ?>

                        <?php echo Form::text('firstName', null, ['class' => 'form-control', 'placeholder' => 'Prénom']); ?>

                        <?php echo $errors->first('firstName', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('lastName') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('lastName', 'Nom')); ?>

                        <?php echo Form::text('lastName', null, ['class' => 'form-control', 'placeholder' => 'Nom']); ?>

                        <?php echo $errors->first('lastName', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('email') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('email', 'E-mail')); ?>

                        <?php echo Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail']); ?>

                        <?php echo $errors->first('email', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('tel') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('tel', 'Téléphone')); ?>

                        <?php echo Form::text('tel', null, ['class' => 'form-control', 'placeholder' => 'Téléphone']); ?>

                        <?php echo $errors->first('tel', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('birthday') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('birthday', 'Date de naissance')); ?>

                        <?php echo Form::date('birthday', null, ['class' => 'form-control', 'placeholder' => 'Date de naissance']); ?>

                        <?php echo $errors->first('birthday', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('password') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('password', 'Mot de passe')); ?>

                        <?php echo Form::password('password', ['class' => 'form-control', 'placeholder' => 'Mot de passe']); ?>

                        <?php echo $errors->first('password', '<small class="help-block">:message</small>'); ?>

                    </div>
                    <div class="form-group <?php echo $errors->has('password_confirmation') ? 'has-error' : ''; ?>">
                        <?php echo Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirmer le mot de passe']); ?>

                        <?php echo $errors->first('password_confirmation', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <?php echo Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']); ?>

                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
        <a href="<?php echo e(route('sipperUser.index')); ?>" class="btn btn-default">
            <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        //Button
        $(function() {

            // We can attach the `fileselect` event to all file inputs on the page
            $(document).on('change', ':file', function() {
                var input = $(this),
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });

            // We can watch for our custom `fileselect` event like this
            $(document).ready( function() {
                $(':file').on('fileselect', function(event, numFiles, label) {

                    var input = $(this).parents('.input-group').find(':text'),
                            log = numFiles > 1 ? numFiles + ' fichiers sélectionnés' : label;

                    if( input.length ) {
                        input.val(log);
                    } else {
                        if( log ) alert(log);
                    }

                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>