<?php

namespace Mogreet;

class Request
{
    private static $_requiredParams = array(
        'system.ping'        => array(),
        'transaction.send'   => array('campaign_id', 'to', 'message'),
        'transaction.lookup' => array('message_id', 'hash'),
        'user.transactions'  => array('number'),
        'user.getopt'        => array('number'),
        'user.info'          => array('number'),
        'user.lookup'        => array('number'),
        'user.uncache'       => array('number'),
        'keyword.list'       => array('campaign_id'),
        'keyword.check'      => array('keyword'),
        'keyword.add'        => array('campaign_id', 'keyword'),
        'keyword.remove'     => array('campaign_id', 'keyword'),
        'media.remove'       => array('content_id'),
        'media.list'         => array(),
        'media.upload'       => array('type', 'name'),
        'list.destroy'       => array('list_id', 'name'),
        'list.empty'         => array('list_id', 'name'),
        'list.download'      => array('list_id'),
        'list.send'          => array('list_id', 'campaign_id', 'message'),
        'list.prune'         => array('list_id', 'numbers'),
        'list.append'        => array('list_id', 'numbers'),
        'list.create'        => array('name'),
        'list.list'          => array(),
        'list.info'          => array('list_id'),
    );

    protected static function _checkParams($api, $params)
    {
        $missingParams = array_diff(static::$_requiredParams[$api], array_keys($params));
        if (count($missingParams) != 0) {
            throw new Exception(
                'Missing required params: '. implode($missingParams, ', '), $api, $params
            );
        }
    }

    public static function postRequest($base, $api, $params, $multipart) 
    {
        static::_checkParams($api, $params);

        if ( ($ch = curl_init()) === false) {
            throw new Exception( 'Could not initialize cURL.');
        }

        $curlOpts = [
            CURLOPT_URL => sprintf( '%s/%s/%s', Client::BASE_API, $base, $api),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_USERAGENT => Client::USER_AGENT,
            CURLOPT_POSTFIELDS => $multipart ? $params : http_build_query( $params)
        ];

        curl_setopt_array( $ch, $curlOpts);

        try {
            if ( ( $data = curl_exec( $ch)) === false) {
                throw new Exception( curl_error( $ch), $api, $params, curl_errno( $ch));
            }

            return $data;
        } finally {
            curl_close( $ch);
        }
    }
}
