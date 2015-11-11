<?php

class NullTemplateController extends RController {

        public function actionIndex() {
			$data = array();
            $this->render('risk/NullTemplate/NullTemplate.tpl',$data);
		}
        
        
}
