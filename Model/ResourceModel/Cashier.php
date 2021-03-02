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

namespace Lof\Cashier\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Cashier
 * @package Lof\Cashier\Model\ResourceModel
 */
class Cashier extends AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('lof_pos_cashier', 'cashier_id');
    }

    /**
     * Perform operations after object save
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        $userId = (array)$object->getUserId();
        $cashierId = (array)$object->getCashierId();
        $table = $this->getTable('lof_pos_cashier_user');
        $where1 = ['cashier_id = ?' => (int)$object->getCashierId()];
        if (!$userId) {
            $this->getConnection()->delete($table, $where1);
        }
        else if ($cashierId) {
            $data = ['cashier_id' => (int)$object->getCashierId(), 'user_id' => (int)$object->getUserId()];
            $where2 = 'cashier_id = '.(int)$object->getCashierId();
            $select = $this->getConnection()->select()->from($table, 'user_id')->where($where2);
            $currentCashierUser = $this->getConnection()->fetchAll($select);
            if (count($currentCashierUser)) {
                $this->getConnection()->update($table, $data, $where1);
            } else {
                $this->getConnection()->insert($table, $data);
            }
        }
        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel $object
     * @return Cashier
     */
    protected function _beforeDelete(AbstractModel $object)
    {
        $condition = ['cashier_id = ?' => (int)$object->getCashierId()];

        $this->getConnection()->delete($this->getTable('lof_pos_cashier_user'), $condition);

        return parent::_beforeDelete($object);
    }
}
