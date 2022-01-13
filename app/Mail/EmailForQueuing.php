<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailForQueuing extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('sender@example.com', 'irunning')
            ->subject('Logs message')
            ->view('mail') //html視圖
            // ->text('hahaha'); //純文字版本
            ->with([ //可透過with()整理傳入參數
                'title' => $this->params['title'],
                'main' => $this->params['main'],
            ]);
            // ->attach('/path/to/file', [ //夾帶檔案之檔案路徑
            //     'as' => 'name.pdf', //定義檔案顯示名稱
            //     'mime' => 'application/pdf',
            // ]);
    }
}
