<?php
/**
 * Copyright © LandOfCoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\Cashier\Model\Resolver;

use Lof\Cashier\Api\CashierRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;


/**
 * Class Cashier
 * @package Lof\Cashier\Model\Resolver
 */
class Cashier implements ResolverInterface
{

    /**
     * @var CashierRepositoryInterface
     */
    private $cashierManagement;

    /**
     * ProductByBarcode constructor.
     * @param CashierRepositoryInterface $cashierManagement
     */
    public function __construct(
        CashierRepositoryInterface $cashierManagement
    ) {
        $this->cashierManagement = $cashierManagement;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        /** @var ContextInterface $context */
        if (!$context->getUserId()) {
            throw new GraphQlAuthorizationException(__('The current user isn\'t authorized.'));
        }
        return $this->cashierManagement->getCashierInformation();
    }
}

