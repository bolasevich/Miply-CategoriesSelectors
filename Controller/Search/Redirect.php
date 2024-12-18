<?php
/**
 * Copyright © 2018 Miply Patryk Makowski. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 * @copyright   Copyright (c) 2016 Patryk Makowski
 * @author      Miply Patryk Makowski <magento@miply.no>
 */

namespace Miply\CategoriesSelectors\Controller\Search;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\ResultFactory;
use Miply\CategoriesSelectors\Controller\AbstractController;
use Miply\CategoriesSelectors\Helper\Data as DataHelper;

/**
 * Class Redirect
 */
class Redirect extends AbstractController
{
    const POST_PARAM_CATEGORY = 'category';
    
    /**
     * @var DataHelper
     */
    private $dataHelper;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;


    /**
     * @param Context                     $context
     * @param ResultFactory               $resultFactory
     * @param DataHelper                  $dataHelper
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        DataHelper $dataHelper,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context);
        
        $this->dataHelper = $dataHelper;
        $this->resultFactory = $resultFactory;
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * Ajax
     */
    public function execute()
    {        
        try{
            /** @var RedirectResult $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            $responseCode = null;
            
            if ($this->getRequest()->isPost()) {
                $categoriesIds = $this->getRequest()->getParam($this::POST_PARAM_CATEGORY);
                
                array_walk($categoriesIds, [$this, 'validateCategoryId']);
                
                $levelsCount        = count($categoriesIds);
                $unselectedValueKey = $this->dataHelper->getKeyForUnselected();
                
                if (array_key_exists($levelsCount - 2, $categoriesIds) && ($categoriesIds[$levelsCount - 2] != $unselectedValueKey)) {
                    /** @var CategoryInterface $category */
                    $category = $this->categoryRepository->get((int) $categoriesIds[$levelsCount - 2]);
                    /** @var string $url */
                    $url = $category->getUrl();
    
                    if (array_key_exists($levelsCount - 1, $categoriesIds)  && ($categoriesIds[$levelsCount - 1] != $unselectedValueKey)) {
                        $url .= '?cat=' . $categoriesIds[$levelsCount - 1];
                    }
                    
                    $resultPage->setUrl($url);
                }
                else {
                    throw new LocalizedException(new Phrase('An error occurred: incorrect parameters. Please try again.'));
                }
            }
            else {
                throw new LocalizedException(new Phrase('An error occurred: incorrect request type. Please try again.'));
            }
        }
        catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, 'An unknown error occurred');
            
            $resultPage->setRefererUrl();
        }        

        return $resultPage;
    }

    /**
     * @param int $item
     * @param int $key
     *
     * @return bool
     * @throws LocalizedException
     */
    public function validateCategoryId($item, $key)
    {
        if ((ctype_digit((string) $item) || $item == '-1') && ctype_digit((string) $key)) {
            
            return true;
        }
        
        throw new LocalizedException(new Phrase('Incorrect category ID: ' . $item));
    }
}
