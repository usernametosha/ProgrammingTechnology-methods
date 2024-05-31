<?php

namespace app\models;
use yii\base\Model;

class RegistrationForm extends User {

    public $repeat_password;
    public $rules = false;


    public function rules() {
        return [

            [['name', 'surname', 'email', 'login', 'password', 'repeat_password', 'rules'], 'required'],
            [['name', 'surname', 'email', 'login', 'password', 'patronymic'], 'string', 'max'=>255],
            ['name', 'match', 'pattern' =>'/^[а-яА-ЯёЁ\-\s]+$/u' ],
            ['surname', 'match', 'pattern' =>'/^[а-яА-ЯёЁ\-\s]+$/u' ],
            ['patronymic', 'match', 'pattern' =>'/^[а-яА-ЯёЁ\-\s]+$/u' ],
            ['login', 'match', 'pattern' =>'/^[a-zA-Z\-\s]+$/u' ],
            ['login','unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],
            ['email','email'],
            ['email', 'unique', 'targetClass' => User::class],
            ['rules', 'compare', 'compareValue' => true, 'message' => 'Для регистрации неообходимо разрешить обработку персональных данных'],

        ];
    }

    public function attributeLabels() {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'repeat_password' => 'Подтверждение пароля',
            'id' => 'ID',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'rules' => 'Обработка персональных данных',
            'email' => 'Email',
        ];
    }

    public function Registration()
    {
        if (!$this->validate()){
            return null;
        }

        $user = new User;
        $user->load($this->attributes, '');
        $user->role_id = Role::findRole('user');
        return $user->save(false) ? $user : null;
    }

}
