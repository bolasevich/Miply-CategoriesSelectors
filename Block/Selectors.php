<?php
/**
 * Copyright © 2018 Miply Patryk Makowski. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 * @copyright   Copyright (c) 2016 Patryk Makowski
 * @author      Miply Patryk Makowski <magento@miply.no>
 */


namespace Miply\CategoriesSelectors\Block;

use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Store\Model\StoreManagerInterface;
use Miply\CategoriesSelectors\Helper\Category as CategoryHelper;
use Miply\CategoriesSelectors\Helper\Data as DataHelper;

/**
 * Class Selectors
 */
//class Selectors extends AbstractBlock
class Selectors extends \Magento\Framework\View\Element\Template
{
    const DEFAULT_CACHE_LIFETIME  = 12000;
    
    const CATEGORIES_LEVELS_COUNT = 3;
    
    /** @var int */
    protected $rootCategoryId = null;
    /**
     * @var int
     */
    private $categoryLevel;

    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * @var DataHelper
     */
    private $dataHelper;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor
     *
     * @param TemplateContext       $context
     * @param CategoryHelper        $categoryHelper
     * @param DataHelper            $dataHelper
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param array                 $data
     */
    public function __construct(
        TemplateContext $context,
        CategoryHelper $categoryHelper,
        DataHelper $dataHelper,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->categoryHelper = $categoryHelper;
        $this->dataHelper = $dataHelper;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }
    
    /**
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();
        
        $info['miply_categories_selector_root_category_id'] = $this->getRootCategoryId();

        return $info;
    }

    /**
     * Returns cache lifetime in seconds
     *
     * @return int Longevity of cache key in seconds
     */
    public function getCacheLifetime()
    {
        return self::DEFAULT_CACHE_LIFETIME;
    }

    /**
     * @param int $categoryId
     *
     * @return CategoryCollection
     */
    public function getCategoryChildren($categoryId = null)
    {
        if (is_null($categoryId)) {
            $categoryId = $this->getRootCategoryId();
        }
        
        return $this->categoryHelper->getCategoryChildren($categoryId);
    }

    /**
     * @return int
     */
    public function getCategoriesLevelsCount()
    {
        $count = $this->scopeConfig->getValue('miply_categoriesselector/categories/levels');
        
        if (ctype_digit($count)) {
            
            return (int) $count;
        }
        
        return (int) $this::CATEGORIES_LEVELS_COUNT;
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->getUrl('miply_categoriesselector/search/redirect');
    }

    /**
     * @param int $level
     *
     * @return string
     */
    public function getDropDownDefaultText($level = 0)
    {
        return $this->scopeConfig->getValue('miply_categoriesselector/drop_down/default_text_' . $level);
    }

    /**
     * @return int
     */
    protected function getRootCategoryId()
    {
        if (is_null($this->rootCategoryId)) {
            $this->rootCategoryId = (int) $this->storeManager->getStore()->getRootCategoryId();
        }

        return $this->rootCategoryId;
    }
    
    /**
     * Returns string used to deal with sorting of records in JSON objects
     *
     * @return string
     */
    public function getJsonKeyOrderPrefix()
    {
        return $this->dataHelper->getJsonKeyOrderPrefix();
    }
}
