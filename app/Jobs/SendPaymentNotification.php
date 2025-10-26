<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class SendPaymentNotification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle()
    {
        // Example: send webhook to merchant


        echo "Sending payment notification for transaction ID: " . "\n";

        echo "Successfully sent payment notification for transaction ID: " ."\n";

        return true;

        // Could also trigger email, analytics, etc.
    }
}
