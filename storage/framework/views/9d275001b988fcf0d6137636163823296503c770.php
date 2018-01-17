<?php $__env->startSection('content'); ?>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Création d'une boisson</div>
            <div class="panel-body">
                <div class="col-sm-12">

                    <?php echo Form::model($item, ['route' => ['item.update', $partner->id, $item->id], 'method' => 'put', 'class' => 'form-horizontal panel']); ?>


                    <div class="form-group <?php echo $errors->has('name') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('name', 'Nom et quantité')); ?>

                        <?php echo Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nom de la boisson - quantité']); ?>

                        <?php echo $errors->first('name', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('category_id') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('category_id', 'Type de boisson')); ?>

                        <select id="category_id" name="category_id" class="form-control form-control-lg">
                            <option disabled selected value> -- Choisir une option -- </option>
                            <?php
                            foreach($categories as $category){
                                $used = null;
                                echo '<option value="'. $category->id .'"';
                                if($item->category_id == $category->id){
                                    $used = 1;
                                    echo 'selected="selected"';
                                }
                                echo '>' . $category->category . '</option>';
                                if($used === null){
                                }
                            }
                            ?>
                        </select>
                        <?php echo $errors->first('category_id', '<small class="help-block">:message</small>'); ?>

                    </div>


                    <div class="form-group <?php echo $errors->has('price') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('price', 'Prix TTC')); ?>

                        <?php echo Form::number('price', null, ['class' => 'form-control', 'placeholder' => 'Prix TTC', 'step' => 'any']); ?>

                        <?php echo $errors->first('price', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('HHPrice') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('HHPrice', 'Prix TTC en HH')); ?>

                        <?php echo Form::number('HHPrice', null, ['class' => 'form-control', 'placeholder' => 'Prix TTC en HH', 'step' => 'any']); ?>

                        <?php echo $errors->first('HHPrice', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('tax') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('tax', 'Choisir une TVA')); ?>

                        <select id="tax" name="tax" class="form-control form-control-lg">
                            <option disabled selected value> -- Choisir une option -- </option>
                            <?php
                            foreach($taxes as $tax){
                                $used = null;
                                echo '<option value="'. $tax->per_cent .'"';
                                if($item->tax == $tax->per_cent){
                                    $used = 1;
                                    echo 'selected="selected"';
                                }
                                echo '>' . $tax->per_cent . ' %</option>';
                                if($used === null){
                                    echo '<option value="'. $tax->per_cent .'">'. $tax->per_cent  .' %</option>';
                                }
                            }
                            ?>
                        </select>
                        <?php echo $errors->first('tax', '<small class="help-block">:message</small>'); ?>

                    </div>


                    <div class="form-group <?php echo $errors->has('ingredients') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('ingredients', 'Ingrédients')); ?>

                        <?php echo Form::textarea('ingredients', null, ['class' => 'form-control', 'placeholder' => 'Ingrédients']); ?>

                        <?php echo $errors->first('ingredients', '<small class="help-block">:message</small>'); ?>

                    </div>

                    <div class="form-group <?php echo $errors->has('availability') ? 'has-error' : ''; ?>">
                        <?php echo e(Form::label('availability', 'Boisson en stock')); ?>

                        <select id="availability" name="availability" class="form-control form-control-lg">
                            <?php if($item->availability = 1): ?>
                                <option value="1" selected="selected">Disponible</option>
                                <option value="0">Indisponible</option>
                            <?php elseif($item->availability = 0): ?>
                                <option value="1">Disponible</option>
                                <option value="0" selected="selected">Indisponible</option>
                            <?php endif; ?>
                        </select>
                        <?php echo $errors->first('availability', '<small class="help-block">:message</small>'); ?>

                    </div>


                    <?php echo Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']); ?>

                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
        <a href="<?php echo e(route('menus.edit', $partner->id)); ?>" class="btn btn-default">
            <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>