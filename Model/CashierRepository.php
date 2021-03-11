<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_Cashier
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\Cashier\Model;

use Lof\Cashier\Api\CashierRepositoryInterface;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreManagementInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\User\Model\UserFactory;

/**
 * Class CashierRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CashierRepository implements CashierRepositoryInterface
{
    /**
     * @var CashierFactory
     */
    protected $cashierFactory;

    /**
     * @var UserContextInterface
     */
    protected $_userContext;

    /**
     * @var $userFactory
     */
    protected $userFactory;

    /**
     * @var $userFactory
     */
    protected $cashierUserFactory;
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @param CashierFactory $cashierFactory
     * @param UserContextInterface $userContext
     * @param UserFactory $userFactory
     * @param CashierUserFactory $cashierUserFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CashierFactory $cashierFactory,
        UserContextInterface $userContext,
        UserFactory $userFactory,
        CashierUserFactory $cashierUserFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->cashierFactory = $cashierFactory;
        $this->_userContext = $userContext;
        $this->userFactory = $userFactory;
        $this->cashierUserFactory = $cashierUserFactory;
        $this->_storeManager = $storeManager;
    }


    /**
     * @return array|mixed|null
     * @throws NoSuchEntityException
     */
    public function getCashierInformation()
    {
        $user_id = $this->_userContext->getUserId();
        $cashierUserModel = $this->cashierUserFactory->create();
        $cashierModel = $this->cashierFactory->create();
        $cashier_id = $cashierUserModel->getCollection()->addFieldToFilter('user_id', $user_id)->getFirstItem()->getCashierId();
        $cashierModel->load($cashier_id);
        $data = $cashierModel->getData();
        $data['image'] = $this->_storeManager->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ) . $cashierModel->getImage();
        return $data;
    }
}
