<?php

namespace Banking\Account;

use Banking\Account\Driver\Http\Action\Create;
use Banking\Account\Driver\Http\Action\Deposit;
use Banking\Account\Driver\Http\Action\Withdraw;

$app->post('/withdraw', Withdraw::class);
$app->post('/deposit', Deposit::class);
$app->post('/create', Create::class);