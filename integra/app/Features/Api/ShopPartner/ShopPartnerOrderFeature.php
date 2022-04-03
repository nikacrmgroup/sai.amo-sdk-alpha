<?php

namespace Nikacrm\App\Features\Api\ShopPartner;

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;

use Nikacrm\App\Features\Api\Mappers\AmoMapper;
use Nikacrm\App\Features\Api\Resources\ResourceFactory;
use Nikacrm\App\Features\Transactions\TransactionsFeature;
use Nikacrm\Core\Amo\Actions\Account\GetAccountAction;
use Nikacrm\Core\Amo\Actions\Contacts\GetAllContactsAction;

use Nikacrm\Core\Amo\Actions\Products\GetAllProductsAction;
use Nikacrm\Core\Amo\Base\AmoDTO;
use Nikacrm\Core\Amo\DTO\CatalogElementDTO;
use Nikacrm\Core\Amo\DTO\LeadDTO;

use Nikacrm\Core\Amo\Jobs\Leads\AddLeadJob;
use Nikacrm\Core\Amo\Jobs\Notes\CreateCommonNoteForLeadCollectionJob;
use Nikacrm\Core\Base\Feature;
use Nikacrm\Core\Helpers\AmoHelper;

class ShopPartnerOrderFeature extends Feature
{

    public const STRINGS_DIVIDER = '======';

    public function create(): void
    {
        $this->logger->save('🟢 Старт логики создания заказа '.getmypid());

        $requestData = $this->getRequestData();
        //$amoDto = $this->prepareDto($requestData);

        if (!$requestData) {
            $this->logger->save('💛 Финиш логики создания заказа. Пустой запрос '.getmypid());
            die();
        }
        $text            = $this->prepareCommonNoteText($requestData);
        $responseJsonLog = $this->addRequestJSONToLog($requestData);
        (new TransactionsFeature())->log($text.$responseJsonLog, $requestData['meta']['customer_id']);


        /* @var \Nikacrm\Core\Amo\DTO\LeadDTO $amoDto */
        $amoDto = $this->prepareDto($requestData);

        $this->linkContacts($amoDto, $requestData);

        $this->linkProducts($amoDto, $requestData);

        $this->transformLogic($amoDto, $requestData);


        /* @var \AmoCRM\Collections\Leads\LeadsCollection $leadCollection */
        $leadCollection = (new AddLeadJob())->exec(['dto' => $amoDto]);

        if (!$leadCollection || $leadCollection->count() === 0) {
            $this->logger->save('❌ Ошибка логики создания сделки, пустой ответ.'.getmypid(), 'error');
            echo je([
              'status'  => 204,
              'message' => 'Ошибка логики создания сделки, пустой ответ',
            ]);
            die();
        }

        $this->createCommonNote($leadCollection, $text);


        $responseText = $this->prepareAmoResponseText($leadCollection);
        (new TransactionsFeature())->log($responseText, $requestData['meta']['customer_id']);
        $this->logger->save('🟦 Финиш логики создания сделки '.getmypid());
        /*echo je([
          'status'  => 200,
          'message' => 'Сделка добавлена успешно',
        ]);*/
        exit();
    }

    private function getRequestData(): array
    {
        $requestResource = ResourceFactory::get('shop.partners');

        /* @var \Nikacrm\App\Features\Api\Resources\Custom\ShopPartnersResource $requestResource */
        $requestData = $requestResource->getData();
        $this->logger->save('🌼 Получен и подготовлен запрос: '.je($requestData));


        return $requestData;
    }

