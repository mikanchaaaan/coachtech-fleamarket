<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $exhibition;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($exhibition)
    {
        $this->exhibition = $exhibition;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        // 最初の取引を取得
        $transaction = $this->exhibition->transactions()->first();

        // receiver_id から User を取得
        $receiver = $transaction ? User::find($transaction->receiver_id) : null;

        return $this->subject('取引が完了しました')
            ->view('emails.transaction_completed')
            ->with([
                'exhibitionTitle' => $this->exhibition->title,
                'receiver' => $receiver,  // 受取人情報をビューに渡す
            ]);
    }
}
