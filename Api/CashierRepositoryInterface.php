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

namespace Lof\Cashier\Api;

/**
 * Lof cashier CRUD interface.
 * @api
 * @since 100.0.2
 */
interface CashierRepositoryInterface
{
    /**
     * Retrieve cashier.
     *
     * @return \Lof\Cashier\Api\Data\CashierInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCashierInformation();
}
