<?php
	class HashTable {
		private $arr; //用于存储数据的数组
		private $size; //记录arr 数组的大小
		public function __construct($size) {
			$this->size = $size;
			$this->arr = new SplFixedArray($this->size);
		}
		private function hashfunc($key) {
			return $key % $this->size;
		}
		public function insert($key, $value) {
			$index = $this->hashfunc($key);
			if (isset($this->arr[$index])) {
				$newNode = new HashNode($key, $value, $this->arr[$index]);
			} else {
				$newNode = new HashNode($key, $value, null);
			}
			$this->arr[$index] = $newNode;
		}

		public function find($key) {
			$index = $this->hashfunc($key);
			$current = $this->arr[$index];
			//var_dump($current);
			while (isset($current)) {
				//遍历当前链表
				if ($current->key == $key) { //比较当前结点关键字
					return $current->value;
				}
				$current = $current->nextNode;
				//return $current->value;
			}
			return null;
		}

	}
	class HashNode {
		public $key; //关键字
		public $value; //数据
		public $nextNode; //HASHNODE来存储信息
		public function __construct($key, $value, $nextNode = null) {
			$this->key = $key;
			$this->value = $value;
			$this->nextNode = $nextNode;
		}
	}
	function executeSQL_ns($sql) {
		if (mysql_query($sql)) {
			//echo 1;
		} else {
			echo "error:".mysql_error();die();
		}
	}
	
	function executeSQL($sql) {
		if ($result = mysql_query($sql)) {
			return $result;
			//echo 1;
		} else {
			echo "error:".mysql_error();die();
		}
	}
	
?>