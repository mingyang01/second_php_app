<?php
class WeekSelect extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'brd_shop_groupon_week_select_info';
	}
}