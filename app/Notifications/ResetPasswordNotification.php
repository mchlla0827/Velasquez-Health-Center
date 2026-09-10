<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPasswordBase
{
    /**
     * Build the mail representation - reuses the exact same secure
     * reset URL construction as Laravel's default notification
     * (token + the notifiable's email), just with our own branded
     * Blade view instead of the default Laravel-branded template.
     */
    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);

        // Embed the logo directly as base64 data - an external image
        // URL (even via asset()) resolves to localhost during local
        // development, which Gmail (or any external client) can never
        // reach. Embedding the image data in the email itself works
        // regardless of hosting.
        $logoBase64 = null;
        $logoPath = public_path('bhclogo.jpg');
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }

        return (new MailMessage)
            ->subject('Password Reset Request - Velasquez Health Center')
            ->view('emails.password-reset', [
                'url' => $url,
                'expireMinutes' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60),
                'logoBase64' => $logoBase64,
            ]);
    }

    /**
     * Build the reset URL exactly as Laravel's base class does -
     * kept as its own method so the base class's static callback
     * customization (createUrlUsing) still works if ever configured.
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}