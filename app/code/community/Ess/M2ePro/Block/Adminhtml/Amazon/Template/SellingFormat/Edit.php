<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Block_Adminhtml_Amazon_Template_SellingFormat_Edit
    extends Ess_M2ePro_Block_Adminhtml_Amazon_Template_Edit
{
    //########################################

    public function __construct()
    {
        parent::__construct();

        $this->setId('amazonTemplateSellingFormatEdit');
        $this->_blockGroup = 'M2ePro';
        $this->_controller = 'adminhtml_amazon_template_sellingFormat';
        $this->_mode = 'edit';

        if (!Mage::helper('M2ePro/Component')->isSingleActiveComponent()) {
            $componentName = Mage::helper('M2ePro/Component_Amazon')->getTitle();

            if ($this->isEditMode()) {
                $this->_headerText = Mage::helper('M2ePro')->__(
                    'Edit %component_name% Selling Policy "%template_title%"',
                    $componentName,
                    $this->escapeHtml(Mage::helper('M2ePro/Data_Global')->getValue('temp_data')->getTitle())
                );
            } else {
                $this->_headerText = Mage::helper('M2ePro')->__('Add %component_name% Selling Policy', $componentName);
            }
        } else {
            if ($this->isEditMode()) {
                $this->_headerText = Mage::helper('M2ePro')->__(
                    'Edit Selling Policy "%template_title%"',
                    $this->escapeHtml(Mage::helper('M2ePro/Data_Global')->getValue('temp_data')->getTitle())
                );
            } else {
                $this->_headerText = Mage::helper('M2ePro')->__('Add Selling Policy');
            }
        }

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->removeButton('add');
        $this->removeButton('save');
        $this->removeButton('edit');

        $url = Mage::helper('M2ePro')->getBackUrl('list');
        $this->_addButton(
            'back',
            array(
                'id'      => 'back_button',
                'label'   => Mage::helper('M2ePro')->__('Back'),
                'onclick' => 'AmazonTemplateSellingFormatObj.back_click(\'' . $url . '\')',
                'class'   => 'back'
            )
        );

        $isSaveAndClose = (bool)$this->getRequest()->getParam('close_on_save', false);

        if (!$isSaveAndClose && Mage::helper('M2ePro/Data_Global')->getValue('temp_data')
            && Mage::helper('M2ePro/Data_Global')->getValue('temp_data')->getId()) {
            $this->_addButton(
                'duplicate',
                array(
                    'id'      => 'duplicate_button',
                    'label'   => Mage::helper('M2ePro')->__('Duplicate'),
                    'onclick' => 'AmazonTemplateSellingFormatObj.duplicate_click'
                        . '(\'amazon-template-sellingFormat\')',
                    'class'   => 'add M2ePro_duplicate_button'
                )
            );

            $this->_addButton(
                'delete',
                array(
                    'id'      => 'delete_button',
                    'label'   => Mage::helper('M2ePro')->__('Delete'),
                    'onclick' => 'AmazonTemplateSellingFormatObj.delete_click()',
                    'class'   => 'delete M2ePro_delete_button'
                )
            );
        }

        if ($isSaveAndClose) {
            $this->removeButton('back');

            $this->_addButton(
                'save',
                array(
                    'id'      => 'save_and_close_button',
                    'label'   => Mage::helper('M2ePro')->__('Save And Close'),
                    'onclick' => 'AmazonTemplateSellingFormatObj.saveAndClose('
                        . '\'' . $this->getUrl('*/*/save', array('_current' => true)) . '\','
                        . ')',
                    'class'   => 'save'
                )
            );
        } else {
            $this->_addButton(
                'save',
                array(
                    'id'      => 'save_button',
                    'label'   => Mage::helper('M2ePro')->__('Save'),
                    'onclick' => 'AmazonTemplateSellingFormatObj.save_click('
                        . '\'\','
                        . '\'' . $this->getSaveConfirmationText() . '\','
                        . '\'' . Ess_M2ePro_Block_Adminhtml_Amazon_Template_Grid::TEMPLATE_SELLING_FORMAT . '\''
                        . ')',
                    'class'   => 'save'
                )
            );

            $this->_addButton(
                'save_and_continue',
                array(
                    'id'      => 'save_and_continue_button',
                    'label'   => Mage::helper('M2ePro')->__('Save And Continue Edit'),
                    'onclick' => 'AmazonTemplateSellingFormatObj.save_and_edit_click('
                        . '\'\','
                        . 'undefined,'
                        . '\'' . $this->getSaveConfirmationText() . '\','
                        . '\'' . Ess_M2ePro_Block_Adminhtml_Amazon_Template_Grid::TEMPLATE_SELLING_FORMAT . '\''
                        . ')',
                    'class'   => 'save'
                )
            );
        }
    }

    //########################################

    protected function isEditMode()
    {
        $templateModel = Mage::helper('M2ePro/Data_Global')->getValue('temp_data');

        return $templateModel && $templateModel->getId();
    }

    //########################################
}
