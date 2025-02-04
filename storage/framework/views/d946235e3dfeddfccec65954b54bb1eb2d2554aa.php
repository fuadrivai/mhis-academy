

<?php $__env->startPush('styles_top'); ?>
    <link href="/assets/vendors/fontawesome/css/all.css" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section>
        <h2 class="section-title">Web Binar Report By User</h2>
        
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
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td class="text-left"><?php echo e($student->full_name); ?> <br> <?php echo e($student->email); ?></td>
                                    <td><?php echo e($student->category->slug??"--"); ?></td>
                                    <td><?php echo e($student->location->name??"--"); ?></td>
                                    <td>
                                        <a href="/panel/report/user/<?php echo e($student->id); ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/datatable/dataTables.js"></script>
    <script src="/assets/default/vendors/datatable/dataTables.bootstrap4.js"></script>

    <script>
        $(document).ready(function(){
            table = $('#myTable').DataTable();
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make(getTemplate() .'.panel.layouts.panel_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fuads\OneDrive\Desktop\Laravel\academy.mhis.link\resources\views/web/default/panel/report/user-report.blade.php ENDPATH**/ ?>