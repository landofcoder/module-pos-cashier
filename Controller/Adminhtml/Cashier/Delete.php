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

namespace Lof\Cashier\Controller\Adminhtml\Cashier;

use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    protected $cashierFactory;
    protected $userFactory;

    public function __construct(Action\Context $context,
                                \Lof\Cashier\Model\CashierFactory $cashierFactory,
                                \Magento\User\Model\UserFactory $userFactory)
    {
        parent::__construct($context);
        $this->cashierFactory = $cashierFactory;
        $this->userFactory = $userFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Lof_Cashier::Cashier_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('cashier_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // init model and delete
                $model = $this->cashierFactory->create();
                $model_user = $this->userFactory->create();

                $model->load($id);
                $user_id = $model->getUser_id();
                $model_user->load($user_id);

                if ($model && $model_user) {
                    $model->delete();
                    // display success message
                    $this->messageManager->addSuccess(__('The cashier has been deleted.'));
                    // go to grid
                    return $resultRedirect->setPath('*/*/');
                }
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['cashier_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a cashier to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
