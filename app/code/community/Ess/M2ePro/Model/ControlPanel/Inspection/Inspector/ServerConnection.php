<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Model_ControlPanel_Inspection_Inspector_ServerConnection
    implements Ess_M2ePro_Model_ControlPanel_Inspection_InspectorInterface
{
    //########################################

    public function process()
    {
        $issues = array();

        try {
            $response = Mage::helper('M2ePro/Server_Request')->single(
                array('timeout' => 30), null, null, false, false, true
            );

            $decoded = Mage::helper('M2ePro')->jsonDecode($response['body']);
            if (empty($decoded['response']['result'])) {
                $issues[] = Mage::getSingleton('M2ePro/ControlPanel_Inspection_Issue_Factory')->createIssue(
                    'Connection Failed',
                    $response['curl_info']
                );
            }
        } catch (Ess_M2ePro_Model_Exception_Connection $exception) {
            $additionalData = $exception->getAdditionalData();
            $curlInfo = array();

            if (!empty($additionalData['curl_info'])) {
                $curlInfo = $additionalData['curl_info'];
            }

            $issues[] = Mage::getSingleton('M2ePro/ControlPanel_Inspection_Issue_Factory')->createIssue(
                $exception->getMessage(),
                $curlInfo
            );
        }

        return $issues;
    }

    //########################################
}