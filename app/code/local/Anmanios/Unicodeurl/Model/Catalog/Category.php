<?php
/**
 * Anmanios
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Anmanios
 * @package     Anmanios_Unicodeurl
 * @copyright   Copyright (c) 2017
 * @author		Manios Anastasios <an.manios@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 

class Anmanios_Unicodeurl_Model_Catalog_Category extends Mage_Catalog_Model_Category
{

	/**
     * Format URL key from name or defined key
     *
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
		if (!Mage::helper('anmunicodeurl')->isEnabled()){
			return parent::formatUrlKey($str);
		}
		
        $str = Mage::helper('catalog/product_url')->format($str);
        $urlKey = preg_replace('#[ ]+#i', '-', $str);
        $urlKey = mb_strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }
	
}
