<?php
/**
 * Copyright © 2018 Miply Patryk Makowski. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 * @copyright   Copyright (c) 2016 Patryk Makowski
 * @author      Miply Patryk Makowski <magento@miply.no>
 */

namespace Miply\CategoriesSelectors\Controller;

use Magento\Framework\App\Action\Action;

/**
 * Class Miply_CategoriesSelector_SearchController
 */
abstract class AbstractController extends Action
{
    const LOG_PATH = 'miply/categories-selector.log';
    
    /**
     * Check is allowed access to action
     * 
     * @todo remove?
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
