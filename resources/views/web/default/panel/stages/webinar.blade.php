@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.css">
@endpush

@section('content')

    <section>
        <h2 class="section-title">Stage Target List</h2>
    </section>

    <section class="mt-25">
        <div class="activities-container mt-25 p-20">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <a  href="/panel/stages/webinar/create" id="add Stages" class="btn btn-primary"><i class="fa fa-plus"> Add Target</i></a>
                </div>
            </div>
            <div class="row pt-20">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered text-center" id="Stage-tabel">
                            <thead>
                                <tr>
                                    <th class="text-left text-gray">No</th>
                                    <th class="text-left text-gray">Stage</th>
                                    <th class="text-left text-gray">Category</th>
                                    <th class="text-left text-gray">Branch</th>
                                    <th class="text-left text-gray">Courses</th>
                                    <th class="text-left text-gray">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($targets as $target)
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$target['stage_name']}}</td>
                                    <td>{{$target['category_name']}}</td>
                                    <td>{{$target['location_name']}}</td>
                                    <td>{{count($target['details'])}}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>
    <script src="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
    <script src="/assets/default/vendors/jquery-blockUI/jquery.blockUI.js"></script>
    <script src="/assets/default/js/panel/report.js"></script>

    <script>
        $(document).ready(function(){
            table = $('#Stage-tabel').DataTable({
                searching: false,
                paging: false,
            });
        })
    </script>
@endpush
