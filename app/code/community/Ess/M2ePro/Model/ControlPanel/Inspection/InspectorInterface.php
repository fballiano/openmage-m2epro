<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

interface Ess_M2ePro_Model_ControlPanel_Inspection_InspectorInterface
{
    /**
     * @return Ess_M2ePro_Model_ControlPanel_Inspection_Issue[]
     */
    public function process();
}