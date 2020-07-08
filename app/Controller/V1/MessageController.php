<?php

namespace App\Controller\V1;

use App\Controller\V1\Controller;
use App\Helpers\Utilities;
use App\Traits\Language;
use App\Models\Users;
use InstagramScraper\Instagram;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Helper\Psr16Adapter;

class MessageController extends Controller
{

    use Language;

    public $bot;
    public $message;
    public $user;
    public $user_tg_id;
    public $text;

    private $groupIds = [-370312451];

    public function __construct($bot, $message)
    {
        $this->bot = $bot;
        $this->message = $message;
        $this->user_tg_id = $message->getChat()->getId();
        $this->text = $message->getText();
        //$user = Users::where('user_telegram_id', $this->user_tg_id)->first();
        $this->user = $user ?? null;

        $this->handle();
    }

    public function handle()
    {
        if (in_array($this->user_tg_id, $this->groupIds)) {
            $this->help();
            $this->requestForDetails();
        }
    }

    private function help()
    {
        if ($this->text == '/help')
            $this->bot->sendMessage($this->message->getChat()->getId(), $this->translate('msg.help'));
    }

    private function requestForDetails()
    {
        $reply_id = $this->message->getReplyToMessage()->getMessageId();
        $reply_text = $this->message->getReplyToMessage()->getText();
        if ($reply_id) {
            if ($this->text == '/r')
                $this->showInstagramResult($reply_id, $reply_text);
        }
    }

    private function showInstagramResult($reply_id = null, $text = null)
    {
        if (is_null($reply_id)) $reply_id = $this->message->getMessageId();
        if (is_null($text)) $text = $this->text;

        if (Utilities::validateInstagramLink($text)) {
            $instagram = Instagram::withCredentials(config("instagram.username"), config("instagram.password"), new Psr16Adapter("Files"));
            $instagram->login();
            $media = $instagram->getMediaByUrl($text);
            $message = "
👤 ناشر : {$media->getOwner()->getFullName()}
👁 تعداد لایک : " . number_format($media->getLikesCount()) . "
👁 تعداد بازدید ویدیو : " . number_format($media->getVideoViews()) . " 
💬 تعداد نظرات : " . number_format($media->getCommentsCount()) . " 
 📆 تاریخ ارسال : " . jdate($media->getCreatedTime()) . "";

            $this->bot->sendMessage($this->message->getChat()->getId(), $message, null, true, $reply_id);
        } else $this->bot->sendMessage($this->message->getChat()->getId(), $this->translate('msg.linkNotValid'), null, true, $reply_id);
    }
}