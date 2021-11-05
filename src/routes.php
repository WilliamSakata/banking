<?php

namespace Banking\Account;

use Banking\Account\Driver\Action\AccountStatement;
use Banking\Account\Driver\Action\Balance;
use Banking\Account\Driver\Action\Deposit;
use Banking\Account\Driver\Action\Withdraw;

$app->post('/withdraw', Withdraw::class);
$app->post('/deposit', Deposit::class);
$app->get('/balance', Balance::class);
$app->get('/statement', AccountStatement::class);