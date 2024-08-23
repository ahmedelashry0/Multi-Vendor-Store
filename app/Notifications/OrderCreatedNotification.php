<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
//        $channels = ['database'];
//        if ($notifiable->notification_preferences['order_created']['sms'] ?? false) {
//            $channels[] = 'vonage';
//        };
//        if ($notifiable->notification_preferences['order_created']['mail'] ?? false) {
//            $channels[] = 'mail';
//        };
//        if ($notifiable->notification_preferences['order_created']['broadcast'] ?? false) {
//            $channels[] = 'broadcast';
//        };
//        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $addr = $this->order->billingAddress;
        return (new MailMessage)
            ->subject("New Order # {$this->order->number}")
            ->from('notification@ajyal-store.ps' , 'AJYAL Store')
            ->greeting("Hello {$notifiable->name}")
            ->line("New order (# {$this->order->number}) has been created by $addr->name from {$addr->country_name}.")
            ->action('View Order', url('/dashboard'))
            ->line('Thank you for using our application!');
    }

/**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
