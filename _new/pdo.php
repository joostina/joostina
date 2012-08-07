<?php

class joosModelBase{
	
	protected $limit = -1;
	protected $offset = -1;
	
	// первичный индекс
	protected $key = 'id';

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;

	public static function tableName()
	{
		return 'tbl_customer';
	}

	public static function relations()
	{
		return array(
			'orders:Order[]' => array(
				'link' => array('customer_id' => 'id'),
			),
		);
	}

	public static function scopes()
	{
		return array(
			'active' => function($q) {
				return $q->andWhere('@.`status` = ' . self::STATUS_ACTIVE);
			},
		);
	}
	
	// вывод всех записей
	public function all()
	{
		
		$this->limit = -1;
		$this->offset = -1;
	}

	// вывод одной записи
	public function one()
	{

		$this->limit = 1;
		$this->offset = 0;
	}

	// вывод первой записи
	public function first()
	{

		$this->limit = 1;
		$this->offset = 0;
		$this->order = array( $this->key, 'DESC' );
	}


}

Customer::find()->one();
Customer::find()->all();