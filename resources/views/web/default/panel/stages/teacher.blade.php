@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
@endpush

@section('content')
<section>
    <div class="row">
        <div class="col-12">
            @if (session()->has('success') || session()->has('error'))
                <div class="card" style="background-color: {{session()->has('success')?'#43d477':crimson}}">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="card-title" style="color: white">{{ session('success') }}</h3>
                                <h3 class="card-title" style="color: white">{{ session('error') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <h2 class="section-title">Teacher List</h2>
</section>

<section class="mt-25">
    <div class="activities-container mt-25 p-20">
        <div class="row pt-20">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered text-center" id="teacher-tabel">
                        <thead>
                            <tr>
                                <th class="text-left text-gray">No</th>
                                <th class="text-left text-gray">Name</th>
                                <th class="text-left text-gray">Division</th>
                                <th class="text-left text-gray">Branch</th>
                                <th class="text-left text-gray">Stages</th>
                                <th class="text-left text-gray">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-left">{{ $item['full_name'] }}
                                        <br><small>{{ $item['email'] }}</small>
                                    </td>
                                    <td>{{$item['category']['slug']??"--"}}</td>
                                    <td>{{$item['location']['name']??"--"}}</td>
                                    <td>
                                        @if (($item['level']['level']??-1)==0)
                                            <span class="badge badge-secondary"><strong>{{$item['level']['stage']}}</strong></span>
                                        @elseif(($item['level']['level']??-1)==1)
                                            <span class="badge" style="background-color: #ffc107!important; color:white"><strong>{{$item['level']['stage']}}</strong></span>
                                        @elseif(($item['level']['level']??-1)==2)
                                            <span class="badge" style="background-color: #28a745!important; color:white"><strong>{{$item['level']['stage']}}</strong></span>
                                        @elseif(($item['level']['level']??-1)==3)
                                            <span class="badge" style="background-color: #6f42c1!important; color:white"><strong>{{$item['level']['stage']}}</strong></span>
                                        @elseif(($item['level']['level']??-1)==4)
                                            <span class="badge badge-danger"><strong>{{$item['level']['stage']}}</strong></span>
                                        @else
                                            <span>--</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button data-level="{{ $item['level_id']}}" data-id="{{ $item['id']}}" data-name="{{ $item['full_name'] }}" data-toggle="modal" data-target="#modal-edit" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-label">Form Stage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Nama Guru</label>
                                <input class="d-none" id="id">
                                <input disabled readonly type="text" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label for="">Stages</label>
                                <select required name="level" id="level" class="form-control select2" style="width: 100%">
                                    <option value="">-- Choose Stage --</option>
                                    @foreach ($levels as $level)
                                        <option value="{{$level->id}}">{{$level->stage}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" onclick="postData()" class="btn btn-secondary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
    <script src="/assets/default/vendors/jquery-blockUI/jquery.blockUI.js"></script>
    <script src="/assets/default/js/panel/report.js"></script>

    <script>
        let baseUrl = "<?= url('/')?>";
        $(document).ready(function(){
            $('#teacher-tabel').DataTable({
                paging: false,
                searching: false,
            });

            $('#modal-edit').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let userId = button.data('id');
                let name = button.data('name');
                let levelId = button.data('level');

                // Update the modal content
                $('#id').val(userId);
                $('#name').val(name);
                $('#level').val(levelId).trigger('change');
            });
        })

        function postData(){
            $('#modal-edit').modal('hide')
            blockUI();
            let data = {
                id:$('#id').val(),
                level:$('#level').val()
            }
            ajax(data, `${baseUrl}/panel/stages/teacher/post/${data.id}`, "POST",
                function(json) {
                    unblockUI();
                    toast('Data saved successfully','text-success')
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },function(json){
                    unblockUI();
                    toast('Server error, please try again','text-danger')
                    console.log(json);
                }
            )
        }
    </script>
@endpush
