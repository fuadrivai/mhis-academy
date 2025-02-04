@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">Teacher List</h2>
        {{-- <h2 class="section-title">{{ $students[5]->category_id}}</h2> --}}
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ $item->full_name }}
                                        <br><small>{{ $item->email }}</small>
                                    </td>
                                    <td>{{ $item->category->slug??"--" }}</td>
                                    <td>{{ $item->location->name??"--" }}</td>
                                    <td>{{ $item->level->stage??"--" }}</td>
                                    <td>{{ $item->iteration }}</td>
                                </tr>
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
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
    <script src="/assets/default/vendors/jquery-blockUI/jquery.blockUI.js"></script>
    <script src="/assets/default/js/panel/report.js"></script>

    <script>
        $(document).ready(function(){
            $('#teacher-tabel').DataTable({
                paging: false,
                searching: false,
            });
        })
    </script>
@endpush
