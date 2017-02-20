<?php
/**
 * @author  Andrey Helldar <helldar@ai-rus.com>
 *
 * @version 2016-12-26
 *
 * @since   1.0
 */
namespace Helldar\Pochta;

use Helldar\Pochta\Controllers\ResponseController;

/**
 * Class Tracking
 *
 * @link    https://www.pochta.ru/support/business/api
 */
class Tracking
{
    /**
     * @var string
     */
    private static $barcode = '';

    /**
     * @var int
     */
    private static $max_codes = 3000;

    /**
     * Получение данных по одному треку.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-27
     *
     * @since   1.0
     * @link    https://tracking.pochta.ru/specification
     *
     * @param $barcode
     *
     * @return string
     */
    public static function one($barcode)
    {
        static::$barcode = $barcode;

        if (!static::validateBarcode()) {
            return 'Barcode not valid!';
        }

        try {
            $soap = static::soap();
            $result = $soap->getOperationHistory(new \SoapParam(static::paramsOne(), 'OperationHistoryRequest'));

            return ResponseController::success($result->OperationHistoryData->historyRecord);
        } catch (\SoapFault $exception) {
            return ResponseController::error($exception->getCode(), $exception->getMessage());
        } catch (\ErrorException $exception) {
            return ResponseController::error(1);
        }
    }

    /**
     * Валидация трека.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-26
     *
     * @since   1.0
     *
     * @return bool
     */
    private static function validateBarcode()
    {
        if (empty(static::$barcode)) {
            return false;
        }

        static::$barcode = mb_convert_encoding(trim(static::$barcode), 'UTF-8');
        $strlen = mb_strlen(static::$barcode, 'UTF-8');

        return $strlen === 13 || $strlen === 14;
    }

    /**
     * Вызов к API вынесен в отдельный метод.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-27
     *
     * @since   1.0
     *
     * @return \SoapClient
     */
    private static function soap()
    {
        return new \SoapClient(config('pochta.api_url_one'), array(
            'trace' => 1,
            'soap_version' => SOAP_1_2,
        ));
    }

    /**
     * Параметры.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-26
     *
     * @since   1.0
     *
     * @return array
     */
    private static function paramsOne()
    {
        return array(
            /*
             * Содержит элементы Barcode, MessageType, Language.
             */
            'OperationHistoryRequest' => array(
                /*
                 * Идентификатор регистрируемого почтового отправления в одном из форматов:
                 * - внутрироссийский, состоящий из 14 символов (цифровой);
                 * - международный, состоящий из 13 символов (буквенно-цифровой) в формате S10.
                 */
                'Barcode' => static::$barcode,
                /*
                 * Тип сообщения. Возможные значения:
                 *    0 - история операций для отправления;
                 *    1 - история операций для заказного уведомления по данному отправлению.
                 */
                'MessageType' => '0',
                /*
                 * Язык, на котором должны возвращаться названия операций/атрибутов и сообщения об ошибках.
                 * Допустимые значения:
                 *     RUS – использовать русский язык (используется по умолчанию);
                 *     ENG – использовать английский язык.
                 */
                'Language' => 'RUS',
            ),
            /*
             * Содержит элементы login и password.
             * Атрибут soapenv:mustUnderstand элемента AuthorizationHeader должен содержать значение 1.
             */
            'AuthorizationHeader' => array(
                /*
                 * Логин для доступа к API Сервиса отслеживания.
                 * Может быть получен в разделе Настройки доступа.
                 *
                 * @link https://tracking.pochta.ru/access-settings
                 */
                'login' => config('pochta.api_login'),
                /*
                 * Пароль для доступа к API Сервиса отслеживания.
                 * Может быть получен в разделе Настройки доступа.
                 *
                 * @link https://tracking.pochta.ru/access-settings
                 */
                'password' => config('pochta.api_password'),
            ),
        );
    }

    /**
     * Режим Пакетного доступа позволяет отслеживать сразу несколько отправлений в одном запросе.
     * Запрос может содержать до 3000 почтовых идентификаторов отправлений.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-27
     *
     * @since   1.0
     *
     * @param array $barcodes
     *
     * @return string
     */
    public static function moreSend($barcodes = array())
    {
        if (!is_array($barcodes)) {
            return ResponseController::error(3);
        }

        if (sizeof($barcodes) > static::$max_codes) {
            return ResponseController::error(2);
        }

        $soap = static::soap();
        $result = $soap->getTicket(new \SoapParam(static::paramsMore(), ''));
    }

    /**
     * Параметры для пакетного запроса.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-27
     *
     * @since   1.0
     *
     * @return array
     */
    private static function paramsMore()
    {
        return array(
            /*
             * Содержит элементы Barcode, MessageType, Language.
             */
            'OperationHistoryRequest' => array(
                /*
                 * Идентификатор регистрируемого почтового отправления в одном из форматов:
                 * - внутрироссийский, состоящий из 14 символов (цифровой);
                 * - международный, состоящий из 13 символов (буквенно-цифровой) в формате S10.
                 */
                'Barcode' => static::$barcode,
                /*
                 * Тип сообщения. Возможные значения:
                 *    0 - история операций для отправления;
                 *    1 - история операций для заказного уведомления по данному отправлению.
                 */
                'MessageType' => '0',
                /*
                 * Язык, на котором должны возвращаться названия операций/атрибутов и сообщения об ошибках.
                 * Допустимые значения:
                 *     RUS – использовать русский язык (используется по умолчанию);
                 *     ENG – использовать английский язык.
                 */
                'Language' => 'RUS',
            ),
            /*
             * Содержит элементы login и password.
             * Атрибут soapenv:mustUnderstand элемента AuthorizationHeader должен содержать значение 1.
             */
            'AuthorizationHeader' => array(
                /*
                 * Логин для доступа к API Сервиса отслеживания.
                 * Может быть получен в разделе Настройки доступа.
                 *
                 * @link https://tracking.pochta.ru/access-settings
                 */
                'login' => config('pochta.api_login'),
                /*
                 * Пароль для доступа к API Сервиса отслеживания.
                 * Может быть получен в разделе Настройки доступа.
                 *
                 * @link https://tracking.pochta.ru/access-settings
                 */
                'password' => config('pochta.api_password'),
            ),
        );
    }
}
