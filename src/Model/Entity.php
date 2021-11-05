<?php

namespace Banking\Account\Model;

interface Entity
{
    public function getIdentity(): Identity;
}