    private function prepareCommonNoteText(array $requestData): string
    {
        $rows = [];
        //Формируем строку товаров

        if ($requestData['products']) {
            $rows[] = "Товары ";
            $rows[] = self::STRINGS_DIVIDER;
            foreach ($requestData['products'] as $product) {
                $price = number_format($product['price'], 0);
                $total = number_format($product['total'], 0);

                $rows[] = "{$product['name']}, {$product['quantity']}шт., цена: {$price}, итого: {$total}";
            }
            $rows[] = t('order.total').": ".number_format($requestData['fields']['total'] ?? '', 0);
        }
        $rows[]    = self::STRINGS_DIVIDER;
        $orderInfo = [

          'order.lastname'         => $requestData['fields']['lastname'] ?? '',
          //'order.email'            => $requestData['fields']['email'] ?? '',
          'order.telephone'        => $requestData['fields']['telephone'] ?? '',
          'order.paymentMethod'    => $requestData['fields']['payment_method'] ?? '',
          'order.shippingMethod'   => $requestData['fields']['shipping_method'] ?? '',
          'order.shippingAddress1' => $requestData['fields']['shipping_address_1'] ?? '',
          'order.shippingCity'     => $requestData['fields']['shipping_city'] ?? '',
          'order.comment'          => $requestData['fields']['comment'] ?? '',
          'order.customer_id'      => $requestData['meta']['customer_id'] ?? '',
        ];

        foreach ($orderInfo as $orderRowName => $orderRowValue) {
            if ($orderRowValue) {
                $rows[] = t($orderRowName)." : {$orderRowValue}";
            }
        }

        //        $rows[] = self::STRINGS_DIVIDER;
        //        $rows[] = 'Запрос в формате JSON: '.je($requestData);

        $text = implode(PHP_EOL, $rows);


        return $text;
    }

    private function addRequestJSONToLog($requestData): string
    {
        $rows[] = PHP_EOL.self::STRINGS_DIVIDER;
        $rows[] = 'Запрос в формате JSON: '.je($requestData);

        $text = implode(PHP_EOL, $rows);

        return $text;
    }

    /**
     * Готовим dto из запроса
     * @param  array  $requestData
     * @return \Nikacrm\Core\Amo\Base\AmoDTO
     */
    private function prepareDto(array $requestData): AmoDTO
    {
        $amoMapper = new AmoMapper();

        //todo complex from config
        /* @var LeadDTO $amoDto */
        $amoDto = $amoMapper->prepareDto($requestData, 'lead');

        $this->logger->save('🌼 Подготовлен dto: '.je(dismount($amoDto)), 'debug');

        return $amoDto;
    }

    private function linkContacts(LeadDTO $amoDto, array $requestData): void
    {
        /*Находим контакты по id партнера*/
        $contacts = $this->findContactsByPartnerId($requestData['meta']);

        if ($contacts) {
            $contactIds = array_keys($contacts);
            $amoDto->setLinkedContactIds($contactIds);
        }
    }

    private function linkProducts(LeadDTO $amoDto, $requestData): void
    {
        $requestProducts = $requestData['products'];
        if ($requestProducts) {
            $catalogElementDtoArray = [];

            $amoProductsCollection = $this->getAmoProductsCollection();
            $amoProducts           = $amoProductsCollection->toArray();

            $namedAmoProducts = [];
            foreach ($amoProducts as $amoProduct) {
                $namedAmoProducts[$amoProduct['name']] = $amoProduct;
            }

            $linkedCatalogElementsCollection = new CatalogElementsCollection();
            /*Ищем товары по имени*/
            foreach ($requestProducts as $requestProduct) {
                if (isset($namedAmoProducts[$requestProduct['name']])) {
                    $catalogElementModel = $amoProductsCollection->getBy('name', $requestProduct['name']);

                    if ($catalogElementModel) {
                        $catalogElementModel->setQuantity($requestProduct['quantity'] ?? 1);
                        $linkedCatalogElementsCollection->add($catalogElementModel);

                        /*Создаем DTO товара с "запасной" meta информацией*/
                        $catalogElementDto        = $this->createCatalogElementDto($namedAmoProducts, $requestProduct);
                        $catalogElementDtoArray[] = $catalogElementDto;
                    }
                }
            }
            $amoDto->setLinkedCatalogElementsDto($catalogElementDtoArray);
            $amoDto->setLinkedCatalogElementsCollection($linkedCatalogElementsCollection);

            //Пишем итого в бюджет сделки
            //$amoDto->setPrice($requestData['fields']['total'] ?? 0);
        }
    }

