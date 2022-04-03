<?php

namespace Nikacrm\App\Features\Api\Resources\Wordpress;


use Nikacrm\App\Features\Api\Resources\BaseRequestResource;
use Nikacrm\App\Features\Api\Resources\RequestResourceInterface;
use Nikacrm\Core\Container;


class MetformResource extends BaseRequestResource implements RequestResourceInterface
{

    public function getData(): array
    {
        $data     = [
          'fields' => [],
          'meta'   => [],
        ];
        $postData = $this->postData;
        $entries  = $postData['entries'] ?? [];
        if ($entries) {
            $data['fields'] = jd($entries);
            unset($postData['entries']);
        }
        $data['meta'] = $this->prepareMeta($postData);

        if (empty($data['fields']) && empty($data['meta'])) {
            return [];
        }

        return $data;
    }

    private function prepareMeta($postData)
    {
        return $postData;
    }

}