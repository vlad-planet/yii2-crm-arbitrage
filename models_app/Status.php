<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Status
{
	//const STATUS_DRAFT = 0;
	const STATUS_DELETED = -1;
	const STATUS_UNAVAILABLE = 2;
	const STATUS_COMPLETED = 3;
	const STATUS_PROCESS = 4;
    const STATUS_INACTIVE = 9;
	const STATUS_ACTIVE = 10;
	const STATUS_BLOCKED = 11;
	const STATUS_WAIT = 12;
	
    public static function statusList($model=null): array
    {
		if($model == 'User'){
			return [
				' ' => '...',
				self::STATUS_DELETED => 'Удален',
				self::STATUS_INACTIVE => 'Не активен',
				self::STATUS_ACTIVE => 'Активен',
			];
		}elseif($model == 'CloudFlare'){
			return [
				' ' => '...',
				self::STATUS_DELETED => 'Удален',
				self::STATUS_INACTIVE => 'Не активен',
				self::STATUS_ACTIVE => 'Активен',
				self::STATUS_WAIT => 'Ожидает загрузки',
				self::STATUS_BLOCKED => 'Заблокирован',
			];
		}elseif($model == 'Server'){
			return [
				' ' => '...',
				self::STATUS_DELETED => 'Удален',
				self::STATUS_INACTIVE => 'Не активен',
				self::STATUS_ACTIVE => 'Активен',
				self::STATUS_BLOCKED => 'Заблокирован',
			];
		}elseif($model == 'Staff'){
			return [
				' ' => '...',
				self::STATUS_DELETED => 'Уволен',
				self::STATUS_ACTIVE => 'Работает',
			];
		}elseif($model == 'Domain'){
			return [
				' ' => '...',
				self::STATUS_DELETED => 'Удален',
				self::STATUS_INACTIVE => 'Не активен',
				self::STATUS_ACTIVE => 'Активен',
				self::STATUS_WAIT => 'Ожидает загрузки',
				self::STATUS_BLOCKED => 'Заблокирован',
			];
		}elseif($model == 'Task'){
			return [
				' ' => '...',
				self::STATUS_UNAVAILABLE => 'Недоступен',
				self::STATUS_COMPLETED => 'Завершено',
				self::STATUS_PROCESS => 'Процессе',
			];
		}else{
			return [
				' ' => '...',
			];
		}
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status,$model=null): string
    {	
        switch ($status) {
			    case self::STATUS_DELETED:
                $class = 'badge badge-danger';
                break;
			    case self::STATUS_UNAVAILABLE:
                $class = 'badge badge-danger';
                break;
			    case self::STATUS_COMPLETED:
                $class = 'badge badge-success';
                break;
			    case self::STATUS_PROCESS:
                $class = 'badge badge-info';
                break;
			    case self::STATUS_INACTIVE:
                $class = 'badge badge-warning';
                break;
				case self::STATUS_ACTIVE:
                $class = 'badge badge-success';
                break;
				case self::STATUS_BLOCKED:
                $class = 'badge badge-danger';
                break;
			    case self::STATUS_WAIT:
                $class = 'badge badge-info';
                break;
            default:
                $class = 'badge badge-default';
        }
				
        return Html::tag('span', ArrayHelper::getValue(self::statusList($model), $status), [
            'class' => $class,
        ]);
    }
}