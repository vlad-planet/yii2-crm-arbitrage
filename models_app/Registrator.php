<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registrator".
 *
 * @property int $id
 * @property string $name
 * @property string|null $prefix
 * @property string|null $ip
 * @property string|null $user
 * @property string|null $login
 * @property string|null $password
 * @property string|null $api_key
 * @property string|null $api_url
 */
class Registrator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registrator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'ip', 'login', 'password', 'user', 'api_key', 'api_url'], 'string', 'max' => 255],
            [['name'], 'unique'],
			[['prefix'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
			'prefix' => 'Prefix',
            'ip' => 'Ip',
			'login' => 'Login',
            'password' => 'Password',
            'user' => 'user',
            'api_key' => 'Api Key',
			'api_url' => 'Api Url',
        ];
    }
	
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }
	
}
