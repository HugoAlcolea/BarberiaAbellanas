<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'http://127.0.0.1:8000/google-auth/callback',
    ],
    'google_calendar' => [
        'client_id' => '101524065606646283809',
        'client_email' => 'laravelcalendaraccount@calendaralcolea.iam.gserviceaccount.com',
        'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCjpIKxjIJCRL0o\nYigQj4HVRzV9AOFfqSB3q4PLOThNmVXDc/RHCo4pIYIXsXNTi0MHuC1c+LUQFht/\nM5P1vH2WrRjyXsvJikwTxRovjmIq8TphZ+rnzizz1kVO6ZyWsen6eFsQtYdGemDL\nrb5ROowRUAQB0xdmvGW5DyMWv/n9dS6IX8fqKHhIGVaalbyZLMitumXeWF05yiNa\nKWBQetYk3KkY/4QauMOun8ZkqkmAqzUVhJhhpOd4S7pbNuA0MndisZbK/puV96uF\nb2qZu6zZjnTX/JY6Yt9fCd4B6IaBKcxN9PR3ZL1xhJCpTEmnTCGDMfRGFA0V2DrV\nhrFVyp8TAgMBAAECggEAFW4ZWXQLqO8EkJHOKmEIwSv6orynRxZIdLOG5mm/aFYg\nx6BbSKRmupczSLQJ5NJzfELP2gGOAKXMgwt3oHJHRiC0CCWG7YVJcjtZMwoj6/i8\nSv5HYoT7GRGh8PQh+BAjkpQJZPZI2V90ZqgBqPSQXEIMzEpfIaqhbatATp3keJ8W\nwhlZ0Zvs1eMHovrZ2oy8pmqwyN7Q8fxZ2Rgp21S21XQt4DRTgwTogCfCVrwUZ4b0\n72/+KriEzGywdYbpC3B2BeIqoRDvP3XnvWDUH111MFNeu/qXjzLrbmH2hkkbbM9j\n+PveusA8674HrEtCQNcc8GiQvil83DOUvDe7mx675QKBgQDX6m0ItHznRkSxMNWX\nGwc7IN9PlTvM53ydt4Qg4Sk2M8DDLGB/AWtgp6d9itDV7itCp02F/+pVHBTz2kFH\n+SgC4hQQ+7oZI4bRZoFiKSAOu6HlraExqbEHTkLA9eKR8O83YNgy/SHEuIxsrRTM\nekEzLvVtMio/Qkl4BqWjODtvzQKBgQDCBcMGO0zoksEyjz6yghxzCxfe7DlHYff6\n2L0NVrGP46f2FGfn7niyWm+oLpBS2PziHiDzfQQY9hzUEbB7EC15dWEezl7xXbh6\ndBoecn/kCl61sWi5kRVFsJmg2Z/sAVPlvAFHFtDIyg/yKZ4BwlJUyb6HcaBd0Ots\niUavU3aqXwKBgQCyX8rgDCu77QNG1y89fGjG4mtaFdGnf/4lVbzkZN9lFfBDzV+S\nzRtmaFsojgvlELPQhuK2tytiUKbGpiKUUTRK3XbyaOPfbMguKl7tN0hyo3QmiQDI\nRIYpoO/o9bnOE5usxmcWCKVjHYt1JLAwFTfUDxFWusDILf0AGY2xf/0qaQKBgBDm\nyZdoYQm9hwDtg4uvU+UdqUWtFExIl36NlOJtk+gx77a7DlYg6vi2chWXgNEGv/i+\noSY01L+L6PT8WYBO53c2J3C0j4a9IE3igUf3t3ZZBOkU/Ed2AQFiIe/mJU08zF8t\n4nqb3TIwj/ULdQ04LU3fiS5pUov5oBiDWNn7DlJHAoGBANBa3oaDvPl5tseZVomP\np6pULkwZgh6hyqd7rMbrSOFv0AKrohOaFWOGlBPvaX2Gg9qZACSd44urgNTCWJSo\n2G89Sa5TEg7+YkGzN2fMvBmkpNYlRoh93ItwMygbCH1OwlnHoUpa0dRLHvsQM0cE\nXXO0W76ss+P1tUNtpnjk2pUv\n-----END PRIVATE KEY-----\n',
        'service_account_credentials' => storage_path('app/service-accounts/calendaralcolea-978337d38352.json'),
    ],

];
