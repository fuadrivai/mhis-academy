@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">User Report</h2>
    </section>

    <section>
        <div class="panel-section-card py-20 px-25 mt-20">
            <h2 class="section-title">Fillter Range</h2>
            <form id="form-filter" class="row">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.from') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="dateInputGroupPrepend">
                                    <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                </span>
                            </div>
                            <input required type="text" name="from" autocomplete="off" id="from" class="form-control month-picker"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.to') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="dateInputGroupPrepend">
                                    <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                </span>
                            </div>
                            <input required type="text" name="to" autocomplete="off" id="to" class="form-control month-picker"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex align-items-center">
                    <button type="button" onclick="getData()" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>
    
    <section class="mt-25">
        <div class="activities-container mt-25 p-20">
            <h2 class="section-title">Course List</h2>
            <div class="row pt-20">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-left text-gray">No</th>
                                <th class="text-left text-gray">Course Name</th>
                                <th class="text-left text-gray">Category</th>
                                <th class="text-left text-gray">Date</th>
                                <th class="text-center text-gray">Progress</th>
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
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>
    <script src="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/assets/default/js/panel/report.js"></script>

    <script>
        let baseUrl = "<?= url('/')?>";
        let dataId = "<?=isset($student)?$student->id:null?>";
        let dataCourses = [];
        $(document).ready(function(){
            table = $('#myTable').DataTable({
                paging: false,
                searching: false,
                ordering:  false,
                data : dataCourses,
                columns:[
                    {
                        data:"id",
                        bSortable: false,
                        className:"text-left",
                    },
                    {
                        data:"webinar.title",
                        bSortable: false,
                        defaultContent:"--",
                        className:"text-left",
                    },
                    {
                        data:"webinar.category.slug",
                        bSortable: false,
                        defaultContent:"--",
                        className:"text-center",
                    },
                    {
                        data:"created_at",
                        bSortable: false,
                        defaultContent:"--",
                        mRender:function(data,type,full){
                            return moment.unix(data).format('DD MMM YYYY');
                        }
                    },
                    {
                        data:"webinar.course_progress",
                        bSortable: false,
                        defaultContent:"--",
                        mRender:function(data,type,full){
                            return `${data} %`;
                        }
                    },
                ],
                fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull +1);
                }
            });
            $('.month-picker').datepicker({
                format:"MM yyyy",
                orientation: "top auto",
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                language: 'id',
                clearBtn:true
            });

            $('#from').on('changeDate',function(){
                $('#to').val('')
                if ($('#from').val()!="") {
                    var startDate = $(this).datepicker('getDate');
                    $('#to').attr('disabled',false)
                    $('#to').attr('required',true)
                    $('#to').datepicker('setStartDate', moment(startDate).toDate());
                }else{
                    $('#to').attr('disabled',true)
                    $('#to').attr('required',false)
                }
            })

        })

        function getData(){
            let data = {
                        from : $('#from').val(),
                        to : $('#to').val(),
                    }
                    ajax(data, `${baseUrl}/panel/report/user/periode/${dataId}`, "GET",
                        function(json) {
                            reloadJsonDataTable(table, json);
                    },function(json){
                        console.log(json);
                    })
        }
    </script>
@endpush
