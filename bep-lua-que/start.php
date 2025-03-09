<?php

// Chạy artisan schedule:run trước khi khởi động server
exec('php artisan schedule:run', $output, $return_var);
echo "Schedule Run Output: " . implode("\n", $output) . "\n";
echo "Return Code for Schedule: " . $return_var . "\n";

// Khởi động server Laravel
exec('php artisan serve', $output, $return_var);
echo "Serve Output: " . implode("\n", $output) . "\n";
echo "Return Code for Serve: " . $return_var . "\n";
