<?php
/**
 * File name: StatusChangedOrder.php
 * Last modified: 2020.04.29 at 10:35:47
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Notifications;

use App\Models\Order;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangedOrder extends Notification
{
    use Queueable;
    /**
     * @var Order
     */
    private $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, $is_cancel = false)
    {
        //
        $this->order = $order;
        $this->is_cancel = $is_cancel;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $status = $this->is_cancel ? 'Cancelado' : $this->order->orderStatus->status;
        $notification = [
            'title' => trans('lang.notification_your_order', ['order_id' => $this->order->id, 'order_status' => $status]),
            'text' => $this->order->productOrders[0]->product->market->name,
            'image' => $this->order->productOrders[0]->product->market->getFirstMediaUrl('image', 'thumb')
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'sound' => 'default',
            'id' => 'orders',
            'status' => 'done',
            'message' => $notification,
        ];
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
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
            'order_id' => $this->order['id'],
        ];
    }
}
