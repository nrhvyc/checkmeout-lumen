<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client as Guzzle;
use App\User;

class CheckGoogleOAuth
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('id_token')) {
            if (self::verifyGoogle($request->input('id_token'))) {
                // Does this google token id match a user in the database?
                if (User::where('id_token', $request->input('id_token'))->count() > 0) {
                    return $next($request);
                }
                else {
                    $response = [
                    'code' => 401,
                    'status' => 'Unauthorized',
                    'data' => [],
                    'message' => 'Authorization Required'
                    ];
                    return response()->json($response, $response['code']);
                }
            }
            else {
                $response = [
                'code' => 401,
                'status' => 'Unauthorized',
                'data' => [],
                'message' => 'Authorization Required'
                ];
                return response()->json($response, $response['code']);
            }
        }
        else {
            $response = [
            'code' => 400,
            'status' => 'Bad Request',
            'data' => [],
            'message' => 'token info not provided'
            ];
            return response()->json($response, $response['code']);
        }

        return $next($request);
    }

    /**
     * Hit the Google endpoint and verify the token
     *
     * @param  string id_token
     * @return boolean
     */
    private function verifyGoogle($id_token) {
        $client = new Guzzle(['base_uri' => 'https://www.googleapis.com/oauth2/v3/']);
        $res = $client->request('GET', 'tokeninfo?id_token=' . $id_token);
        if ($res->getStatusCode() == 200) {
            return True;
        }
        else {
            return False;
        }
    }

}