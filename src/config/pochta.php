<?php
/**
 * Протокол Единичного доступа реализован на основе SOAP (Simple Object Access Protocol).
 *
 * Базовый протокол: SOAP 1.2
 *
 * Бесплатная версия ограничивает количество отправляемых запросов в размере 100 запросов в сутки.
 * Для получения безлимитного доступа необходимо заключить договор с Почтой России от имени компании.
 *
 * @see     https://tracking.pochta.ru/request
 *
 * @author  Andrey Helldar <helldar@ai-rus.com>
 * @version 2016-12-26
 * @since   1.0
 *
 * @link    https://tracking.pochta.ru/specification
 */
return [
    /**
     * Адрес для Единичного доступа.
     */
    'api_url_one'  => 'https://tracking.russianpost.ru/rtm34?wsdl',

    /**
     * Логин для доступа к API Сервиса отслеживания.
     * Может быть получен в разделе Настройки доступа.
     *
     * @link https://tracking.pochta.ru/access-settings
     */
    'api_login'    => env('POCHTA_LOGIN'),

    /**
     * Пароль для доступа к API Сервиса отслеживания.
     * Может быть получен в разделе Настройки доступа.
     *
     * @link https://tracking.pochta.ru/access-settings
     */
    'api_password' => env('POCHTA_PASSWORD'),
];