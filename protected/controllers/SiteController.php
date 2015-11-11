<?php

class SiteController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('login'),
                'users' => array('?'),
            ),
            array('deny',
                'users' => array('?'),
            ),
        );
    }
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        //$this->render('layouts/header.tpl');
        $this->redirect("/auth/index");
    }

    public function actionUser() {
        echo Yii::app()->user->id;
        echo Yii::app()->user->username;
        echo Yii::app()->user->name;
        echo Yii::app()->user->departid;
        echo Yii::app()->user->depart;
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        parent::login();
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
        Yii::app()->request->redirect('/site/login');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionError() {
        $this->render('error/404.tpl');
    }

    public function actionApiAuthErrorHandler() {
        $this->render('error/404.tpl', array("message" => "您暂无该功能权限!"));
    }

}