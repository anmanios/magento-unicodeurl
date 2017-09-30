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
 

class Anmanios_Unicodeurl_Helper_Data extends Mage_Core_Helper_Abstract
{

	const XML_PATH_UNICODEURL_GENERAL_ENABLED = 'anmunicodeurl/general/enabled';
	
	/**
	 * Retrieve module status
	 *
	 * @param int $storeId
	 * @return int
	 */
	public function isEnabled($storeId = null){
		return Mage::getStoreConfig(self::XML_PATH_UNICODEURL_GENERAL_ENABLED, $storeId);
	}
	
	/**
	 * Changes the case of all keys in an array (multibyte)
	 *
	 * @param array $array
	 * @param int $case
	 * @return bool|array
	 */
	public function array_change_key_case_unicode($array, $case = CASE_LOWER){
		if (!is_array($array)){
			return false;
		}
		
		$result = array();
		$case = ($case == CASE_LOWER) ? MB_CASE_LOWER : MB_CASE_UPPER;
		foreach ($array as $key => $value){
			$result[mb_convert_case($key, $case, "UTF-8")] = $value;
		}
		return $result;
	}
	
}
