<?php

namespace App\Controller\V1;

use App\Controller\V1\Controller;
use App\Traits\Language;

// models
use App\Models\Users;

class CommandController extends Controller
{
    use Language;

    public $bot;

    public function __construct($bot)
    {
        $this->bot = $bot;
        $this->start();
    }

    function start()
    {
        $this->bot->command('start', function ($message) {
            $user = Users::firstOrCreate(['user_telegram_id' => $message->getFrom()->getId()]);
            if (in_array($message->getChat()->getId(), $this->management())) { // if user is admin
                $this->bot->sendMessage($message->getChat()->getId(), $this->translate('msg.welcome'));
            } else {
                $this->bot->sendMessage($message->getChat()->getId(), $this->translate('error.403'));
            }
        }); // end of command
    }

}