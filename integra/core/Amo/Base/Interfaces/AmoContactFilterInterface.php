<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\ContactsFilter;

interface AmoContactFilterInterface
{

    public function create(): ContactsFilter;


}