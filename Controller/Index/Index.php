<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Controller\Index;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;


class Index extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var Session
     */
    private $walletData;    

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MagePrakash\Wallet\Helper\Data $walletData
        )
    {
        $this->walletData = $walletData;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Authenticate customer
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->_objectManager->get(\Magento\Customer\Model\Session::class)->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
        }
        return parent::dispatch($request);
    }


    /**
     * @return $this|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->_objectManager->get(\MagePrakash\Wallet\Helper\Data::class)->isEnabled()) {
            $this->_redirect('customer/account/');
            return;
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($this->walletData->getTitle());
        return $resultPage;
    }
}
