<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Block;

use MagePrakash\Wallet\Model\ResourceModel\WalletHistory\CollectionFactory;
use MagePrakash\Wallet\Model\WalletRepository;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Dashboard extends Template
{
    /**
     * @var string
     */
    protected $_template = 'dashboard.phtml';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var walletRepository
     */
    private $walletRepository;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var customerSession
     */
    private $customerSession;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        Registry $registry,
        WalletRepository $walletRepository,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Customer\Model\Session $customerSession,        
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->collectionFactory = $collectionFactory;
        $this->registry = $registry;
        $this->walletRepository = $walletRepository;
        $this->priceCurrency = $priceCurrency;
        $this->customerSession = $customerSession;        
    }

    public function getCustomerBalance()
    {
        return $this->priceCurrency->convertAndFormat(
            $this->walletRepository->loadByCustomerId($this->getCustomerId())->getCurrentBalance(),
            false,
            2,
            null,
            $this->_storeManager->getStore()->getCurrentCurrency()
        );
    }

    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }

    /**
     * Return Pager html for all pages
     *
     * @return string
     */
    public function getPagerHtml()
    {
        $pagerBlock = $this->getChildBlock('mageprakash_wallet_pager');

        if ($pagerBlock instanceof \Magento\Framework\DataObject) {

            $pagerBlock->setUseContainer(
                false
            )->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setLimit(
                $this->getLimit()
            )->setCollection(
                $this->getCollection()
            );

            return $pagerBlock->toHtml();
        }

        return '';
    }

    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create();
            $this->collection->setDashboarFilters($this->getCustomerId());
            $this->collection->setOrder('main_table.' . 'wallet_history_id', 'DESC');
            if ($this->getLimit()) {
                $curPage = (int)$this->getRequest()->getParam('p', 1);
                $this->collection->setCurPage($curPage);
                $this->collection->setPageSize($this->getLimit());
            }
        }
        return $this->collection;
    }

    public function getLimit()
    {
        return (int)$this->getRequest()->getParam('limit', 10);
    }
}
