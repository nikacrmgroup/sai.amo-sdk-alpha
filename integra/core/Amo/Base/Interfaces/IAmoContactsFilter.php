<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\ContactsFilter;

interface IAmoContactsFilter
{

    public function create(): ContactsFilter;


}