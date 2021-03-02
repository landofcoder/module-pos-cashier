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

use Lof\Cashier\Model\CashierFactory;
use Lof\Cashier\Model\CashierUserFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\Read;

/**
 * Class Save
 * @package Lof\Cashier\Controller\Adminhtml\Cashier
 */
class Save extends Action
{

    /**
     * @var Filesystem
     */
    protected $_fileSystem;

    /**
     * @var Js
     */
    protected $jsHelper;

    /**
     * User model factory
     *
     * @var CashierFactory
     */
    protected $_cashierFactory;

    /**
     * @var CashierUserFactory
     */
    protected $_cashierUserFactory;

    /**
     * @param Context $context
     * @param Filesystem $filesystem
     * @param Js $jsHelper
     * @param CashierFactory $cashierFactory
     * @param CashierUserFactory $cashierUserFactory
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        Js $jsHelper,
        CashierFactory $cashierFactory,
        CashierUserFactory $cashierUserFactory
    )
    {
        $this->_fileSystem = $filesystem;
        $this->jsHelper = $jsHelper;
        $this->_cashierFactory = $cashierFactory;
        $this->_cashierUserFactory = $cashierUserFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Lof_Cashier::Cashier_save');
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();
        if (array_key_exists('form_key', $data)) {
            unset($data['form_key']);
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data['username'] = $data['email'];
            $model = $this->_cashierFactory->create();
            $id = $this->getRequest()->getParam('cashier_id');
            if ($id) {
                $model->load($id);
            }

            $modelCashierUser = $this->_cashierUserFactory->create();
            $cashierUserCollection = $modelCashierUser->getCollection();
            $dataCashierUser = $cashierUserCollection->addFieldToFilter('user_id', $data['user_id']);
            if ($dataCashierUser->getFirstItem()->getCashier_id() != null && $data['user_id'] != 0) {
                if ($dataCashierUser->getFirstItem()->getCashier_id() != $id) {
                    $this->messageManager->addError(__('This user account has been assigned to another cashier.'));
                    return $resultRedirect->setPath('*/*/*');
                }
            }

            /** @var Read $mediaDirectory */
            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(DirectoryList::MEDIA);
            $mediaFolder = 'lof/cashier/';
            $path = $mediaDirectory->getAbsolutePath($mediaFolder);

            // Delete, Upload Image
            $imagePath = $mediaDirectory->getAbsolutePath($model->getImage());
            if (isset($data['image']['delete']) && file_exists($imagePath)) {
                unlink($imagePath);
                $data['image'] = '';
            } else {
                if (isset($data['image']) && is_array($data['image'])) {
                    unset($data['image']);
                }
                if ($image = $this->uploadImage('image')) {

                    $data['image'] = $image;
                }
            }
            try {
                $model->setData($data);
                $model->save();

                $this->messageManager->addSuccess(__('You saved this cashier.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['cashier_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the cashier.'));
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['cashier_id' => $this->getRequest()->getParam('cashier_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param string $fieldId
     * @return \Magento\Framework\Controller\Result\Redirect|string|void
     */
    public function uploadImage($fieldId = 'image')
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (isset($_FILES[$fieldId]) && $_FILES[$fieldId]['name'] != '') {
            $uploader = $this->_objectManager->create(
                'Magento\Framework\File\Uploader',
                ['fileId' => $fieldId]
            );

            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(DirectoryList::MEDIA);
            $mediaFolder = 'lof/cashier/';
            try {
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $result = $uploader->save($mediaDirectory->getAbsolutePath($mediaFolder)
                );
                return $mediaFolder . $result['name'];
            } catch (\Exception $e) {
                $this->_logger->critical($e);
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['cashier_id' => $this->getRequest()->getParam('cashier_id')]);
            }
        }
        return;
    }
}
