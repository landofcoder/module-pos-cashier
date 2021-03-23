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

namespace Lof\Cashier\Block\Adminhtml\Cashier\Edit\Tab;

use Lof\Cashier\Model\CashierUserFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Module\Manager;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

/**
 * Class Main
 * @package Lof\Cashier\Block\Adminhtml\Cashier\Edit\Tab
 */
class Main extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @var CashierUserFactory
     */
    protected $_cashierUserFactory;

    /**
     * @var CollectionFactory
     */
    protected $userCollectionFactory;

    /**
     * @var Manager
     */
    protected $_moduleManager;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Config $wysiwygConfig
     * @param CollectionFactory $userCollectionFactory
     * @param CashierUserFactory $cashierUserFactory
     * @param Manager $moduleManager
     * @param array
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Config $wysiwygConfig,
        CollectionFactory $userCollectionFactory,
        CashierUserFactory $cashierUserFactory,
        Manager $moduleManager,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->_cashierUserFactory = $cashierUserFactory;
        $this->_moduleManager = $moduleManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @param $n
     * @return string
     */
    protected function _getSpaces($n)
    {
        $s = '';
        for ($i = 0; $i < $n; $i++) {
            $s .= '--- ';
        }

        return $s;
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $this->_eventManager->dispatch(
            'lof_check_license',
            ['obj' => $this, 'ex' => 'Lof_Cashier']
        );

        /* @var $model Page */
        $model = $this->_coreRegistry->registry('lof_cashier');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Lof_Cashier::Cashier_update')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId() . time()]);
        if (!$this->getData('is_valid') && !$this->getData('local_valid')) {
            $isElementDisabled = true;
            $wysiwygConfig['enabled'] = $wysiwygConfig['add_variables'] = $wysiwygConfig['add_widgets'] = $wysiwygConfig['add_images'] = 0;
            $wysiwygConfig['plugins'] = [];
        }
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('cashier_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Cashier Information')]);

        if ($model->getId()) {
            $fieldset->addField('cashier_id', 'hidden', ['name' => 'cashier_id']);
        }

        $modelCashierUser = $this->_cashierUserFactory->create();
        $cashierUserCollection = $modelCashierUser->getCollection();
        $user_id = $cashierUserCollection->addFieldToFilter('cashier_id', $model->getId())->getFirstItem()->getUser_id();
        if ($user_id) {
            $model->setData('user_id', $user_id);
        }

        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'note' => 'Allowed image types: jpg, jpeg, png',
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'firstname',
            'text',
            [
                'name' => 'firstname',
                'label' => __('First Name'),
                'title' => __('First Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'lastname',
            'text',
            [
                'name' => 'lastname',
                'label' => __('Last Name'),
                'title' => __('Last Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'cashier_phone',
            'text',
            [
                'name' => 'cashier_phone',
                'label' => __('Phone Number'),
                'title' => __('Phone Number'),
                'disabled' => $isElementDisabled
            ]
        );

        $outlets[] = [
            'label' => __('Default'), 'value' => 0
        ];
//        check exist module Lof_outlet
        if($this->isModuleEnabled('Lof_Outlet')){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $outletCollection = $objectManager->create('\Lof\Outlet\Model\ResourceModel\Outlet\Collection');
            foreach ($outletCollection as $_outlet) {
                $data_outlet = [
                    'label' => $_outlet->getOutlet_name(),
                    'value' => $_outlet->getOutlet_id(),
                    'id' => $_outlet->getOutlet_id()
                ];
                $outlets[] = $data_outlet;
            }
        }
        $fieldset->addField(
            'outlet_id',
            'select',
            [
                'name' => 'outlet_id',
                'label' => __('Outlet'),
                'title' => __('Outlet'),
                'required' => true,
                'values' => $outlets,
                'disabled' => $isElementDisabled
            ]
        );


        $adminUsers[] = [
            'label' => __('Please select'), 'value' => 0
        ];
        foreach ($this->userCollectionFactory->create() as $user) {
            $adminUsers[] = [
                'value' => $user->getId(),
                'label' => $user->getUsername()
            ];
        }

        $fieldset->addField(
            'user_id',
            'select',
            [
                'name' => 'user_id',
                'label' => __('Link To Admin User'),
                'title' => __('Link To Admin User'),
                'values' => $adminUsers,
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Cashier Status'),
                'name' => 'status',
                'options' => $model->getAvailableStatuses(),
                'disabled' => $isElementDisabled
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $this->_eventManager->dispatch('adminhtml_cashier_cashier_edit_tab_main_prepare_form', ['form' => $form]);
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function isModuleEnabled($moduleName)
    {
        return $this->_moduleManager->isEnabled($moduleName);
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Cashier Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Cashier Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