    private function transformLogic(LeadDTO $dto, array $requestData): void
    {
        //$this->updateLeadNameWithAmoProducts($dto);
        $this->updateLeadNameWithSiteProducts($dto, $requestData);
    }

    private function createCommonNote(LeadsCollection $leadCollection, string $text): void
    {
        $params = [
          'leads' => $leadCollection,
          'text'  => $text,
        ];
        (new CreateCommonNoteForLeadCollectionJob())->exec($params);
    }

    private function prepareAmoResponseText(LeadsCollection $leadCollection): string
    {
        $account          = (new GetAccountAction())->exec();
        $accountSubdomain = $account['subdomain'];
        $leadsUrls        = [];
        $leads            = $leadCollection->toArray();
        foreach ($leads as $lead) {
            $leadsUrls[] = "<a href='https://{$accountSubdomain}.amocrm.ru/leads/detail/{$lead['id']}' target='_blank' >{$lead['id']}</a>";
        }
        $leadsUrlsText = implode(', ', $leadsUrls);

        $text = "Создана сделка: ";
        if (count($leads) > 1) {
            $text = "Созданы сделки: ";
        }
        $text .= $leadsUrlsText;

        return $text;
    }

    private function findContactsByPartnerId($meta)
    {
        if (!isset($meta['customer_id'])) {
            return false;
        }

        $cfId    = $this->config->contact_cf_partner_id;
        $cfValue = $meta['customer_id'];

        /* @var \AmoCRM\Collections\ContactsCollection $allContactCollection */
        $allContactCollection = (new GetAllContactsAction())->exec();
        $allContacts          = $allContactCollection->toArray();

        $contactsFound = AmoHelper::getEntityByCfValueAndId($allContacts, $cfId, $cfValue);

        return $contactsFound;
    }

    public function getAmoProductsCollection(): CatalogElementsCollection
    {
        $productsCollection = (new GetAllProductsAction())->exec();


        return $productsCollection;
    }

    private function createCatalogElementDto(array $namedAmoProducts, array $requestProduct): CatalogElementDTO
    {
        $amoProduct        = $namedAmoProducts[$requestProduct['name']];
        $catalogElementDto = new CatalogElementDTO();
        $catalogElementDto
          ->setId($amoProduct['id'])
          ->setPrice($requestProduct['price'] ?? 0)
          ->setQty($requestProduct['quantity'] ?? 1)
          ->setTotal($requestProduct['total'] ?? 0)
          ->setRaw($requestProduct);

        return $catalogElementDto;
    }

    private function updateLeadNameWithSiteProducts(LeadDTO $dto, array $requestData): void
    {
        /*В заголовок лида пишем товары*/
        $leadName = $dto->getLeadName();
        $products = $requestData['products'];
        if ($products) {
            $productsNames     = array_column($products, 'name');
            $productsNamesText = implode(', ', $productsNames);

            if ($leadName) {
                $leadName .= ', ';
            }
            $leadName .= $productsNamesText;
        }
        if (!$leadName) {
            $leadName = t('default.lead_name');
        }
        $dto->setLeadName($this->prepareLeadName($leadName));
    }

    private function prepareLeadName(string $leadName): string
    {
        $leadName = htmlspecialchars_decode($leadName, ENT_QUOTES);

        return $leadName;
    }

    private function updateLeadNameWithAmoProducts(LeadDTO $dto): void
    {
        /*В заголовок лида пишем товары*/
        $leadName                  = $dto->getLeadName();
        $catalogElementsCollection = $dto->getLinkedCatalogElementsCollection();
        if ($catalogElementsCollection && $catalogElementsCollection->count() > 0) {
            $catalogElements      = $catalogElementsCollection->toArray();
            $catalogElementsNames = array_column($catalogElements, 'name');


            $catalogElementsNamesText = implode(', ', $catalogElementsNames);

            if ($leadName) {
                $leadName .= ', ';
            }
            $leadName .= $catalogElementsNamesText;

            $dto->setLeadName($leadName);

            $stop = 'Stop';
        }
    }


}