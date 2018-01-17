<?php $__env->startSection('content'); ?>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Modification d'un utilisateur</div>
            <div class="panel-body">
                <div class="col-sm-12">
                    <?php echo Form::model($sipperUser, ['route' => ['sipperUser.update', $sipperUser->id], 'method' => 'put', 'class' => 'form-horizontal panel', 'files' => true]); ?>


                    <?php if($sipperUser->picture != null): ?>
                        <div class="avatar-circle">
                            <img style="width: 100px; height: 100px; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;" src="<?php echo e(asset('uploads/sipper_users_img/' . $sipperUser->picture)); ?>" />
                        </div>
                    <?php else: ?>
                        <span class="glyphicon glyphicon-user"><i>   -   Pas de photo de profil</i></span>
                    <?php endif; ?>
                    <br />

                    <div class="form-group <?php echo $errors->has('picture') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('picture', 'Photo de profil')); ?>

                        <div class="input-group"><label class="input-group-btn"><span class="btn btn-default">Choisir&hellip;<div style="display: none"><?php echo e(Form::file('picture')); ?></div></span></label>
                            <input type="text" class="form-control" readonly>
                        </div>
                        <?php echo $errors->first('picture', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <p>Dernière modification : <?php echo e($sipperUser->updated_at); ?></p>
                    <p>Création : <?php echo e($sipperUser->created_at); ?></p>

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
                        <?php echo e(Form::label('email', 'Email')); ?>

                        <?php echo Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']); ?>

                        <?php echo $errors->first('email', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('tel') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('tel', 'Téléphone')); ?>

                                <!--<input class="form-control" placeholder="Téléphone" name="tel" type="tel">-->
                        <?php echo Form::text('tel', null, ['class' => 'form-control', 'placeholder' => 'Téléphone']); ?>

                        <?php echo $errors->first('tel', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('birthday') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('birthday', 'Date de naissance')); ?>

                        <?php echo Form::date('birthday', null, ['class' => 'form-control', 'placeholder' => 'Date de naissance']); ?>

                        <?php echo $errors->first('birthday', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('activated') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('activated', 'Utilisateur actif / inactif')); ?>

                        <select id="activated" name="activated" class="form-control form-control-lg">
                            <?php if($sipperUser->activated == 1): ?>
                                <option value="1" selected="selected">Actif</option>
                                <option value="0">Inactif</option>
                            <?php else: ?>
                                <option value="1">Actif</option>
                                <option value="0" selected="selected">Inactif</option>
                            <?php endif; ?>
                        </select>

                        <?php echo $errors->first('activated', '<small class="help-block">:message</small>'); ?>

                    </div>


                    <?php echo Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']); ?>



                    <?php echo Form::close(); ?>


                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['sipperUser.destroy', $sipperUser->id]]); ?>

                    <?php echo Form::submit('Supprimer l\'utilisateur', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment supprimer cet utilisateur ?\')']); ?>

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