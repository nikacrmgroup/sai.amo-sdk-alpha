<?php

namespace Nikacrm\Core\Amo\Actions\Tags;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoTagsAction extends AmoAction
{

    protected $apiTags; //api объект библиотеки амо
    protected $tagsService; //обертка api методов для тэгов

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->tagsService = $this->apiClient->tagsService();
        $this->apiTags     = $this->tagsService->api;
    }


}