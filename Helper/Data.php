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

/**
 * Class Data
 */
class Data 
{
    const JSON_KEY_ORDER_PREFIX = 'mpcs';

    const CATEGORY_ID_NOT_SELECTED = '-1';

    /**
     * Returns string used to deal with sorting of records in JSON objects
     *
     * @return string
     */
    public function getJsonKeyOrderPrefix()
    {
        return $this::JSON_KEY_ORDER_PREFIX;
    }

    /**
     * Returns string used as key for unselected value in drop-downs
     *
     * @return string
     */
    public function getKeyForUnselected()
    {
        return $this::CATEGORY_ID_NOT_SELECTED;
    }
}