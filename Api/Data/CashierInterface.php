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

namespace Lof\Cashier\Api\Data;

/**
 * lof cashier interface.
 * @api
 * @since 100.0.2
 */
interface CashierInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const CASHIER_ID = 'cashier_id';
    /**
     *
     */
    const FIRST_NAME = 'firstname';
    /**
     *
     */
    const LAST_NAME = 'lastname';
    /**
     *
     */
    const EMAIL = 'email';
    /**
     *
     */
    const CASHIER_PHONE = 'cashier_phone';
    /**
     *
     */
    const IMAGE = 'image';
    /**
     *
     */
    const OUTLET_ID = 'outlet_id';
    /**
     *
     */
    const USER_ID = 'user_id';
    /**
     *
     */
    const STATUS = 'status';

    /**#@-*/

    /**
     * Get getCashierId
     *
     * @return int|null
     */
    public function getCashierId();

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName();

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Get image
     *
     * @return string
     */
    public function getImage();

    /**
     * Get outlet id
     *
     * @return int|null
     */
    public function getOutletId();

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set setCashier
     *
     * @param int $id
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setCashier($id);

    /**
     * Set first name
     *
     * @param string $first_name
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setFirstName($first_name);

    /**
     * Set last name
     *
     * @param string $last_name
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setLastName($last_name);

    /**
     * Set email
     *
     * @param string $email
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setEmail($email);

    /**
     * Set phone
     *
     * @param string $phone
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setPhone($phone);

    /**
     * Set image
     *
     * @param string $image
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setImage($image);

    /**
     * Set outlet_id
     *
     * @param int $outlet_id
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setOutletId($outlet_id);

    /**
     * Set user_id
     *
     * @param int $user_id
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setUserId($user_id);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Lof\Cashier\Api\Data\CashierInterface
     */
    public function setIsActive($isActive);
}
