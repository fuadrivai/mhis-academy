<!DOCTYPE html>
<html lang="en">
<head>
    
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
        <h3 align="center">Webinar Statistics Report</h3>
        <table>
            <tr>
                <td>Title</td>
                <td> : </td>
                <td><?php echo e($webinar['title']); ?></td>
            </tr>
            <tr>
                <td>Category</td>
                <td> : </td>
                <td><?php echo e($webinar['category']['slug']); ?></td>
            </tr>
            <tr>
                <td>Students</td>
                <td> : </td>
                <td><?php echo e(count($webinar['students'])); ?></td>
            </tr>
            
        </table>
        <br>
        <table id="table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th class="" rowspan="2">Student</th>
                    <th rowspan="2">Progress</th>
                    <th rowspan="2">Passed Quizzes</th>
                    <th colspan="2">Assignments</th>
                    <th rowspan="2">Registered Date</th>
                </tr>
                <tr>
                    <th>Unsent</th>
                    <th>Pending</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($webinar['students'])<1): ?>
                    <tr><td colspan="7"> No student take the course </td></tr>
                <?php endif; ?>
                <?php $__currentLoopData = $webinar['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->index+1); ?></td>
                        <td class="text-left">
                            <div class="user-inline-avatar d-flex align-items-center">
                                <div class=" ml-5">
                                    <span class="d-block text-dark-blue font-weight-500"><?php echo e($user->full_name); ?></span><br>
                                    <span class="mt-5 d-block font-12 text-gray"><?php echo e($user->email); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($user->course_progress ?? 0); ?> %</td>
                        <td><?php echo e($user->passed_quizzes ?? 0); ?></td>
                        <td><?php echo e($user->unsent_assignments ?? 0); ?></td>
                        <td><?php echo e($user->pending_assignments ?? 0); ?></td>
                        <td><?php echo e(dateTimeFormat($user->created_at,'j M Y | H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</body>
</html><?php /**PATH C:\Users\fuads\OneDrive\Desktop\Laravel\mhis_academy\resources\views/pdf.blade.php ENDPATH**/ ?>