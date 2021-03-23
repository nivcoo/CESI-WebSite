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
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>Permission</th>
                                    @if (!empty($roles))
                                        @foreach ($roles as $role)
                                            <th> {{ $role['name'] }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($permissions))
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission["definition"] }}</td>
                                            @if(!empty($roles))
                                                @foreach ($roles as $role)
                                                    <td>
                                                        <input type="checkbox"
                                                               name="{{ $permission["id"] }}-{{ $role['id'] }}" {{ (isset($roles_has_permissions[$role['id']][$permission['id']]) && $roles_has_permissions[$role['id']][$permission['id']]) ? ' checked="checked"' : '' }} {{ $role['id'] == 4 ? "disabled checked='checked' " : "" }}>
                                                    </td>
                                                @endforeach
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif

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
