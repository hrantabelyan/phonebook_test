<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class Timezone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $client = new Client([
                'verify' => false,
                'http_errors' => false
            ]);
        } catch (\Throwable $e) {
            logger($e);
            return false;
        }

        try {
            $apiResponse = $client->request('GET', 'http://worldtimeapi.org/api/timezone', [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36',
                    'Accept' => 'application/json',
                ],
            ]);

            $statusCode = $apiResponse->getStatusCode();
        } catch (\Throwable $e) {
            logger($e);
            return false;
        }

        if ($statusCode !== 200) {
            logger('bad status code: ' . $statusCode);
            return false;
        }

        try {
            $timezones = json_decode((string) $apiResponse->getBody(), true);
            if ($timezones == null) {
                throw new \Exception('Could not decode the json response');
            }
        } catch (\Throwable $e) {
            logger('could not decode the json response');
            return false;
        }

        return in_array($value, $timezones);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute field must be a valid time zone.');
    }
}
