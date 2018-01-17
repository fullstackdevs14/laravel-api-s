<?php if(Auth::check()): ?>
    <div class="sideMenu col-md-3">

        <!--Add to the class "panel-collapse collapse" the "in" class to automatically select.-->

        <div class="panel-group" id="accordion">

            <!--Trade sections-->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <span class="glyphicon glyphicon-th">
                            </span>Activité</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <span class="glyphicon glyphicon-euro"></span><?php echo link_to_route('charts.home','Voir les chiffres'); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="glyphicon glyphicon-th-list"></span><?php echo link_to_route('order.index','Liste des commandes'); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="glyphicon glyphicon-export"></span><?php echo link_to_route('export.index','Exports'); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Partners section -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-glass">
                            </span>Partenaires</a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <?php echo link_to_route('partner.index','Chercher un partenaire'); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo link_to_route('partner.create', 'Créer un nouveau partenaire'); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Users section -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-king">
                            </span>Utilisateurs</a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <?php echo link_to_route('sipperUser.index','Chercher un utilisateur'); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo link_to_route('sipperUser.create', 'Créer un nouvel utilisateur'); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Leads section -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-grain">
                            </span>Leads</a>
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <?php echo link_to_route('leads.index','Voir leads sipperapp.com'); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Noifications section -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-bullhorn"></span>
                            </span>Notifications</a>
                    </h4>
                </div>
                <div id="collapseFive" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <?php echo link_to_route('notification.form', 'Notifier tous les utilisateurs'); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Account section -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix"><span class="glyphicon glyphicon-tower"></span>
                            </span>Admnistration</a>
                    </h4>
                </div>
                <div id="collapseSix" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>
                                    </span><?php echo e(link_to_route('register', 'Créer un nouvel admin')); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    </span><?php echo e(link_to_route('changePassword', 'Changer de mot de passe')); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="glyphicon glyphicon-trash text-danger"></span><a href="#" class="text-danger">Supprimer ce compte</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
