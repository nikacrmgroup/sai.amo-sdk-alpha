<?php

namespace Nikacrm\App\Features\Api\Resources\Custom;


use Nikacrm\App\Features\Api\Resources\BaseRequestResource;
use Nikacrm\App\Features\Api\Resources\RequestResourceInterface;


class ShopPartnersResource extends BaseRequestResource implements RequestResourceInterface
{

    public const CUSTOMER_GROUP_ID = '2';
    public const META_FIELDS       = [
      'invoice_prefix',
      'store_id',
      'store_name',
      'store_url',
      'customer_id',
      'customer_group_id',
      'affiliate_id',
      'commission',
      'marketing_id',
      'tracking',
      'language_id',
      'currency_id',
      'currency_code',
      'currency_value',
      'ip',
      'forwarded_ip',
      'user_agent',
      'accept_language',
      'contact_id',
    ];

    public function getData(): array
    {
        $data     = [
          'fields'   => [],
          'meta'     => [],
          'products' => [],
        ];
        $postData = $this->postData;
        if (!$this->validate($postData)) {
            return [];
        }
        $products = $postData['products'] ?? [];
        if ($products) {
            foreach ($products as $product) {
                /*Проверяем на пустые товары*/
                if ($product['product_id']) {
                    $data['products'][] = $product;
                }
            }

            unset($postData['products']);
        }
        $meta           = $this->prepareMeta($postData);
        $data['meta']   = $meta;
        $postData       = array_diff_assoc($postData, $meta);
        $data['fields'] = $this->prepareFields($postData);


        if (empty($data['fields']) && empty($data['meta'])) {
            return [];
        }

        return $data;
    }

    private function prepareFields($postData): array
    {
        $isNumeric = [
          'telephone',
        ];


        $prepared = $postData;

        /*Exclude logic*/
        foreach ($postData as $fieldName => $fieldValue) {
            /*Проверяем, если телефон не число - убираем его*/
            if ($fieldName === 'telephone' && !is_numeric($fieldName)) {
                //unset($prepared[$fieldName]);
            }
        }

        return $prepared;
    }

    private function validate($postData): bool
    {
        return true;
        //return isset($postData['customer_group_id']) && $postData['customer_group_id'] === self::CUSTOMER_GROUP_ID;
    }

    private function prepareMeta($postData): array
    {
        $meta = array_intersect_key($postData, array_flip(self::META_FIELDS));

        return $meta;
    }

}