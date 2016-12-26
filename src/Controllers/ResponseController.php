<?php
/**
 * @author  Andrey Helldar <helldar@ai-rus.com>
 * @version 2016-12-27
 * @since   1.0
 */

namespace Helldar\Pochta\Controllers;


class ResponseController
{
    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-26
     * @since   1.0
     *
     * @param $content
     *
     * @return string
     */
    public static function success($content)
    {
        $result = [
            'response' => [
                'status'  => 'ok',
                'content' => $content,
            ],
        ];

        return json_encode($result);
    }

    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-26
     * @since   1.0
     *
     * @param $content
     * @param $code
     *
     * @return string
     */
    public static function error($code, $content = null)
    {
        $result = [
            'error' => [
                'status'  => 'error',
                'code'    => $code,
                'content' => !empty($content) ? $content : trans('pochta::' . (string)$code),
            ],
        ];

        return json_encode($result);
    }
}