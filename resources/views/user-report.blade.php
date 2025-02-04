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
        <h3 align="center">User Statistics Report</h3>
        <table>
            <tr>
                <td>Branch</td>
                <td> : </td>
                <td>{{$student->location->name??"--"}}</td>
            </tr>
            <tr>
                <td>Division</td>
                <td> : </td>
                <td>{{$student->category->slug}}</td>
            </tr>
            {{-- <tr>
                <td>Students</td>
                <td> : </td>
                <td>{{count($webinar['students'])}}</td>
            </tr>
            <tr>
                <td>Sales</td>
                <td> : </td>
                <td>{{count($webinar['sales'])}}</td>
            </tr> --}}
        </table>
        <br>
        <h4><strong>Teacher : {{$student->full_name}}</strong></h4>
        <table id="table">
            <thead>
                <tr>
                    <th style="text-align: center; color:white">No</th>
                    <th style="text-align: left; color:white">Course Name</th>
                    <th style="text-align: center; color:white">Division</th>
                    <th style="text-align: center; color:white">Date</th>
                    <th style="text-align: center; color:white">Progress</th>
                </tr>
            </thead>
            <tbody>
                @if (count($student['sales'])<1)
                    <tr><td colspan="5"> No data course </td></tr>
                @endif
                @foreach ($student['sales'] as $sale)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td class="text-left">{{ $sale->webinar->title ?? "--" }}</td>
                        <td style="text-align: center">{{ $sale->webinar->category->slug ?? "--" }}</td>
                        <td style="text-align: center">{{ dateTimeFormat($sale->created_at,'j M Y | H:i') }}</td>
                        <td style="text-align: center">{{ $sale->webinar->course_progress ?? "0" }} %</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>