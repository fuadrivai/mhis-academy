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
                            <select {{isset($id)?"":(isset($user->location_id)?"disabled":"")}} required name="branch" id="branch" class="form-control select2" style="width: 100%">
                                @if (!isset($id))
                                    <option value="">-- Select Branch --</option>
                                    @foreach($branches as $branch)
                                        @if ($user->location_id==$branch->id)
                                            <option selected value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @else
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">Division</label>
                            <select {{isset($id)?"": (isset($user->category_id)?"disabled":"")}} required name="division" id="division" class="form-control select2" style="width: 100%">
                                @if (!isset($id))
                                    <option value="">-- Select Division --</option>
                                    @foreach($divisions as $division)
                                        @if ($user->category_id==$division->id)
                                            <option selected value="{{ $division->id }}">{{ $division->title }}</option>
                                        @else
                                            <option value="{{ $division->id }}">{{ $division->title }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">Stages</label>
                            <select name="level" id="level" class="form-control select2" style="width: 100%">
                                @if (!isset($id))
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->stage }}</option>
                                    @endforeach
                                @endif
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
                    <a href="#" data-toggle="modal" data-target="#modal-webinar" class="btn btn-secondary"><i class="fa fa-plus"> Add Courses</i></a>
                </div>
            </div>
            <div class="row pt-20">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered text-center" id="target-tabel">
                            <thead>
                                <tr>
                                    <th class="text-left text-gray">Course</th>
                                    <th class="text-left text-gray">Category</th>
                                    <th class="text-left text-gray">Duration</th>
                                    <th class="text-left text-gray">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <button onclick="onSaveTarget()" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>
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
                            <button onclick="onInputWebinar()" type="button" class="btn btn-secondary"><i class="fa fa-save"> Submit</i></button>
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
        let id = "{{$id}}"
        let baseUrl = "<?= url('/')?>";
        let targets = {
            level_id:null,
            category_id:null,
            location_id:null,
            details:[]
        }
        let webinars = [];
        let tempWebinars = [];
        $(document).ready(function(){
            id == ""?"":getTargetById();
            getWebinar();
            tblTarget = $('#target-tabel').DataTable({
                searching: false,
                paging: false,
                data:targets.details,
                columns:[
                    {
                        data:"webinar.title",
                        bSortable: false,
                        className:"text-left",
                    },
                    {
                        data:"webinar.category.title",
                        bSortable: false,
                        className:"text-center",
                    },
                    {
                        data:"webinar.duration",
                        bSortable: false,
                        className:"text-center",
                        mRender:function(data,type,full){
                            return `${data} - Minutes`
                        }
                    },
                    {
                        data:"webinar.id",
                        bSortable: false,
                        className:"text-center",
                        mRender:function(data,type,full){
                            return `<button class="btn btn-sm btn-danger delete-webinar"><i class="fa fa-trash"></i></button>`
                        }
                    },
                ]
            });

            tblWebinar = $('#webinar-target').DataTable({
                data:webinars,
                columns:[
                    {
                        data:"id",
                        defaultContent:"--",
                        className:'text-center',
                        mRender:function(data,type,full){
                            return `<input type="checkbox" class="input-check" id="check-${data}" data-id="${data}">`
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
                            return `<span class="badge text-white" style='background-color:green'>${data}</span>`
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

            $('#modal-webinar').on('show.bs.modal', function (e) {
                targets.details.forEach(e=>{
                    tempWebinars.push(e.webinar);
                })
                reloadJsonDataTable(tblWebinar, webinars);
            })

            $('#webinar-target').on('change','td input[type="checkbox"]',function() {
                let webinar = tblWebinar.row($(this).parents('tr')).data();
                let val = $(this).prop('checked');
                if (val) {
                    tempWebinars.push(webinar);
                }else{
                    tempWebinars.splice(webinar,1);
                }
            })

            $('#target-tabel').on('click','.delete-webinar',function(){
                let data = tblTarget.row($(this).parents('tr')).index();
                targets.details.splice(data, 1);
                reloadJsonDataTable(tblTarget, targets.details);
            })
        })

        function onInputWebinar() {
            targets.details=[];
            tempWebinars.forEach(e => {
                detail = {
                    webinar:e
                }
                targets.details.push(detail);
            });
            tempWebinars = [];
            $('#modal-webinar').modal('hide')
            reloadJsonDataTable(tblTarget, targets.details);
        }

        function getWebinar(){
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
        function onSaveTarget(){
            if ($('#branch').val()=="" || $('#division').val()=="") {
                toast('Branch or Division cannot be Empty !','error')
                return false;
            }
            if (targets.details.length <1) {
                toast('Course detail cannot be empty !','error')
                return false;
            }
            blockUI();
            targets.level_id=$('#level').val();
            targets.category_id=$('#division').val();
            targets.location_id=$('#branch').val();

            ajax(targets, `${baseUrl}/panel/stages/webinar/${id ==""?"save":"update"}`, "POST",
                function(json) {
                    unblockUI();
                    toast('Data Saved Successfully','success');
                    setTimeout(() => {
                        window.location.href=`${baseUrl}/panel/stages/webinar`
                    }, 700);
                },
                function(json){
                    unblockUI();
                    toast(json?.responseJSON?.message??'something went wrong, Please try again later !','error');
                }
            )
        }

        function getTargetById(){
            blockUI();
            ajax(null, `${baseUrl}/panel/stages/webinar/target/${id}`, "GET",
                function(json) {
                    $('#branch').append(`
                        <option value="${json.location.id}">${json.location.name}</option>
                    `).attr('disabled',true)
                    $('#division').append(`
                        <option value="${json.category.id}">${json.category.title}</option>
                    `).attr('disabled',true)
                    $('#level').append(`
                        <option value="${json.level.id}">${json.level.stage}</option>
                    `).attr('disabled',true)
                    targets = json;
                    reloadJsonDataTable(tblTarget, targets.details);
                    unblockUI();
                },
                function(json){
                    unblockUI();
                    toast(json?.responseJSON?.message??'something went wrong, Please try again later !','error');
                }
            )
        }
    </script>

@endpush
