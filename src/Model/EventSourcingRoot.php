<?php

namespace Banking\Account\Model;

interface EventSourcingRoot extends Entity
{
    public static function blank(Identity $identity): EventSourcingRoot;
}