<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */
namespace MagePrakash\Wallet\Controller\Adminhtml\Index;
 
class Wallet extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer compare grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */

    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $this->_request = $request;
        if (!$this->_objectManager->get(\MagePrakash\Wallet\Helper\Data::class)->isEnabled()) {
            if ($request->getActionName() != 'noroute') {
                $this->_forward('noroute');
                return $this->getResponse();
            }
        }
        return parent::dispatch($request);
    }

    public function execute()
    {
        $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }
}