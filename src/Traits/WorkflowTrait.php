<?php

namespace Cage\LaravelWorkflow\Traits;

use Workflow;

/**
 * @author Boris Koumondji <brexis@yahoo.fr>
 */
trait WorkflowTrait
{
    protected $markingProperty = 'status';

    public function workflow_apply($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->apply($this, $transition);
    }

    public function workflow_can($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->can($this, $transition);
    }

    public function workflow_transitions($workflow = null)
    {
        return Workflow::get($this, $workflow)->getEnabledTransitions($this);
    }

    public function workflow_get($workflow = null)
    {
        return Workflow::get($this, $workflow);
    }

    public function getMarking()
    {
        $type = config("workflow.straight.type");
        $status = $this->{$this->markingProperty};
        var_dump($status);
        if (empty($status)) {
            return null;
        }

        if ($type == "state_machine") {
            if (is_array($status)) {
                return key($status);
            } else {
                $status = json_decode($status, true)??$status;
                return is_array($status) ? key($status) : $status;
            }
        } else {
            if (is_array($status)) {
                return $status;
            } else {
                return json_decode($status, true);
            }
        }
    }

    public function setMarking($currentPlace, $context = [])
    {
        $this->{$this->markingProperty} = $currentPlace;
    }
}
