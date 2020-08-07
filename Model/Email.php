<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Model;

use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use MagePrakash\Wallet\Helper\Data;
use Psr\Log\LoggerInterface;

class Email
{
    /**SenderResolver
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SenderResolverInterface
     */
    private $senderResolver;

    /**
     * Email constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder      $transportBuilder
     * @param LoggerInterface       $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        LoggerInterface $logger,
        SenderResolverInterface $senderResolver,
        Data $dataWallet
    ) {
        $this->storeManager                 = $storeManager;
        $this->transportBuilder             = $transportBuilder;
        $this->logger                       = $logger;
        $this->senderResolver               = $senderResolver;
        $this->dataWallet                   = $dataWallet;
    }

    /**
     * Send email helper
     * emailTo and sendFrom can be array with keys email and name.
     * Otherwise string with key to Store Email address.
     *
     * @param string|array $emailTo
     * @param array  $vars
     * @param string $area
     * @param string|array $sendFrom
     * @param int $storeId
     */
    public function sendEmail(
        $emailTo = '',
        $vars = [],
        $area = \Magento\Framework\App\Area::AREA_FRONTEND,
        $sendFrom = '',
        $replyTo = '',
        $storeId = 0
    ) {
        if($this->dataWallet->isEmailEnabled() == 1)
        {
            try {
                $store = $this->storeManager->getStore($storeId);
                $data =  array_merge(
                    [
                        'website_name'  => $store->getWebsite()->getName(),
                        'group_name'    => $store->getGroup()->getName(),
                        'store_name'    => $store->getName(),
                    ],
                    $vars
                );
                $sender = [
                            'name'  => $this->dataWallet->getStorename(),
                            'email' => $this->dataWallet->getStoreEmail(),
                          ];
               
                $transport = $this->transportBuilder->setTemplateIdentifier($this->dataWallet->getEmailTemplate()
                )->setTemplateOptions(
                    ['area' => $area, 'store' => $store->getId()]
                )->setTemplateVars(
                    $data
                )->setFrom($sender)
                    ->addTo(
                        $emailTo['email'],
                        $emailTo['name']
                    )->getTransport();

                $transport->sendMessage();
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }
    }
}
