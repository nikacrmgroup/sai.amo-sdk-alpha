<?php

namespace Nikacrm\Core\Amo\Filters\Contacts;

use AmoCRM\Filters\ContactsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoContactsFilter;

class FilterContactsByCustomField implements IAmoContactsFilter
{

    public function create($params = ['custom_fields' => []]): ContactsFilter
    {
        $customFields = $params['custom_fields'];
        $ids          = $params['ids'] ?? [];
        $filter       = new ContactsFilter();
        $filter->setLimit(500);
        $filter->setCustomFieldsValues($customFields);
        $filter->setOrder('created_at', 'desc');
        if ($ids) {
            $filter->setIds($ids);
        }

        return $filter;
    }
}