<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Library;

use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


trait FcmHelper
{


    protected function send(Model $model, $field, $title, $body, $tokens = null, $sound = 'default', $counter = 0)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound);

//        $dataBuilder = new PayloadDataBuilder();
//        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
//        $data = $dataBuilder->build();

        $tokens = $tokens ?? $model::select($field)->value($field)->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification);

//        $downstreamResponse->numberSuccess();
//        $downstreamResponse->numberFailure();
//        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $deleteTokens = $downstreamResponse->tokensToDelete();

        if (count($deleteTokens) >= 1) {
            $this->deleteTokens($model, $field, $deleteTokens);
        }

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $modifyToken = $downstreamResponse->tokensToModify();

        if (count($modifyToken)) {
            $this->modifyTokens($model, $field, $modifyToken);
        }

        // return Array - you should try to resend the message to the tokens in the array
        $resendTokens = $downstreamResponse->tokensToRetry();

        if (count($resendTokens) >= 1 && $counter <= 3) {
            $this->send($model, $field, $title, $body, $resendTokens, 'default', $counter++);
        }

        // return Array (key:token, value:error) - in production you should remove from your database the tokens present in this array
        $errorTokens = $downstreamResponse->tokensWithError();

        if (count($errorTokens) >= 1) {
            $this->deleteTokens($model, $field, $errorTokens);
        }

        return $downstreamResponse->numberSuccess();
    }


    private function deleteTokens(Model $model, string $field, array $tokens)
    {
        return $model::whereIn($field, $tokens)->delete();
    }


    private function modifyTokens(Model $model, string $field, array $tokens)
    {
        foreach ($tokens as $oldToken => $newToken) {

            $model::where($field, $oldToken)->update([$field => $newToken]);

        }
    }

}