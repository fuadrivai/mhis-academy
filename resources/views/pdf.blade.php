<!DOCTYPE html>
<html lang="en">
<head>
    {{-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <title>Webinar Statistics Report</title>
    <style>
        .center {
            margin: auto;
            padding: 10px;
        }
        hr.vertical {
            border: 2px solid rgb(9, 9, 9);
        }
        #table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #table td, #table th {
            border: 1px solid #ddd;
            padding: 3px;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table tr:hover {background-color: #ddd;}

        #table th {
            padding-top: 3px;
            padding-bottom: 3px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body>
    <div class="center">
        <img border="0" width="300" height="auto" class="sp-img" src="https://mutiaraharapan.sch.id/wp-content/uploads/2020/01/logo_mh_all_level_220_x_67_cm-01.png">
        <hr class="vertical">
        <h3 align="center">Course Statistics Report</h3>
        <table>
            <tr>
                <td>Title</td>
                <td> : </td>
                <td>{{$webinar['title']}}</td>
            </tr>
            <tr>
                <td>Category</td>
                <td> : </td>
                <td>{{$webinar['category']['slug']?? "--"}}</td>
            </tr>
            <tr>
                <td>Students</td>
                <td> : </td>
                <td>{{count($webinar['students'])}}</td>
            </tr>
            {{-- <tr>
                <td>Sales</td>
                <td> : </td>
                <td>{{count($webinar['sales'])}}</td>
            </tr> --}}
        </table>
        <br>
        <table id="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Student</th>
                    <th>Progress</th>
                    <th class="text-center">Date</th>
                    <!--<th>Registered Date</th>-->
                </tr>
            </thead>
            <tbody>
                @if (count($webinar['students'])<1)
                    <tr><td colspan="4"> No student take the course </td></tr>
                @endif
                @foreach ($webinar['students'] as $user)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td class="text-left">
                            <div class="user-inline-avatar d-flex align-items-center">
                                <div class=" ml-5">
                                    <span class="d-block text-dark-blue font-weight-500">{{ $user->full_name }}</span><br>
                                    <span class="mt-5 d-block font-12 text-gray">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->course_progress ?? 0 }} %</td>
                        <td class="text-center"><center> {{ dateTimeFormat($user->sales->created_at,'j M Y | H:i') }} </center></td>
                        <!--<td>{{ dateTimeFormat($user->created_at,'j M Y | H:i') }}</td>-->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>