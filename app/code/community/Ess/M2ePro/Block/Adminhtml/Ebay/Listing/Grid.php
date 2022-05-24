<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Block_Adminhtml_Ebay_Listing_Grid extends Ess_M2ePro_Block_Adminhtml_Listing_Grid
{
    const MASS_ACTION_ID_EDIT_PARTS_COMPATIBILITY = 'editPartsCompatibilityMode';

    //########################################

    public function __construct()
    {
        parent::__construct();
        $this->setId('ebayListingGrid');
    }

    protected function _prepareCollection()
    {
        // Get collection of listings
        $collection = Mage::helper('M2ePro/Component_Ebay')->getCollection('Listing');
        $collection->getSelect()->join(
            array('a'=>Mage::getResourceModel('M2ePro/Account')->getMainTable()),
            '(`a`.`id` = `main_table`.`account_id`)',
            array('account_title'=>'title')
        );
        $collection->getSelect()->join(
            array('m'=>Mage::getResourceModel('M2ePro/Marketplace')->getMainTable()),
            '(`m`.`id` = `main_table`.`marketplace_id`)',
            array('marketplace_title'=>'title')
        );

        $structureHelper = Mage::helper('M2ePro/Module_Database_Structure');

        $m2eproListing = $structureHelper->getTableNameWithPrefix('m2epro_listing');
        $m2eproEbayListing = $structureHelper->getTableNameWithPrefix('m2epro_ebay_listing');
        $m2eproListingProduct = $structureHelper->getTableNameWithPrefix('m2epro_listing_product');
        $m2eproEbayListingProduct = $structureHelper->getTableNameWithPrefix('m2epro_ebay_listing_product');

        $sql = "SELECT
            l.id                                           AS listing_id,
            COUNT(lp.id)                                   AS products_total_count,
            COUNT(CASE WHEN lp.status = 2 THEN lp.id END)  AS products_active_count,
            COUNT(CASE WHEN lp.status != 2 THEN lp.id END) AS products_inactive_count,
            IFNULL(SUM(elp.online_qty_sold), 0)            AS items_sold_count
        FROM `{$m2eproListing}` AS `l`
            INNER JOIN `{$m2eproEbayListing}` AS `el` ON l.id = el.listing_id
            LEFT JOIN `{$m2eproListingProduct}` AS `lp` ON l.id = lp.listing_id
            LEFT JOIN `{$m2eproEbayListingProduct}` AS `elp` ON lp.id = elp.listing_product_id
        GROUP BY listing_id";

        $collection->getSelect()->joinLeft(
            array('t' => new Zend_Db_Expr('('.$sql.')')),
            'main_table.id=t.listing_id',
            array(
                'products_total_count'    => 'products_total_count',
                'products_active_count'   => 'products_active_count',
                'products_inactive_count' => 'products_inactive_count',
                'items_sold_count'        => 'items_sold_count'
            )
        );

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    //########################################

    protected function _prepareMassaction()
    {
        // Set massaction identifiers
        // ---------------------------------------
        $this->setMassactionIdField('main_table.id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        // ---------------------------------------

        // Set clear log action
        // ---------------------------------------
        $this->getMassactionBlock()->addItem(
            'clear_logs', array(
             'label'    => Mage::helper('M2ePro')->__('Clear Log(s)'),
            'url'      => $this->getUrl(
                '*/adminhtml_listing/clearLog', array(
                'back' => Mage::helper('M2ePro')->makeBackUrlParam(
                    '*/adminhtml_ebay_listing/index', array(
                     'tab' => Ess_M2ePro_Block_Adminhtml_Ebay_ManageListings::TAB_ID_LISTING
                    )
                )
                )
            ),
             'confirm'  => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );
        // ---------------------------------------

        // Set remove listings action
        // ---------------------------------------
        $this->getMassactionBlock()->addItem(
            'delete_listings', array(
             'label'    => Mage::helper('M2ePro')->__('Delete Listing(s)'),
             'url'      => $this->getUrl('*/adminhtml_ebay_listing/delete'),
             'confirm'  => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );
        // ---------------------------------------

        return parent::_prepareMassaction();
    }

    //########################################

    protected function setColumns()
    {
        $this->addColumn(
            'items_sold_count', array(
                'header'         => Mage::helper('M2ePro')->__('Sold QTY'),
                'align'          => 'right',
                'width'          => '100px',
                'type'           => 'number',
                'index'          => 'items_sold_count',
                'filter_index'   => 't.items_sold_count',
                'frame_callback' => array($this, 'callbackColumnProductsCount')
            )
        );

        return $this;
    }

    protected function getColumnActionsItems()
    {
        $helper  = Mage::helper('M2ePro');
        $backUrl = $helper->makeBackUrlParam(
            '*/adminhtml_ebay_listing/index', array(
            'tab' => Ess_M2ePro_Block_Adminhtml_Ebay_ManageListings::TAB_ID_LISTING
            )
        );

        $actions = array(
            'manageProducts' => array(
                'caption' => $helper->__('Manage'),
                'group'   => 'products_actions',
                'field'   => 'id',
                'url'     => array(
                    'base'   => '*/adminhtml_ebay_listing/view',
                    'params' => array('id' => $this->getId(), 'back' => $backUrl)
                )
            ),

            'addProductsSourceProducts' => array(
                'caption'        => $helper->__('Add From Products List'),
                'group'          => 'products_actions',
                'field'          => 'id',
                'onclick_action' => 'EbayListingGridObj.addProductsSourceProductsAction',
            ),

            'addProductsSourceCategories' => array(
                'caption'        => $helper->__('Add From Categories'),
                'group'          => 'products_actions',
                'field'          => 'id',
                'onclick_action' => 'EbayListingGridObj.addProductsSourceCategoriesAction',
            ),

            'autoActions' => array(
                'caption' => $helper->__('Auto Add/Remove Rules'),
                'group'   => 'products_actions',
                'field'   => 'id',
                'url'     => array(
                    'base'   => '*/adminhtml_ebay_listing/view',
                    'params' => array('id' => $this->getId(), 'auto_actions' => 1)
                )
            ),

            'editTitle' => array(
                'caption'        => $helper->__('Title'),
                'group'          => 'edit_actions',
                'field'          => 'id',
                'onclick_action' => 'EditListingTitleObj.openPopup',
            ),

            'editConfiguration' => array(
                'caption' => $helper->__('Configuration'),
                'group'   => 'edit_actions',
                'field'   => 'id',
                'url'     => array(
                    'base'   => '*/adminhtml_ebay_listing/edit',
                    'params' => array('back' => $backUrl)
                )
            ),

            'editPartsCompatibilityMode' => array(
                'caption'        => $helper->__('Parts Compatibility Mode'),
                'group'          => 'edit_actions',
                'field'          => 'id',
                'onclick_action' => 'EditCompatibilityModeObj.openPopup',
                'action_id'      => self::MASS_ACTION_ID_EDIT_PARTS_COMPATIBILITY
            ),

            'viewLogs' => array(
                'caption' => $helper->__('Logs & Events'),
                'group'   => 'other',
                'field'   => 'listing_id',
                'url'     => array(
                    'base'   => '*/adminhtml_ebay_log/listing',
                    'params' => array('listing_id' => $this->getId())
                )
            ),

            'clearLogs' => array(
                'caption' => $helper->__('Clear Log'),
                'confirm' => $helper->__('Are you sure?'),
                'group'   => 'other',
                'field'   => 'id',
                'url'     => array(
                    'base' => '*/adminhtml_listing/clearLog',
                    'params' => array(
                        'back' => $backUrl
                    )
                )
            ),

            'delete' => array(
                'caption' => $helper->__('Delete Listing'),
                'confirm' => $helper->__('Are you sure?'),
                'group'   => 'other',
                'field'   => 'id',
                'url'     => array(
                    'base'   => '*/adminhtml_ebay_listing/delete',
                    'params' => array('id' => $this->getId())
                )
            ),
        );

        return $actions;
    }

    /**
     * editPartsCompatibilityMode has to be not accessible for not Multi Motors marketplaces
     * @return $this
     */
    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        $this->getColumn('actions')->setData(
            'renderer', 'M2ePro/adminhtml_ebay_listing_grid_column_renderer_action'
        );

        return $result;
    }

    //########################################

    public function callbackColumnTitle($value, $row, $column, $isExport)
    {
        $title = Mage::helper('M2ePro')->escapeHtml($value);
        $compatibilityMode = $row->getData('parts_compatibility_mode');

        $value = <<<HTML
<span id="listing_title_{$row->getId()}">{$title}</span>
<span id="listing_compatibility_mode_{$row->getId()}" style="display: none;">{$compatibilityMode}</span>
HTML;
        /** @var $row Ess_M2ePro_Model_Listing */
        $accountTitle = $row->getData('account_title');
        $marketplaceTitle = $row->getData('marketplace_title');

        $storeModel = Mage::getModel('core/store')->load($row->getStoreId());
        $storeView = $storeModel->getWebsite()->getName();
        if (strtolower($storeView) != 'admin') {
            $storeView .= ' > '.$storeModel->getGroup()->getName();
            $storeView .= ' > '.$storeModel->getName();
        } else {
            $storeView = Mage::helper('M2ePro')->__('Admin (Default Values)');
        }

        $account = Mage::helper('M2ePro')->__('Account');
        $marketplace = Mage::helper('M2ePro')->__('Marketplace');
        $store = Mage::helper('M2ePro')->__('Magento Store View');

        $value .= <<<HTML
<div>
    <span style="font-weight: bold">{$account}</span>: <span style="color: #505050">{$accountTitle}</span><br/>
    <span style="font-weight: bold">{$marketplace}</span>: <span style="color: #505050">{$marketplaceTitle}</span><br/>
    <span style="font-weight: bold">{$store}</span>: <span style="color: #505050">{$storeView}</span>
</div>
HTML;

        return $value;
    }

    //########################################

    public function getGridUrl()
    {
        return $this->getUrl('*/adminhtml_ebay_listing/listingGrid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/adminhtml_ebay_listing/view', array(
            'id' => $row->getId()
            )
        );
    }

    //########################################

    protected function callbackFilterTitle($collection, $column)
    {
        $value = $column->getFilter()->getValue();

        if ($value == null) {
            return;
        }

        $collection->getSelect()->where(
            'main_table.title LIKE ? OR a.title LIKE ? OR m.title LIKE ?',
            '%'.$value.'%'
        );
    }

    //########################################

    protected function _toHtml()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            return parent::_toHtml();
        }

        $urls = Mage::helper('M2ePro')->jsonEncode(
            array_merge(
                Mage::helper('M2ePro')->getControllerActions('adminhtml_ebay_listing'),
                Mage::helper('M2ePro')->getControllerActions('adminhtml_ebay_listing_productAdd'),
                Mage::helper('M2ePro')->getControllerActions('adminhtml_ebay_log'),
                Mage::helper('M2ePro')->getControllerActions('adminhtml_ebay_template'),
                array(
                'adminhtml_listing/saveTitle' => Mage::helper('adminhtml')->getUrl('M2ePro/adminhtml_listing/saveTitle')
                )
            )
        );

        $translations = Mage::helper('M2ePro')->jsonEncode(
            array(
            'Cancel' => Mage::helper('M2ePro')->__('Cancel'),
            'Save' => Mage::helper('M2ePro')->__('Save'),
            'Edit Parts Compatibility Mode' => Mage::helper('M2ePro')->__('Edit Parts Compatibility Mode'),
            'Edit Listing Title' => Mage::helper('M2ePro')->__('Edit Listing Title'),
            )
        );

        $uniqueTitleTxt = Mage::helper('M2ePro')->escapeJs(
            Mage::helper('M2ePro')
            ->__('The specified Title is already used for other Listing. Listing Title must be unique.')
        );

        $constants = Mage::helper('M2ePro')
            ->getClassConstantAsJson('Ess_M2ePro_Helper_Component_Ebay');

        $javascriptsMain = <<<HTML

<script type="text/javascript">

    Event.observe(window, 'load', function() {
        M2ePro.url.add({$urls});
        M2ePro.translator.add({$translations});

        M2ePro.text.title_not_unique_error = '{$uniqueTitleTxt}';

        M2ePro.php.setConstants(
            {$constants},
            'Ess_M2ePro_Helper_Component'
        );

        EbayListingGridObj = new EbayListingGrid('{$this->getId()}');
        EditListingTitleObj = new ListingEditListingTitle('{$this->getId()}');
        EditCompatibilityModeObj = new EditCompatibilityMode('{$this->getId()}');
    });

</script>

HTML;

        return parent::_toHtml().$javascriptsMain;
    }

    //########################################
}
