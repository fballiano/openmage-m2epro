<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  2011-2017 ESS-UA [M2E Pro]
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Model_Ebay_Template_Synchronization_ChangeProcessor
    extends Ess_M2ePro_Model_Template_Synchronization_ChangeProcessorAbstract
{
    const INSTRUCTION_TYPE_REVISE_QTY_ENABLED            = 'template_synchronization_revise_qty_enabled';
    const INSTRUCTION_TYPE_REVISE_QTY_DISABLED           = 'template_synchronization_revise_qty_disabled';
    const INSTRUCTION_TYPE_REVISE_QTY_SETTINGS_CHANGED   = 'template_synchronization_revise_qty_settings_changed';

    const INSTRUCTION_TYPE_REVISE_PRICE_ENABLED          = 'template_synchronization_revise_price_enabled';
    const INSTRUCTION_TYPE_REVISE_PRICE_DISABLED         = 'template_synchronization_revise_price_disabled';

    const INSTRUCTION_TYPE_REVISE_TITLE_ENABLED          = 'template_synchronization_revise_title_enabled';
    const INSTRUCTION_TYPE_REVISE_TITLE_DISABLED         = 'template_synchronization_revise_title_disabled';

    const INSTRUCTION_TYPE_REVISE_SUBTITLE_ENABLED       = 'template_synchronization_revise_subtitle_enabled';
    const INSTRUCTION_TYPE_REVISE_SUBTITLE_DISABLED      = 'template_synchronization_revise_subtitle_disabled';

    const INSTRUCTION_TYPE_REVISE_DESCRIPTION_ENABLED    = 'template_synchronization_revise_description_enabled';
    const INSTRUCTION_TYPE_REVISE_DESCRIPTION_DISABLED   = 'template_synchronization_revise_description_disabled';

    const INSTRUCTION_TYPE_REVISE_IMAGES_ENABLED         = 'template_synchronization_revise_images_enabled';
    const INSTRUCTION_TYPE_REVISE_IMAGES_DISABLED        = 'template_synchronization_revise_images_disabled';

    const INSTRUCTION_TYPE_REVISE_CATEGORIES_ENABLED     = 'template_synchronization_revise_categories_enabled';
    const INSTRUCTION_TYPE_REVISE_CATEGORIES_DISABLED    = 'template_synchronization_revise_categories_disabled';

    const INSTRUCTION_TYPE_REVISE_PARTS_ENABLED          = 'template_synchronization_revise_parts_enabled';
    const INSTRUCTION_TYPE_REVISE_PARTS_DISABLED         = 'template_synchronization_revise_parts_disabled';

    const INSTRUCTION_TYPE_REVISE_PAYMENT_ENABLED        = 'template_synchronization_revise_payment_enabled';
    const INSTRUCTION_TYPE_REVISE_PAYMENT_DISABLED       = 'template_synchronization_revise_payment_disabled';

    const INSTRUCTION_TYPE_REVISE_SHIPPING_ENABLED       = 'template_synchronization_revise_shipping_enabled';
    const INSTRUCTION_TYPE_REVISE_SHIPPING_DISABLED      = 'template_synchronization_revise_shipping_disabled';

    const INSTRUCTION_TYPE_REVISE_RETURN_ENABLED         = 'template_synchronization_revise_return_enabled';
    const INSTRUCTION_TYPE_REVISE_RETURN_DISABLED        = 'template_synchronization_revise_return_disabled';

    const INSTRUCTION_TYPE_REVISE_OTHER_ENABLED          = 'template_synchronization_revise_other_enabled';
    const INSTRUCTION_TYPE_REVISE_OTHER_DISABLED         = 'template_synchronization_revise_other_disabled';

    //########################################

    protected function getInstructionsData(Ess_M2ePro_Model_ActiveRecord_Diff $diff, $status)
    {
        /** @var Ess_M2ePro_Model_Ebay_Template_Synchronization_Diff $diff */

        $data = parent::getInstructionsData($diff, $status);

        if ($diff->isReviseQtyEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_QTY_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 80 : 5,
            );
        } elseif ($diff->isReviseQtyDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_QTY_DISABLED,
                'priority'  => 5,
            );
        } elseif ($diff->isReviseQtySettingsChanged()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_QTY_SETTINGS_CHANGED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 80 : 5,
            );
        }

        //----------------------------------------

        if ($diff->isRevisePriceEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_PRICE_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 60 : 5,
            );
        } elseif ($diff->isRevisePriceDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_PRICE_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseTitleEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_TITLE_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseTitleDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_TITLE_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseSubtitleEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_SUBTITLE_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseSubtitleDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_SUBTITLE_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseDescriptionEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_DESCRIPTION_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseDescriptionDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_DESCRIPTION_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseImagesEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_IMAGES_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseImagesDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_IMAGES_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseCategoriesEnabled()) {
            $data[] = array(
                'type'     => self::INSTRUCTION_TYPE_REVISE_CATEGORIES_ENABLED,
                'priority' => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseCategoriesDisabled()) {
            $data[] = array(
                'type'     => self::INSTRUCTION_TYPE_REVISE_CATEGORIES_DISABLED,
                'priority' => 5,
            );
        }

        //----------------------------------------

        if ($diff->isRevisePartsEnabled()) {
            $data[] = array(
                'type'     => self::INSTRUCTION_TYPE_REVISE_PARTS_ENABLED,
                'priority' => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isRevisePartsDisabled()) {
            $data[] = array(
                'type'     => self::INSTRUCTION_TYPE_REVISE_PARTS_DISABLED,
                'priority' => 5,
            );
        }

        //----------------------------------------

        if ($diff->isRevisePaymentEnabled()) {
            $data[] = array(
                'type'     => self::INSTRUCTION_TYPE_REVISE_PAYMENT_ENABLED,
                'priority' => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isRevisePaymentDisabled()) {
            $data[] = array(
                'type'     => self::INSTRUCTION_TYPE_REVISE_PAYMENT_DISABLED,
                'priority' => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseShippingEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_SHIPPING_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseShippingDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_SHIPPING_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseReturnEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_RETURN_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseReturnDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_RETURN_DISABLED,
                'priority'  => 5,
            );
        }

        //----------------------------------------

        if ($diff->isReviseOtherEnabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_OTHER_ENABLED,
                'priority'  => $status === Ess_M2ePro_Model_Listing_Product::STATUS_LISTED ? 30 : 5,
            );
        } elseif ($diff->isReviseOtherDisabled()) {
            $data[] = array(
                'type'      => self::INSTRUCTION_TYPE_REVISE_OTHER_DISABLED,
                'priority'  => 5,
            );
        }

        return $data;
    }

    //########################################
}
