<?php
return [
    'gateways'=>[
        'offline_payment'=>Modules\Booking\Gateways\OfflinePaymentGateway::class,
        'myfatoora'=>Modules\Booking\Gateways\MyFatooraGateway::class,
        'paypal'=>Modules\Booking\Gateways\PaypalGateway::class,
        'stripe'=>Modules\Booking\Gateways\StripeGateway::class,
        'payrexx'=>Modules\Booking\Gateways\PayrexxGateway::class,
    ],
];
