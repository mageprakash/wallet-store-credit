<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */
namespace MagePrakash\Wallet\Block\Adminhtml\Edit\Tab;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Customer\Api\AccountManagementInterface;
use MagePrakash\Wallet\Helper\Data;

/**
 * Customer account form block
 */
class Wallet extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
/**
     * @var string
     */
    protected $_template = 'MagePrakash_Wallet::tab/wallet.phtml';

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $walletRepository;

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    protected $_storeManager;
    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \MagePrakash\Wallet\Api\WalletRepositoryInterface $walletRepository,
         AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Data $walletData,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;        
        $this->priceCurrency = $priceCurrency;        
        $this->walletRepository = $walletRepository;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->walletData = $walletData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Return Tab label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __($this->walletData->getTitle());
    }

    /**
     * Return Tab title
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __($this->walletData->getTitle());
    }
    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->canShowTab();
    }

    /**
     * Initialize the form.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('_wallet');
        $customerId = $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
        $walletFactory = $this->walletRepository->loadByCustomerId($customerId);
     //   $this->_coreRegistry->register('subscriber', $subscriber, true);
        

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Wallet Information')]);

        $fieldset->addField(
                'current_balance',
                'label',
                [
                    'label' => __('Current Balance'),
                    'value' =>  $this->priceCurrency->convertAndFormat(
                                $walletFactory->getCurrentBalance(),
                                false,
                                2
                                ),
                    'bold'  => true
                ]
        );


        $fieldset->addField(
            'add_balace',
            'text',
            [
                'label' => __('Add Balance'),
                'name' => 'add_balace',
                'data-form-part' => 'customer_form',
            ]
        );

        $fieldset->addField(
            'comment',
            'textarea',
            [
                'label' => __('Comment'),
                'name' => 'comment',
                'data-form-part' => 'customer_form',
            ]
        );

       // $this->updateFromSession($form, $customerId);  
        $this->setForm($form);
        return $this;
    }

    /**
     * Update form elements from session data
     *
     * @param \Magento\Framework\Data\Form $form
     * @param int $customerId
     * @return void
     */
    protected function updateFromSession(\Magento\Framework\Data\Form $form, $customerId)
    {
        $data = $this->_backendSession->getCustomerFormData();
        if (!empty($data)) {
            $dataCustomerId = isset($data['customer']['entity_id']) ? $data['customer']['entity_id'] : null;
            if (isset($data['subscription']) && $dataCustomerId == $customerId) {
                $form->getElement('subscription')->setIsChecked($data['subscription']);
            }
        }
    }

    /**
     * Retrieve the date when the subscriber status changed.
     *
     * @return null|string
     */
    public function getStatusChangedDate()
    {
        $subscriber = $this->_coreRegistry->registry('subscriber');
        if ($subscriber->getChangeStatusAt()) {
            return $this->formatDate(
                $subscriber->getChangeStatusAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }

        return null;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
}