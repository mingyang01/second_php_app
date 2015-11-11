<?php
class AuditLog extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'shop_groupon_audit_comments';
    }
}