<?php
/**
 * @author  Andrey Helldar <helldar@ai-rus.com>
 * @version 2016-12-26
 * @since   1.0
 */

namespace Helldar\Pochta;

use Helldar\Pochta\Controllers\ResponseController;

/**
 * Class Tracking
 *
 * @link    https://www.pochta.ru/support/business/api
 *
 * @package Helldar\Pochta
 */
class Tracking
{
    /**
     * @var string
     */
    private static $barcode = '';

    /**
     * Получение данных по одному треку.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-27
     * @since   1.0
     *
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
            $soap = new \SoapClient(config('pochta.api_url_one'), [
                'trace'        => 1,
                'soap_version' => SOAP_1_2,
            ]);

            $result = $soap->getOperationHistory(new \SoapParam(static::params(), 'OperationHistoryRequest'));

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
     * @version 2016-12-26
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
        $strlen          = mb_strlen(static::$barcode, 'UTF-8');

        return $strlen === 13 || $strlen === 14;
    }

    /**
     * Параметры.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-26
     * @since   1.0
     *
     * @return array
     */
    private static function params()
    {
        return [
            /**
             * Содержит элементы Barcode, MessageType, Language.
             */
            'OperationHistoryRequest' => [
                /**
                 * Идентификатор регистрируемого почтового отправления в одном из форматов:
                 * - внутрироссийский, состоящий из 14 символов (цифровой);
                 * - международный, состоящий из 13 символов (буквенно-цифровой) в формате S10.
                 */
                'Barcode'     => static::$barcode,
                /**
                 * Тип сообщения. Возможные значения:
                 *    0 - история операций для отправления;
                 *    1 - история операций для заказного уведомления по данному отправлению.
                 */
                'MessageType' => '0',
                /**
                 * Язык, на котором должны возвращаться названия операций/атрибутов и сообщения об ошибках.
                 * Допустимые значения:
                 *     RUS – использовать русский язык (используется по умолчанию);
                 *     ENG – использовать английский язык.
                 */
                'Language'    => 'RUS',
            ],
            /**
             * Содержит элементы login и password.
             * Атрибут soapenv:mustUnderstand элемента AuthorizationHeader должен содержать значение 1.
             */
            'AuthorizationHeader'     => [
                /**
                 * Логин для доступа к API Сервиса отслеживания.
                 * Может быть получен в разделе Настройки доступа.
                 *
                 * @link https://tracking.pochta.ru/access-settings
                 */
                'login'    => config('pochta.api_login'),
                /**
                 * Пароль для доступа к API Сервиса отслеживания.
                 * Может быть получен в разделе Настройки доступа.
                 *
                 * @link https://tracking.pochta.ru/access-settings
                 */
                'password' => config('pochta.api_password'),
            ],
        ];
    }
}
