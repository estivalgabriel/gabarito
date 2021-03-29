<?php
/*
LoginController - controller do login
*/
class HomeController extends MainController{

	public function index() {

		// DEFINO AS SEGUINTES INFORMAÇÕES DA PÁGINA CRIADA PELO CONTROLLER
		// @title
		// @description
		// @keywords
		//
		// =-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		// NAO DEIXAR DE CONFIGURAR INDIVIDUALMENTE ESSAS INFORMAÇÕES
		// =-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$this->titulo 			= 'Index';
		$this->descricao 		= 'Index';
		$this->keyword 			= 'Index';
		
		// Parametros da funcao
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();

		//carrega o modelo
		$model = $this->load_model('home-model');

		//echo $model->controller;

		// Carrega as views
		require_once VIEW_ABSPATH . '/_head.php';
		require_once VIEW_ABSPATH . '/home.php';
		require_once VIEW_ABSPATH . '/_footer.php';
	}

}
