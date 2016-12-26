<?php
/**
 * @author  Andrey Helldar <helldar@ai-rus.com>
 * @version 2016-12-26
 * @since   1.0
 */

namespace Helldar\Pochta;

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
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-26
     * @since   1.0
     *
     * @link    https://tracking.pochta.ru/specification
     */
    public static function one($barcode)
    {
        if (!static::validateBarcode($barcode)) {
            return 'Barcode not valid!';
        }

        $soap = new \SoapClient(config('pochta.api_url_one'), [
            'trace'        => 1,
            'soap_version' => SOAP_1_2,
        ]);

        $result = $soap->getOperationHistory(new \SoapParam(static::params(), 'OperationHistoryRequest'));

        foreach ($result->OperationHistoryData->historyRecord as $record) {
            dd($record);
        }
    }

    /**
     * Валидация трека.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-26
     * @since   1.0
     *
     * @param $barcode
     *
     * @return bool
     */
    private static function validateBarcode($barcode)
    {
        if (empty($barcode)) {
            return false;
        }

        $barcode = mb_convert_encoding(trim($barcode), 'UTF-8');
        $strlen  = mb_strlen($barcode, 'UTF-8');

        if ($strlen !== 13 && $strlen !== 14) {
            return false;
        }

        return true;
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
                'Barcode'     => $barcode,
                /**
                 * Тип сообщения. Возможные значения:
                 *    0 - история операций для отправления;
                 *    1 - история операций для заказного уведомления по данному отправлению.
                 */
                'MessageType' => 0,
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

//<?php
//$wsdlurl = 'https://tracking.russianpost.ru/rtm34?wsdl';
//$client2 = '';
//
//$client2 = new SoapClient($wsdlurl, array('trace' => 1, 'soap_version' => SOAP_1_2));
//
//$params3 = array ('OperationHistoryRequest' => array ('Barcode' => 'RA644000001RU', 'MessageType' => '0','Language' => 'RUS'),
//                  'AuthorizationHeader' => array ('login'=>'myLogin','password'=>'myPassword'));
//
//$result = $client2->getOperationHistory(new SoapParam($params3,'OperationHistoryRequest'));
//
//foreach ($result->OperationHistoryData->historyRecord as $record) {
//    printf("<p>%s </br>  %s, %s</p>",
//        $record->OperationParameters->OperDate,
//        $record->AddressParameters->OperationAddress->Description,
//        $record->OperationParameters->OperAttr->Name);
//};
//
