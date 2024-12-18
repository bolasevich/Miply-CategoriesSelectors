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
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;
use Miply\CategoriesSelectors\Helper\Category as CategoryHelper;
use Miply\CategoriesSelectors\Helper\Data as DataHelper;


/**
 * Class Dropdown
 *
 * @package Miply\CategoriesSelectors
 */
class Dropdown extends AbstractBlock
{
    /**
     * @var int
     */
    private $categoryLevel;
    
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;
    
    /**
     * @var EncoderInterface
     */
    private $jsonEncoder;
    
    /**
     * @var DataHelper
     */
    private $dataHelper;

    /**
     * Constructor
     *
     * @param Context          $context
     * @param CategoryHelper   $categoryHelper
     * @param DataHelper       $dataHelper
     * @param EncoderInterface $jsonEncoder
     * @param array            $data
     */
    public function __construct(
        Context $context,
        CategoryHelper $categoryHelper,
        DataHelper $dataHelper,
        EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        
        $this->categoryHelper = $categoryHelper;
        $this->jsonEncoder = $jsonEncoder;
        $this->dataHelper = $dataHelper;
    }
    
    
    const DEFAULT_CACHE_LIFETIME = 12000;
    
    /** @var int */
    protected $parentCategoryId = null;

    /**
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();
        
        $info['miply_categories_dropdown_parent_category_id'] = $this->getParentCategoryId();

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
     * @param $parentCategoryId
     */
    public function setParentCategoryId($parentCategoryId)
    {
        $this->parentCategoryId = $parentCategoryId;
    }

    /**
     * @return int
     */
    protected function getParentCategoryId()
    {
        return (int) $this->parentCategoryId;
    }

    /**
     * @param $categoryLevel
     */
    public function setCategoryLevel($categoryLevel)
    {
        $this->categoryLevel = $categoryLevel;
    }

    /**
     * @return int
     */
    protected function getCategoryLevel()
    {
        return (int) $this->categoryLevel;
    }

    /**
     * @param int $categoryId
     * @param int $categoryLevel
     *
     * @return CategoryCollection
     */
    public function getCategoryChildren($categoryId, $categoryLevel = 0)
    {
        return $this->categoryHelper->getCategoryChildren($categoryId, $categoryLevel);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {        
        if (($this->getParentCategoryId() > 0) && ($this->getCategoryLevel() > -1)) {
            /** @var CategoryCollection $categories */
            $categories = $this->getCategoryChildren($this->getParentCategoryId(), $this->getCategoryLevel());
            
            /* sort JSON keys of type integer alphabetically */
            $keyPrefix = $this->getJsonKeyOrderPrefix();
            
            $categoriesNames = [];
            foreach ($categories as $category) {
                $categoriesNames[$keyPrefix . $category->getId()] = $category->getName();
            }
            
            return $this->jsonEncoder->encode($categoriesNames);
        }
        
        return $this->jsonEncoder->encode([]);
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
