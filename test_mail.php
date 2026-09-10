<?php
echo config('mail.default') . " | ";
echo config('mail.mailers.smtp.host') . " | ";
echo config('mail.mailers.smtp.encryption') . " | ";
try {
    $r = Illuminate\Support\Facades\Password::sendResetLink(['email' => 'vhc.capstone@gmail.com']);
    echo "STATUS: " . $r;
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage();
}
