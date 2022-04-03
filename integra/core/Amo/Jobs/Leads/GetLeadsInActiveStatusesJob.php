<?php

namespace Nikacrm\Core\Amo\Jobs\Leads;

use Nikacrm\Core\Amo\Actions\Leads\GetAllLeadsWithFilterAction;
use Nikacrm\Core\Amo\Actions\Pipelines\GetAllActiveStatusesAction;
use Nikacrm\Core\Amo\Base\AmoJob;
use Nikacrm\Core\Amo\Filters\FilterLeadsByParams;

class GetLeadsInActiveStatusesJob extends AmoJob
{

    private function prepareActiveStatusesParam(array $pipelinesToDistribute)
    {
        $result = [];
        foreach ($pipelinesToDistribute as $pipelineId => $pipeline) {
            $activeStatuses = $pipeline['active_statuses'];
            foreach ($activeStatuses as $activeStatus) {
                $result[] = [
                  'status_id'   => $activeStatus,
                  'pipeline_id' => $pipelineId,
                ];
            }
        }


        return $result;
    }

    protected function logic()
    {
        $activeStatuses = (new GetAllActiveStatusesAction())->exec();

        $pipelinesIds          = $this->params['pipeline_ids'] ?? [];
        $pipelinesToDistribute = [];

        if ($pipelinesIds) {
            foreach ($pipelinesIds as $pipelineId) {
                if (isset($activeStatuses[$pipelineId])) {
                    $pipelinesToDistribute[$pipelineId] = $activeStatuses[$pipelineId];
                }
            }
        } else {
            foreach ($activeStatuses as $pipelineId => $status) {
                $pipelinesToDistribute[$pipelineId] = $status;
            }
        }

        $preparedStatuses = $this->prepareActiveStatusesParam($pipelinesToDistribute);
        $params           = [
          'statuses' => $preparedStatuses,
        ];
        $filter           = (new FilterLeadsByParams())->create($params);
        $leads            = (new GetAllLeadsWithFilterAction($filter))->exec();

        return $leads->toArray();
    }

}