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

use Lof\Cashier\Api\Data\CashierInterface;

class Cashier extends \Magento\Framework\Model\AbstractModel implements CashierInterface
{
    protected $_eventPrefix = 'lof_pos_cashier';
    /**
     * Cashier Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Lof\Cashier\Model\ResourceModel\Cashier $resource
     * @param \Lof\Cashier\Model\ResourceModel\Cashier\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Lof\Cashier\Model\ResourceModel\Cashier $resource,
        \Lof\Cashier\Model\ResourceModel\Cashier\Collection $resourceCollection,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve cashier model with cashier data
     */
    public function getDataModel()
    {
        $cashierData = $this->getData();
        return $cashierData;
    }

    /**
     * Prepare cashier's statuses.
     * Available event lof_cashier_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get getCashierId
     *
     * @return int
     */
    public function getCashierId()
    {
        return parent::getData(self::CASHIER_ID);
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getData(self::CASHIER_PHONE);
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Get outlet id
     *
     * @return int|null
     */
    public function getOutletId()
    {
        return $this->getData(self::OUTLET_ID);
    }

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set ID
     *
     * @param int $cashier_id
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setCashier($cashier_id)
    {
        return $this->setData(self::CASHIER_ID, $cashier_id);
    }

    /**
     * Set first name
     *
     * @param string $first_name
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setFirstName($first_name)
    {
        return $this->setData(self::FIRST_NAME, $first_name);
    }

    /**
     * Set last name
     *
     * @param string $last_name
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setLastName($last_name)
    {
        return $this->setData(self::LAST_NAME, $last_name);
    }

    /**
     * Set email
     *
     * @param string $email
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setPhone($phone)
    {
        return $this->setData(self::CASHIER_PHONE, $phone);
    }

    /**
     * Set image
     *
     * @param string $image
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set outlet_id
     *
     * @param int $outlet_id
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setOutletId($outlet_id)
    {
        return $this->setData(self::OUTLET_ID, $outlet_id);
    }

    /**
     * Set user_id
     *
     * @param int $user_id
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setUserId($user_id)
    {
        return $this->setData(self::USER_ID, $user_id);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::STATUS, $isActive);
    }
}
