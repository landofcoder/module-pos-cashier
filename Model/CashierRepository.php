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
     * @var \Magento\Authorization\Model\UserContextInterface
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
     * @param CashierFactory $cashierFactory
     * @param \Magento\Authorization\Model\UserContextInterface $userContext
     * @param \Magento\User\Model\UserFactory $userFactory
     * @param \Lof\Cashier\Model\CashierUserFactory $cashierUserFactory
     */
    public function __construct(
        CashierFactory $cashierFactory,
        \Magento\Authorization\Model\UserContextInterface $userContext,
        \Magento\User\Model\UserFactory $userFactory,
        \Lof\Cashier\Model\CashierUserFactory $cashierUserFactory
    )
    {
        $this->cashierFactory = $cashierFactory;
        $this->_userContext = $userContext;
        $this->userFactory = $userFactory;
        $this->cashierUserFactory = $cashierUserFactory;
    }

    /**
     * get Cashier Information
     *
     * @return Cashier
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCashierInformation()
    {
        $user_id = $this->_userContext->getUserId();
        $cashierUserModel = $this->cashierUserFactory->create();
        $cashierModel = $this->cashierFactory->create();
        $cashier_id = $cashierUserModel->getCollection()->addFieldToFilter('user_id', $user_id)->getFirstItem()->getCashier_id();
        $dataCashier = $cashierModel->getCollection()
            ->addFieldToFilter('cashier_id', ['eq' => $cashier_id])->getFirstItem();
        return $dataCashier;
    }
}
