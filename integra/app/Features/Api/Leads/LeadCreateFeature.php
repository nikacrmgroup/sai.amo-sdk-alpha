<?php

namespace Nikacrm\App\Features\Api\Leads;

use Nikacrm\App\Features\Api\Mappers\AmoMapper;
use Nikacrm\App\Features\Api\Resources\ResourceFactory;
use Nikacrm\Core\Amo\Base\AmoDTO;
use Nikacrm\Core\Amo\Jobs\Leads\AddComplexLeadsJob;
use Nikacrm\Core\Base\Feature;

final class LeadCreateFeature extends Feature
{

    public function create()
    {
        $this->logger->save('🟢 Старт логики создания сделки '.getmypid());

        $requestData = $this->getRequestData();

        if (!$requestData) {
            $this->logger->save('💛 Финиш логики создания сделки. Пустой запрос '.getmypid());
            die();
        }
        $amoDto = $this->prepareDto($requestData);

        (new AddComplexLeadsJob())->exec(['dto' => $amoDto]);
        //$contactsCollection = (new GetContactsByCustomFieldsAction())->exec($params);
        $this->logger->save('🟦 Финиш логики создания сделки '.getmypid());
    }

    private function getRequestData(): array
    {
        $requestResource = ResourceFactory::get();

        /* @var \Nikacrm\App\Features\Api\Resources\Wordpress\MetformResource $requestResource */
        $requestData = $requestResource->getData();
        $this->logger->save('🌼 Получен и подготовлен запрос: '.je($requestData));


        return $requestData;
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
        $amoDto = $amoMapper->prepareDto($requestData, 'lead_complex');

        $this->logger->save('🌼 Подготовлен dto: '.je(dismount($amoDto)), 'debug');

        return $amoDto;
    }


}