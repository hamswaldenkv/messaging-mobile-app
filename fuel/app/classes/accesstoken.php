<?php

use Fuel\Core\Input;

class AccessToken
{

    private static function getBearerToken()
    {
        $headers = Utils::getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function getToken()
    {
        $accessToken = Input::get('access_token', null);
        if ($accessToken == null) $accessToken = self::getBearerToken();
        return $accessToken;
    }

    public static function check()
    {
        $token = self::getToken();

        $result = Model_Access_Token::query()
            ->where('access_token', $token)
            ->where('status', 1)
            //->where('expires_at', '>', time())
            ->get_one();

        if ($result == null)
            Printer::error('USER_NOT_AUTHENTICATED', 'A valid access_token is required to access to data', 41001, 401);

        $userResult = Model_User::find($result->user_id);

        if ($userResult == null)
            Printer::error('USER_NOT_AUTHENTICATED', 'A valid access_token is required to access to data', 41001, 401);

        $userResult->scope = $result->scope;
        $userResult->token = $token;
        return $userResult;
    }
}
