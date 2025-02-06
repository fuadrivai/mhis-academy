@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">Target Form</h2>
    </section>

    <section>
        <div class="panel-section-card py-20 px-25 mt-20">
            <form id="form-filter">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">Branch</label>
                            <select name="branch" id="branch" class="form-control select2" style="width: 100%">
                                <option value="all">All</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">Division</label>
                            <select name="division" id="division" class="form-control select2" style="width: 100%">
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->slug }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">Stages</label>
                            <select name="division" id="division" class="form-control select2" style="width: 100%">
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->stage }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    
    <section class="mt-25">
        <div class="activities-container mt-25 p-20">
            <h3 class="section-title text-center">Course List</h3>
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <a href="#" data-toggle="modal" data-target="#modal-webinar" class="btn btn-sm btn-block btn-secondary"><i class="fa fa-plus"> Add Courses</i></a>
                </div>
            </div>
            <div class="row pt-20">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered text-center" id="target-tabel">
                            <thead>
                                <tr>
                                    <th class="text-left text-gray"></th>
                                    <th class="text-left text-gray">No</th>
                                    <th class="text-left text-gray">Name</th>
                                    <th class="text-left text-gray">Division</th>
                                    <th class="text-left text-gray">Branch</th>
                                    <th class="text-left text-gray">Total Courses</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-webinar" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">List webinar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped table-bordered" width="100%" id="webinar-target">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Division</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary"><i class="fa fa-save"> Submit</i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        let baseUrl = "<?= url('/')?>";
        let targets = {
            level_id:null,
            category_id:null,
            location_id:null,
            details:[]
        }
        let webinars = [];
        $(document).ready(function(){
            getData();
            // table = $('#target-tabel').DataTable({
            //     searching: false,
            //     paging: false,
            //     data:targets.details,
            //     columns:[
            //         {
            //             data:"webinar.",
            //             bSortable: false,
            //             className:"text-left",
            //         },
            //         {
            //             data:"",
            //             className:"",
            //         }
            //     ]
            // });

            tblWebinar = $('#webinar-target').DataTable({
                data:webinars,
                columns:[
                    {
                        data:"id",
                        defaultContent:"--",
                        className:'text-center',
                        mRender:function(data,type,full){
                            return `<input type="checkbox" class="input-check" data-id="${data}">`
                        }
                    },
                    {
                        data:"title",
                        defaultContent:"--",
                    },
                    {
                        data:"category.slug",
                        className:'text-center',
                        defaultContent:"--",
                    },
                    {
                        data:"duration",
                        defaultContent:"--",
                        className:'text-center',
                        mRender:function(data,type,full){
                            return `${data} - minutes`
                        }
                    },
                    {
                        data:"status",
                        className:'text-center',
                        defaultContent:"--",
                        mRender:function(data,type,full){
                            return `${data}`
                        }
                    }
                ],
                drawCallback: function( settings ) {
                    var api = this.api();
                    var node = api.rows().nodes()
                    for (var i = 0; i < node.length; i++) {
                        let dataId = $(node[i]).find('input').attr('data-id')
                        let isExist = targets.details.some(item => item.webinar.id == dataId)
                        if (isExist) {
                            $(node[i]).find('input').prop('checked',true)
                        }
                    }
                },
            })
        })

        function getData(){
            blockUI();
            ajax(null, `${baseUrl}/panel/stages/webinar/datatable`, "GET",
                function(json) {
                    webinars = json;
                    reloadJsonDataTable(tblWebinar, webinars);
                    unblockUI()
                },
                function(json){
                    unblockUI()
                    console.log(json);
                }
            )
        }
    </script>

@endpush
