<?php

namespace Banking\Account\Model;

enum FinancialTransactionType : string
{
    case DEBIT = 'D';
    case CREDIT = 'C';
}