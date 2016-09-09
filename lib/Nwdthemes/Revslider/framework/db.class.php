<?php

// Overrides original db.class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/framework/db.class.php';

class UniteDBRev extends UniteDBRevOriginal{

	/**
	 *
	 * get data array from collection
	 *
	 */

	public function fetch($tableName,$where="",$orderField="",$groupByField="",$sqlAddon=""){

		$collection = Mage::getModel('nwdrevslider/' . $tableName)->getCollection();
		if ($where)
		{
			list($field, $value) = explode('=', $where);
			$collection->addFieldToFilter(trim($field, '"\' '), trim($value, '"\' '));
		}
		if ($orderField)
		{
			$arrOrderField = explode(' ', $orderField);
			if (count($arrOrderField) == 2)
			{
				$collection->setOrder($arrOrderField[0], strtolower($arrOrderField[1]));
			}
			else
			{
				$collection->setOrder($orderField, strtolower($sqlAddon) == 'desc' ? 'desc' : 'asc');
			}
		}

		$response = array();
		foreach ($collection as $_item) {
			$response[] = $_item->getData();
		}


		return $response;
	}

	/**
	 *
	 * insert variables to some table
	 */
	public function update($table,$arrItems,$where){

		if (is_array($where) && $where)
		{
			$collection = Mage::getModel('nwdrevslider/' . $table)->getCollection();
			foreach ($where as $_field => $_value) {
				$collection->addFieldToFilter($_field, $_value);
			}
			$item = $collection->getFirstItem();
			try {
				$item
					->addData($arrItems)
					->setId( $item->getId() )
					->save();
			} catch (Exception $e) {
				$this->throwError($e->getMessage());
			}
		}
		else
		{
			$this->throwError('No id provided.');
		}

		return true;
	}

	/**
	 *
	 * escape data to avoid sql errors and injections.
	 */
	public function escape($string) {
		return($string);
	}

	/**
	 *
	 * insert variables to some table
	 */
	public function insert($table,$arrItems) {


		$model = Mage::getModel('nwdrevslider/' . $table)->setData($arrItems);
		try {
			$model->save();
		} catch (Exception $e) {
			$this->throwError($e->getMessage());
		}

		$this->lastRowID = $model->getId();

		return $this->lastRowID;
	}

	/**
	 *
	 * delete rows
	 */
	public function delete($table,$where){

		UniteFunctionsRev::validateNotEmpty($table,"table name");
		UniteFunctionsRev::validateNotEmpty($where,"where");

		list($field, $value) = explode('=', $where);
		$collection = Mage::getModel('nwdrevslider/' . $table)->getCollection();
		$collection->addFieldToFilter($field, trim($value, '"\''));
		foreach ($collection as $_item) {
			$_item->delete();
		}
	}

	/**
	 *
	 * throw error
	 */
	private function throwError($message,$code=-1){
		UniteFunctionsRev::throwError($message,$code);
	}

}
