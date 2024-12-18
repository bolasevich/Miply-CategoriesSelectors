<?php
/**
 * Copyright © 2018 Miply Patryk Makowski. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 * @copyright   Copyright (c) 2016 Patryk Makowski
 * @author      Miply Patryk Makowski <magento@miply.no>
 */
 
namespace Miply\CategoriesSelectors\Helper;

use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection as FrameworkCollection;

/**
 * Class Category
 */
class Category
{    
    const XPATH_CONFIG_CATEGORY_SORT_ORDER = 'miply_categories_selector/category/sort_order/level_';
    
    /**
     * @var CategoryCollection
     */
    private $categoryCollection;
    
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Category constructor.
     *
     * @param CategoryCollection   $categoryCollection
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CategoryCollection $categoryCollection,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->categoryCollection = $categoryCollection;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * @param int $parentCategoryId
     * @param int $categoryLevel
     *
     * @return CategoryCollection
     */
    public function getCategoryChildren($parentCategoryId, $categoryLevel = 0)
    {
        $this->categoryCollection->resetData();

        $this->categoryCollection->addIsActiveFilter();
        $this->categoryCollection->addNameToResult();
        $this->categoryCollection->addFieldToFilter('parent_id', ['eq' => $parentCategoryId]);
        $this->categoryCollection->addAttributeToSelect('name');
        $this->categoryCollection->setOrder('name', $this->getCategorySortOrder($categoryLevel));

        return $this->categoryCollection;
    }

    /**
     * @param $categoryLevel
     *
     * @return string
     */
    public function getCategorySortOrder($categoryLevel)
    {
        $configValue = $this->scopeConfig->getValue($this::XPATH_CONFIG_CATEGORY_SORT_ORDER . $categoryLevel);
        
        return is_null($configValue) ? FrameworkCollection::SORT_ORDER_ASC : strtoupper($configValue);
    }
}