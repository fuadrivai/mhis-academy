@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">User Report</h2>
    </section>

    <section>
        <div class="panel-section-card py-20 px-25 mt-20">
            <form id="form-filter">
                <div class="row">
                    <div class="col-12 col-lg-3">
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
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label class="input-label">Division</label>
                            <select name="division" id="division" class="form-control select2" style="width: 100%">
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->slug }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.from') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="dateInputGroupPrepend">
                                        <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                    </span>
                                </div>
                                <input readonly required type="text" name="from" autocomplete="off" id="from" class="form-control month-picker"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('public.to') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="dateInputGroupPrepend">
                                        <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                    </span>
                                </div>
                                <input readonly required type="text" name="to" autocomplete="off" id="to" class="form-control month-picker"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-3 d-flex justity-content-end">
                        <button type="button" onclick="getData()" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                    </div>
                </div>
                
            </form>
        </div>
    </section>
    
    <section class="mt-25">
        <div class="activities-container mt-25 p-20">
            <h3 class="section-title text-center">User List</h3>
            <div class="row justify-content-end">
                <div class="col-md-3 col-sm-12">
                    <a  href="#" onclick="return false;" id="pdf-button" class="btn btn-sm btn-block btn-secondary"><i class="fa fa-download"> Download PDF</i></a>
                </div>
            </div>
            <div class="row pt-20">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered text-center" id="parent-tabel">
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
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>
    <script src="/assets/default/vendors/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
    <script src="/assets/default/js/panel/report.js"></script>

    <script>
        let baseUrl = "<?= url('/')?>";
        let dataId = "<?=isset($student)?$student->id:null?>";
        let dataCourses = [];
        $(document).ready(function(){
            table = $('#parent-tabel').DataTable({
                // paging: false,
                searching: false,
                data : dataCourses,
                columns:[
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        width: '10%',
                        mRender:function(){
                            return `<i class="fa fa-plus-circle"></i>`
                        }
                    },
                    {
                        data:"id",
                        bSortable: false,
                        className:"text-left",
                    },
                    {
                        data:"full_name",
                        bSortable: false,
                        defaultContent:"--",
                        className:"text-left",
                        mRender:function(data,type,full){
                            return `${data} <br> ${full.email}`;
                        }
                    },
                    {
                        data:"category.slug",
                        bSortable: false,
                        defaultContent:"--",
                        className:"text-center",
                    },
                    {
                        data:"location.name",
                        bSortable: false,
                        defaultContent:"--",
                        className:"text-center",
                    },
                    {
                        data:"sales",
                        defaultContent:"--",
                        className:"text-center",
                        mRender:function(data, type, full){
                            return data.length
                        }
                    },
                ],
                fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    $('td:eq(1)', nRow).html(iDisplayIndexFull +1);
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

            table.on('click', 'td.dt-control', function (e) {
                var tr = $(this).parents('tr');
                let row = table.row(tr);
            
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                    tr.addClass('text-center')
                }
            });

            $('#pdf-button').on('click',function(e){
                getReportPdf();
            })
        })

        function format(d) {

            let header = `<div class="row justify-content-center">
                            <div class="col-md-11">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Course List</h6>
                                    </div>
                                    <table class="table table-sm table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-left text-gray">No</th>
                                                    <th class="text-left text-gray">Course Name</th>
                                                    <th class="text-left text-gray">Division</th>
                                                    <th class="text-left text-gray">Date</th>
                                                    <th class="text-left text-gray">Progress</th>
                                                </tr>
                                            </thead>
                                        <tbody>`

            let footer = `</tbody></table></div></div></div>`
            if (d.sales.length < 1) {
                header += `<tr><td colspan="5" class="text-center text-gray">No Data</td></tr>`
            }else{
                d.sales.forEach((e,i) => {
                    header += `<tr>
                                <td class="text-left">${i+1}</td>
                                <td class="text-left">${e.webinar.title}</td>
                                <td class="text-center">${e.webinar.category.slug}</td>
                                <td class="text-center">${moment.unix(e.created_at).format('DD MMM YYYY')}</td>
                                <td class="text-center">${e.webinar.course_progress} %</td>
                            </tr>`
                });
            }
            header += footer;
            return header;
        }
 

        function getData(){
            let from = $('#from').val();
            let to = $('#to').val();
            if (from == "" || to == "") {
                $.toast({
                    heading: 'Information',
                    text: 'Start date and End date could not be empty',
                    icon: 'warning',
                    loader: true,        // Change it to false to disable loader
                    loaderBg: 'text-danger'  // To change the background
                })
                return false;
            }
            let data = {
                from : from,
                to : to,
                division : $('#division').val(),
                branch : $('#branch').val(),
            }

            ajax(data, `${baseUrl}/panel/report`, "GET",
                function(json) {
                    reloadJsonDataTable(table, json);
            },function(json){
                console.log(json);
            })
        }

        function getReportPdf(){
            let from = $('#from').val();
            let to = $('#to').val();
            if (from == "" || to == "") {
                $.toast({
                    heading: 'Information',
                    text: 'Start date and End date could not be empty',
                    icon: 'warning',
                    loader: true,        // Change it to false to disable loader
                    loaderBg: 'text-danger'  // To change the background
                })
                return false;
            }
            let data = {
                from : from,
                to : to,
                division : $('#division').val(),
                branch : $('#branch').val(),
            }
            var result = $.param(data);
            window.location.href=`${baseUrl}/panel/report/pdf?${result}`;
        }
    </script>
@endpush
