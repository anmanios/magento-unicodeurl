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
 

class Anmanios_Unicodeurl_Model_Core_Resource_Url_Rewrite extends Mage_Core_Model_Resource_Url_Rewrite
{

    /**
     * Load rewrite information for request
     * If $path is array - we must load all possible records and choose one matching earlier record in array
     *
     * @param   Mage_Core_Model_Url_Rewrite $object
     * @param   array|string $path
     * @return  Mage_Core_Model_Resource_Url_Rewrite
     */
    public function loadByRequestPath(Mage_Core_Model_Url_Rewrite $object, $path)
    {
		if (!Mage::helper('anmunicodeurl')->isEnabled()){
			return parent::loadByRequestPath($object, $path);
		}
		
        if (!is_array($path)) {
            $path = array(urldecode(mb_strtolower($path)));
        }

        $pathBind = array();
        foreach ($path as $key => $url) {
            $pathBind['path' . $key] = urldecode(mb_strtolower($url));
			$path[$key] = urldecode(mb_strtolower($url));
        }
        // Form select
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getMainTable())
            ->where('request_path IN (:' . implode(', :', array_flip($pathBind)) . ')')
            ->where('store_id IN(?)', array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId()));

        $items = $adapter->fetchAll($select, $pathBind);

        // Go through all found records and choose one with lowest penalty - earlier path in array, concrete store
        $mapPenalty = Mage::helper('anmunicodeurl')->array_change_key_case_unicode(array_flip(array_values($path))); // we got mapping array(path => index), lower index - better
        $currentPenalty = null;
        $foundItem = null;
        foreach ($items as $item) {
            if (!array_key_exists($item['request_path'], $mapPenalty)) {
                continue;
            }
            $penalty = $mapPenalty[$item['request_path']] << 1 + ($item['store_id'] ? 0 : 1);
            if (!$foundItem || $currentPenalty > $penalty) {
                $foundItem = $item;
                $currentPenalty = $penalty;
                if (!$currentPenalty) {
                    break; // Found best matching item with zero penalty, no reason to continue
                }
            }
        }

        // Set data and finish loading
        if ($foundItem) {
            $object->setData($foundItem);
        }

        // Finish
        $this->unserializeFields($object);
        $this->_afterLoad($object);

        return $this;
    }
	
}
