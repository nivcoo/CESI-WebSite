@extends('layouts.panel')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">{{$title}}</h3>

                    </div>
                    <div class="card-body">
                        @if(Session::has('message'))
                            <p class="alert alert-success m-1 mb-3">{{ Session::get('message') }}</p>
                        @endif

                        <div id="users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-responsive-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Type</th>
                                    <th>Society Name</th>
                                    <th style="width: 300px;">Content</th>
                                    <th>Actions</th>
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

            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ url()->current() }}",

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'type', name: 'type'},
                    {data: 'name', name: 'name'},
                    {data: 'content', name: 'content'},
                    {data: 'action', name: 'action', orderable: true, searchable: true},
                ]
            });

        });
    </script>

@endsection
