<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">

        <title><?php echo e(isset($title) ? $title . ' - ' . config('app.name', 'ToDo') : config('app.name', 'ToDo')); ?></title>

        <script>if(localStorage.getItem('darkMode')==='true')document.documentElement.classList.add('dark');</script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <div class="w-full max-w-100">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html>
<?php /**PATH /home/tehnic/laravel/todo/resources/views/layouts/guest.blade.php ENDPATH**/ ?>