<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Directory_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this -> _setupDirectory();
	}

	// GRUPOS

	public function getGroup($id = NULL, $includePath = NULL, $includeContent = NULL) {
		$group = $id === NULL ? $this -> _getRootNode() : $this -> _getNode($id, 'group');
		if ($group !== NULL) {
			if ($includePath) {
				$group['path'] = $this -> _getPath($group['id']);
			}
			if ($includeContent) {
				$group['content'] = $this -> _getDirectChildren($group['id']);
			}						
		}
		return $group;
	}

	public function getRootGroup($includePath = NULL, $includeContent = NULL) {
		return $this -> getGroup(NULL, $includePath, $includeContent);
	}

	public function createGroup($parentId, $name, $extraFields) {
		return $this -> _appendNewChild($parentId, 'group', $name, $extraFields);
	}

	public function updateGroup($id, $name, $extraFields) {
		return $this -> _updateNode($id, $name, $extraFields);
	}

	public function deleteGroup($id) {
		return $this -> _deleteNode($id);
	}

	// PANTALLAS

	public function getScreen($id) {
		$screen = $this -> _getNode($id, 'screen');
		return $screen;
	}

	public function createScreen($parentId, $name, $extraFields) {
		return $this -> _appendNewChild($parentId, 'screen', $name, $extraFields);
	}

	public function updateScreen($id, $name, $extraFields) {
		return $this -> _updateNode($id, $name, $extraFields);
	}

	public function deleteScreen($id) {
		return $this -> _deleteNode($id);
	}

	// Helpers privados

	private function _setupDirectory() {
		if ($this -> _getRootNode() === NULL) {
			// nuevo nodo raiz
			$parentId = 0;
			$leftValue = 1;
			$rightValue = 2;
			$this -> _addNode($parentId, $leftValue, $rightValue, 'group', 'Groups and Screens');
		}
	}

	private function _getRootNode() {
		$query = $this -> db -> get_where('groups', ['parent_id' => 0]);
		return $query -> row_array(); 
	}

	private function _getNode($id, $type) {
		$where = ['id' => $id];
		$typeView = $type === 'group' ? 'groups' : 'screens';
		$query = $this -> db -> get_where($typeView, $where);
		return  $query -> row_array();
	}

	private function _getNodeCore($id) {
		$query = $this -> db -> get_where('directory', ['id' => $id]);
		return $query -> row_array();
	}

	private function _getDirectChildren($id) {
		$query = $this -> db 
			-> where(['parent_id' => $id])
			-> order_by('name', 'ASC')
			-> get('groups');
		$groups = $query -> result_array();

		$query = $this -> db 
			-> where(['parent_id' => $id])
			-> order_by('name', 'ASC')
			-> get('screens');
		$screens = $query -> result_array();

		$children = array_merge($groups, $screens);
		
		return $children;
	}

	private function _getPath($id) {;
		$node = $this -> _getNodeCore($id);

		$query = $this -> db
			-> select('id, name')
			-> where('left_value' . ' <= ' . $node['left_value'] . ' AND ' . 'right_value' . ' >= ' . $node['right_value'])
			-> order_by('left_value')
			-> get('directory');

		$path = $query -> result_array();

		return $path;
	}

	private function _addNode($parentId, $leftValue, $rightValue, $type, $name, $extraFields = []) {
		$newNode['parent_id'] = (int) $parentId;
		$newNode['left_value'] = (int) $leftValue;
		$newNode['right_value'] = (int) $rightValue;
		$newNode['type'] = $type;
		$newNode['name'] = $name;

		// proceso operacion como transaccion (afecta a varias tablas en la BD)
		$this -> db -> trans_start();

		$this -> db -> insert('directory', $newNode);
		$newNodeId = $this -> db -> insert_id();
		$extraFields['id'] = $newNodeId;
		$typeTable = $type === 'group' ? 'groups_data' : 'screens_data';
		$this -> db -> insert($typeTable, $extraFields);

		$this -> db -> trans_complete();
		$created = $this -> db -> trans_status();
		return $created ? $newNodeId : NULL;
	}

	private function _appendNewChild($parentId, $type, $name, $extraFields = []) {
		$parentNode = $this -> _getNodeCore($parentId);
		$newNodeId = NULL;

		if ($parentNode !== NULL) {
			$parentId = $parentNode['id'];
			$leftValue = $parentNode['right_value'];
			$rightValue = $leftValue + 1;
			$nodeWidth = 2;  // (rightValue - leftValue) + 1 = 2 para todo nuevo nodo
			
			if ($this -> _shiftNodes($leftValue, $nodeWidth)) {
				$newNodeId = $this -> _addNode($parentId, $leftValue, $rightValue, $type, $name, $extraFields);
			}
		}
		
		return $newNodeId;
	}

	private function _updateNode($id, $name = NULL, $extraFields = []) {
		$node = $this -> _getNodeCore($id);

		// proceso operacion como transaccion (afecta a varias tablas en la BD)
		$this -> db -> trans_start();

		$where = ['id' => $id];
		if ($name !== NULL) {
			$newValue = ['name' => $name];
			$this -> db -> update('directory', $newValue, $where);
		}
		if (!empty($extraFields)) {
			$typeTable = $node['type'] === 'group' ? 'groups_data' : 'screens_data';
			$this -> db -> update($typeTable, $extraFields, $where);
		}

		$this -> db -> trans_complete();
		return $this -> db -> trans_status();
	}

	private function _deleteNode($id) {
		$node = $this -> _getNodeCore($id);

		// proceso operacion como transaccion (afecta a varias tablas en la BD)
		$this -> db -> trans_start();

		$where = [
			'left_value' . ' >=' => $node['left_value'],
			'left_value' . ' <=' => $node['right_value'],
		];
		$this -> db -> delete('directory', $where);

		$nodeWidth = $node['left_value'] - $node['right_value'] - 1;
		$this -> _shiftNodes($node['right_value'] + 1, $nodeWidth);

		$this -> db -> trans_complete();
		return $this -> db -> trans_status();
	}

	private function _shiftNodes($startingPoint, $changeVal) {
		// proceso operacion como transaccion (afecta a varias filas en la BD)
		$this -> db -> trans_start();

		$this -> db -> set('left_value', 'left_value' . '+' . (int) $changeVal, FALSE);
		$this -> db -> where('left_value' . ' >=', (int) $startingPoint);
		$this -> db -> update('directory');

		$this -> db -> set('right_value', 'right_value' . '+' . (int) $changeVal, FALSE);
		$this -> db -> where('right_value' . ' >=', (int) $startingPoint);
		$this -> db -> update('directory');

		$this -> db -> trans_complete();
		return $this -> db -> trans_status();
	}
}