<?php $__env->startSection('content'); ?>
    <?php echo is_array(\Tether\Config::get('admin_emails')) ? json_encode(\Tether\Config::get('admin_username')) : \Tether\Config::get('admin_username'); ?>
    
    <form method="POST">
        <input type="text" name="username" />
        
        <button type="submit">Check Username</button>
    </form>
<?php $__env->stopSection(); ?>    
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/josh/Sites/tether/templates/index.blade.php ENDPATH**/ ?>