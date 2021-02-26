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

class Cashier extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $userId = (array)$object->getUserId();
        $cashierId = (array)$object->getCashierId();
        $table = $this->getTable('lof_pos_cashier_user');
        $delete = array_diff($userId, $cashierId);
        if ($delete) {
            $where = ['cashier_id = ?' => (int)$object->getCashierId()];
            $this->getConnection()->delete($table, $where);
        }
        if ($cashierId && $userId) {
            $data = ['cashier_id' => (int)$object->getCashierId(), 'user_id' => (int)$object->getUserId()];
            $this->getConnection()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['cashier_id = ?' => (int)$object->getCashierId()];

        $this->getConnection()->delete($this->getTable('lof_pos_cashier_user'), $condition);

        return parent::_beforeDelete($object);
    }
}
