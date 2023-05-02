<?php

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeResetPasswordNotificationController extends ResetPassword
{
    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url').route('password.reset', [
                $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

        return (new MailMessage)
            ->subject('Jelszó beállítása')
            ->line('Azért kapotad ezt az emailt, mert regisztrálva lettél rendszerünkbe. Kérlek add meg az új jelszavad!')
            ->action('Új jelszó', $resetUrl)
            ->line('A link lejár :count percen belül.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])
            ->line('Ha szerinted téves email cím került regisztrálásra, hagy figyelmen kívül ezt az emailt');
    }
}
