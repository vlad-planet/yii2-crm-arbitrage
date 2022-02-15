<?php
namespace common\models;

use Yii;
use yii\base\Model;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
	public $lastname;
    public $email;
    public $password;
	public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username', 'lastname', 'email', 'password','password_repeat'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занят.'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        
			[['password', 'password_repeat'], 'string'],
			[
				'password_repeat', 'compare', 'compareAttribute' => 'password',
				'message' => "Пароли не совпадают", 'skipOnEmpty' => false,
				'when' => function ($model) {
					return $model->password !== null && $model->password !== '';
				},
			],
		
		];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
			'username' => 'Имя',
			'lastname' => 'Фамилия',
            'email' => 'Электронная почта',
			'password' => 'Пароль',
			'password_repeat' => 'Повторите пароль',
        ];
		
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */

	public function signup()
	{
		if (!$this->validate()) {
			return null;
		}
		
		$user = new User();
		$user->username = $this->username;
		$user->lastname = $this->lastname;
		$user->email = $this->email;
		$user->setPassword($this->password);
		$user->generateAuthKey();

		//Добавляем роль по умолчанию для каждого зарегестрированного 
		if($user->save()){ // && $this->sendEmail($user)
			$auth = Yii::$app->authManager;
			$role = $auth->getRole('user');
			$auth->assign($role, $user->id);
			return $user;
		}
		return null;
	}


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Регистрация учетной записи на ' . Yii::$app->name)
            ->send();
    }
}
