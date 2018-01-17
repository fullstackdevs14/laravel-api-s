<?php $__env->startSection('content'); ?>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Modification d'un partenaire</div>
            <div class="panel-body">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2 col-sm-offset-9">
                            <span style="vertical-align: middle"><?php echo e(link_to_route('openings.edit', 'Horaires d\'ouverture', [$partner->id], ['class' => 'btn btn-default'])); ?></span>
                            <br />
                            <br />
                            <span style="vertical-align: middle"><?php echo e(link_to_route('menus.edit', 'Menu', [$partner->id], ['class' => 'btn btn-default'])); ?></span>
                        </div>
                    </div>

                    <br />

                    <?php echo Form::model($partner, ['route' => ['partner.update', $partner->id], 'method' => 'put', 'class' => 'form-horizontal panel', 'files' => true]); ?>


                    <div class="panel panel-default">
                        <div class="panel-heading">Création / modification :</div>
                        <div class="panel-body">
                            <p>Date d'inscription : <?php echo e($partner->created_at); ?></p>
                            <p>Dernière modification : <?php echo e($partner->updated_at); ?></p>
                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-heading">Visibilité dans l'application :</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <img style="width: 100%" class="img-responsive" src="<?php echo e(asset('uploads/partners_img/' . $partner->picture)); ?>">
                                </div>
                            </div>

                            <br />

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('picture') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('picture', 'Image pour l\'application')); ?>

                                        <div class="input-group">
                                            <label class="input-group-btn"><span class="btn btn-default">Choisir&hellip;<div style="display: none"><?php echo e(Form::file('picture')); ?></div></span></label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <?php echo $errors->first('picture', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('website') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('website', 'Site Internet')); ?>

                                        <?php echo Form::text('website', null, ['class' => 'form-control', 'placeholder' => 'Site Internet']); ?>

                                        <?php echo $errors->first('website', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <br />

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informations propriétaire :
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('ownerLastName') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('ownerLastName', 'Nom du propriétaire')); ?>

                                        <?php echo Form::text('ownerLastName', null, ['class' => 'form-control', 'placeholder' => 'Nom du propriétaire']); ?>

                                        <?php echo $errors->first('ownerLastName', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('ownerFirstName') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('ownerFirstName', 'Prénom du propriétaire')); ?>

                                        <?php echo Form::text('ownerFirstName', null, ['class' => 'form-control', 'placeholder' => 'Prénom du propriétaire']); ?>

                                        <?php echo $errors->first('ownerFirstName', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('email') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('email', 'Email')); ?>

                                        <?php echo Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']); ?>

                                        <?php echo $errors->first('email', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informations bar :
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('name', 'Nom du bar')); ?>

                                        <?php echo Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nom du bar']); ?>

                                        <?php echo $errors->first('name', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('category') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('category', 'Categorie du bar')); ?>

                                        <select id="category" name="category" class="form-control form-control-lg">
                                            <?php if($partner->category == 'pub'): ?>
                                                <option value="pub" selected="selected">Pub</option>
                                                <option value="cocktail">Cocktail</option>
                                                <option value="liquor">Liquor</option>
                                                <option value="wine">Wine</option>
                                                <option value="club">club</option>
                                            <?php elseif($partner->category == 'cocktail'): ?>
                                                <option value="pub">Pub</option>
                                                <option value="cocktail" selected="selected">Cocktail</option>
                                                <option value="liquor">Liquor</option>
                                                <option value="wine">Wine</option>
                                                <option value="club">club</option>
                                            <?php elseif($partner->category == 'liquor'): ?>
                                                <option value="pub">Pub</option>
                                                <option value="cocktail">Cocktail</option>
                                                <option value="liquor" selected="selected">Liquor</option>
                                                <option value="wine">Wine</option>
                                                <option value="club">club</option>
                                            <?php elseif($partner->category == 'wine'): ?>
                                                <option value="pub">Pub</option>
                                                <option value="cocktail">Cocktail</option>
                                                <option value="liquor">Liquor</option>
                                                <option value="wine" selected="selected">Wine</option>
                                                <option value="club">club</option>
                                            <?php elseif($partner->category == 'club'): ?>
                                                <option value="pub">Pub</option>
                                                <option value="cocktail">Cocktail</option>
                                                <option value="liquor">Liquor</option>
                                                <option value="wine">Wine</option>
                                                <option value="club" selected="selected">club</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('tel') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('tel', 'Téléphone')); ?>

                                                <!--<input class="form-control" placeholder="Téléphone" name="tel" type="tel">-->
                                        <?php echo Form::text('tel', null, ['class' => 'form-control', 'placeholder' => 'Téléphone']); ?>

                                        <?php echo $errors->first('tel', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('address') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('address', 'N° et rue')); ?>

                                        <?php echo Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'N° et rue']); ?>

                                        <?php echo $errors->first('address', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('postalCode') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('postalCode', 'Code postal')); ?>

                                        <?php echo Form::text('postalCode', null, ['class' => 'form-control', 'placeholder' => 'Code postal']); ?>

                                        <?php echo $errors->first('postalCode', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('city') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('city', 'Ville')); ?>

                                        <?php echo Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Ville']); ?>

                                        <?php echo $errors->first('city', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('lat') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('lat', 'Latitude')); ?>

                                        <?php echo Form::text('lat', null, ['class' => 'form-control', 'placeholder' => 'Latitude']); ?>

                                        <?php echo $errors->first('lat', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('lng') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('lng', 'Longitude')); ?>

                                        <?php echo Form::text('lng', null, ['class' => 'form-control', 'placeholder' => 'Longitude']); ?>

                                        <?php echo $errors->first('lng', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informations statut du bar :
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('openStatus') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('openStatus', 'Commandes ouvertes / fermées')); ?>

                                        <select id="openStatus" name="openStatus" class="form-control form-control-lg">
                                            <?php if($partner->openStatus == 1): ?>
                                                <option value="1" selected="selected">Ouvertes</option>
                                                <option value="0">Fermées</option>
                                            <?php else: ?>
                                                <option value="1">Ouvertes</option>
                                                <option value="0" selected="selected">Fermées</option>
                                            <?php endif; ?>
                                        </select>
                                        <?php echo $errors->first('openStatus', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('HHStatus') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('HHStatus', 'Happy Hour ouverte / fermée')); ?>

                                        <select id="HHStatus" name="HHStatus" class="form-control form-control-lg">
                                            <?php if($partner->HHStatus == 1): ?>
                                                <option value="1" selected="selected">Ouverte</option>
                                                <option value="0">Fermée</option>
                                            <?php else: ?>
                                                <option value="1">Ouverte</option>
                                                <option value="0" selected="selected">Fermée</option>
                                            <?php endif; ?>
                                        </select>
                                        <?php echo $errors->first('openStatus', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group <?php echo $errors->has('activated') ? 'has-error' : ''; ?>">
                                        <?php echo e(Form::label('activated', 'Utilisateur actif / inactif')); ?>

                                        <select id="activated" name="activated" class="form-control form-control-lg">
                                            <?php if($partner->activated == 1): ?>
                                                <option value="1" selected="selected">Actif</option>
                                                <option value="0">Inactif</option>
                                            <?php else: ?>
                                                <option value="1">Actif</option>
                                                <option value="0" selected="selected">Inactif</option>
                                            <?php endif; ?>
                                        </select>
                                        <?php echo $errors->first('activated', '<small class="help-block">:message</small>'); ?>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <?php echo e(Form::submit('Envoyer', ['class' => 'btn btn-default pull-right'])); ?>


                    <?php echo Form::close(); ?>


                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['partner.destroy', $partner->id]]); ?>

                    <?php echo Form::submit('Supprimer le bar', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment supprimer cet utilisateur ?\')']); ?>

                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
        <a href="<?php echo e(route('partner.index')); ?>" class="btn btn-default">
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