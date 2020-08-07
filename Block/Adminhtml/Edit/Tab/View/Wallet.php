<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */
namespace MagePrakash\Wallet\Block\Adminhtml\Edit\Tab\View;
 
use Magento\Customer\Controller\RegistryConstants;
 
/**
 * Adminhtml customer recent orders grid block
 */
class Wallet extends \Magento\Backend\Block\Widget\Grid\Extended
{
    
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;
 
    /**
     * @var \Magento\Sales\Model\Resource\Order\Grid\CollectionFactory
     */
    protected $_collectionFactory;
 
    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Sales\Model\Resource\Order\Grid\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \MagePrakash\Wallet\Model\ResourceModel\WalletHistory\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
         $this->_logger = $logger;
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }
 
    /**
     * Initialize the orders grid.
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('comparedproduct_view_compared_grid');
        $this->setDefaultSort('created_at', 'desc');
        $this->setSortable(false);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }
    /**
     * {@inheritdoc}
     */
   /* protected function _preparePage()
    {
        $this->getCollection()->setPageSize(5)->setCurPage(1);
    }*/
 
    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        
        $collection = $this->_collectionFactory->create()
        ->addFieldToFilter('customer_id', array('eq'=>$this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID)));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    /**
     * {@inheritdoc}
     */
     protected function _prepareColumns()
    {
        $this->addColumn(
            'wallet_history_id',
            ['header' => __('History Id'), 'index' => 'wallet_history_id', 'type' => 'number', 'width' => '100px']
        );

        $this->addColumn(
            'wallet_id',
            [
                'header' => __('Wallet Id'),
                'index' => 'wallet_id',
            ]
        );

         $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'index' => 'action',
                'renderer' => \MagePrakash\Wallet\Block\Adminhtml\Edit\Tab\View\Renderer\Action::class                
            ]
        );

        $this->addColumn(
            'balance_amount',
            [
                'header' => __('Balance Amount'),
                'index' => 'balance_amount',
            ]
        );
        $this->addColumn(
            'balance_difference',
            [
                'header' => __('Balance Difference'),
                'index' => 'balance_difference',
                'renderer' => \MagePrakash\Wallet\Block\Adminhtml\Edit\Tab\View\Renderer\BalanceDifference::class
            ]
        );
        
        $this->addColumn(
            'comment',
            [
                'header' => __('Comment'),
                'index' => 'comment',
            ]
        );

        $this->addColumn(
            'additional_info',
            [
                'header' => __('Additional Info'),
                'index' => 'additional_info',
                'renderer' => \MagePrakash\Wallet\Block\Adminhtml\Edit\Tab\View\Renderer\AdditionalInfo::class
            ]
        );
        $this->addColumn(
            'created_at',
            [
                'header' => __('Created At'),
                'index' => 'created_at',
            ]
        );
        return parent::_prepareColumns();
    }
 
    /**
     * Get headers visibility
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getHeadersVisibility()
    {
        return $this->getCollection()->getSize() >= 0;
    }
    /**
     * {@inheritdoc}
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('catalog/product/edit', ['id' => $row->getProductId()]);
    }
}