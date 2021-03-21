@extends('layouts.panel')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Permissions</h3>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('panel_permissions') }}" data-ajax="true" method="POST">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Permission</th>
                                    <?php
                                    if (!empty($roles)) {
                                        foreach ($roles as $role) {
                                            echo '<th>' . $role['name'] . '</th>';
                                        }
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($permissions)) { ?>
                                <?php foreach ($permissions as $permission) { ?>


                                <tr>
                                    <td><?= $permission["definition"] ?></td>
                                    <?php if(!empty($roles)) { ?>
                                    <?php foreach ($roles as $role) { ?>
                                    <td><input type="checkbox"
                                               name="<?= $permission["id"] ?>-<?= $role['id'] ?>"<?= (isset($roles_has_permissions[$role['id']][$permission['id']]) && $roles_has_permissions[$role['id']][$permission['id']]) ? ' checked="checked"' : '' ?> <?= $role['id'] == 4 ? "disabled checked='checked' " : "" ?>>
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                </tr>


                                <?php } ?>
                                <?php } ?>

                                </tbody>
                            </table>

                            <button class="btn btn-primary" type="submit">Send</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
