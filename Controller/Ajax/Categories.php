<?php
/**
 * Copyright © 2018 Miply Patryk Makowski. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 * @copyright   Copyright (c) 2016 Patryk Makowski
 * @author      Miply Patryk Makowski <magento@miply.no>
 */

namespace Miply\CategoriesSelectors\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use \Miply\CategoriesSelectors\Block\Dropdown;
use Miply\CategoriesSelectors\Controller\AbstractController;


/**
 * Class Categories
 */
class Categories extends AbstractController
{
    const URL_PARAM_CATEGORY_ID = 'parent_id';
    const URL_PARAM_SORT_ORDER  = 'level';
    
    /**
     * @var DropdownProxy
     */
    private $dropdownBlock;


    /**
     * @param Context       $context
     * @param ResultFactory $resultFactory
     * @param Dropdown      $dropdownBlock
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        Dropdown $dropdownBlock
    ) {
        parent::__construct($context);
        
        $this->dropdownBlock = $dropdownBlock;
        $this->resultFactory = $resultFactory;
    }
    
    /**
     * Ajax
     */
    public function execute()
    {
        try {
            /** @var JsonResult $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);

            $responseCode = null;
            
            if ($this->getRequest()->isAjax()) {
                $parentCategoryId = (int) $this->getRequest()->getParam($this::URL_PARAM_CATEGORY_ID);
                $level            = (int) $this->getRequest()->getParam($this::URL_PARAM_SORT_ORDER);

                if ($parentCategoryId) {
                    $this->dropdownBlock->setParentCategoryId($parentCategoryId);
                    $this->dropdownBlock->setCategoryLevel($level);

                    $resultPage->setJsonData($this->dropdownBlock->toHtml());
                }
                else {
                    $responseCode = 400;
                    throw new LocalizedException(new Phrase('Bad Request: Invalid category ID: ' . $parentCategoryId));
                }
            }
            else {
                $responseCode = 406;
                throw new LocalizedException(new Phrase('Not Acceptable: AJAX request required'));
            }

        } catch (\Throwable $e) {
            // @todo logger
            //Mage::log($e->getMessage(), null, $this::LOG_PATH);

            if (!$responseCode) {
                $responseCode = 500;
            }

            $resultPage->setData($e->getMessage());
        } finally {
            $resultPage->setHttpResponseCode($responseCode ?? 200);
            
            return $resultPage;
        }
    }
}
