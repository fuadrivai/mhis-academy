@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
@endpush

@section('content')
    <section>
        <h2 class="section-title">Web Binar Report By User</h2>
        {{-- <h2 class="section-title">{{$students[0]->location}}</h2> --}}
    </section>
    
    <section class="mt-25">
        <div class="activities-container mt-25 p-20">
            <h2 class="section-title">Teacher List</h2>
            <div class="row pt-20">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-left text-gray">No</th>
                                <th class="text-left text-gray">Name</th>
                                <th class="text-center text-gray">Division</th>
                                <th class="text-center text-gray">Branch</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-left">{{$student->full_name}} <br> {{$student->email}}</td>
                                    <td>{{$student->category->slug??"--"}}</td>
                                    <td>{{$student->location->name??"--"}}</td>
                                    <td>
                                        <a href="/panel/report/user/{{$student->id}}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
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

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>

    <script>
        $(document).ready(function(){
            table = $('#myTable').DataTable();
        })
    </script>
@endpush
