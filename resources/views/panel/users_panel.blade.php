@extends('layouts.panel')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">List of {{$title}}</h3>

                    </div>
                    <div class="card-body">
                        @if(Session::has('message'))
                            <p class="alert alert-success m-1 mb-3">{{ Session::get('message') }}</p>
                        @endif

                        <div id="users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>No
                                        @if($can["add"])
                                            <a class="btn btn-sm btn-success d-inline" href="{{ route("panel_users_add", [$type]) }}">Add</a>
                                        @endif</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <script type="text/javascript">
        $(function () {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url()->current() }}",

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: true, searchable: true},
                ]
            });

        });
    </script>

@endsection
