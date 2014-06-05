<?php
class IndexController extends Zend_Controller_Action
{
	
	function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
        Zend_Loader::loadClass('Usuario');
	}
	
	function indexAction()
	{
		$this->view->title = "Controle de Finanças";
		$this->render();
	}
	function addAction()
	{
		$this->view->title = "Cadastrar novo Usuário";
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			Zend_Loader::loadClass('Zend_Filter_StripTags');
			$filter = new Zend_Filter_StripTags();
			$username = $filter->filter($this->_request->getPost('username'));
			$email = $filter->filter($this->_request->getPost('e-mail'));
			$senha = $filter->filter($this->_request->getPost('senha'));
			$confirmSenha = $filter->filter($this->_request->getPost('confirmSenha'));
			$nome = $filter->filter($this->_request->getPost('nome'));
			$sobrenome = $filter->filter($this->_request->getPost('sobrenome'));
			
			if($senha == '' || $username == '' || $email == ''){
				echo ' <div id=\'alerta\'>
						<p>Os campos Senha, Username e E-mail não podem ser nulos</p>
					  </div>';
				return;
			}

			if($confirmSenha !== $senha){
				echo ' <div id=\'alerta\'>
						<p>As senhas não conferem</p>
					  </div>';
				return;
			}

			$usuario = new Usuario();
			
			$rowset = $usuario->fetchAll($usuario->select()->where('Username = ?',$username));
			
			if( count($rowset) > 0){
				echo ' <div id=\'alerta\'>
						<p>Username já existe!</p>
					  </div>';
				return;
			}
			
			$rowset = $usuario->fetchAll($usuario->select()->where('Email = ?',$email));
			
			if( count($rowset) > 0){
				echo ' <div id=\'alerta\'>
						<p>E-mail já existe!</p>
					  </div>';
				return;
			}
			
			$data = array(
					'Nome' => $nome,
					'Sobrenome' => $sobrenome,
					'Username' => $username,
					'Email' => $email,
					'Senha' => $senha
			);
			
			$usuario->insert($data);
			
			echo ' <div id=\'alerta\'>
						<p>Cadastrado com sucesso!</p>
					  </div>';
			
		}
		$this->render();
	}
	function loginAction()
	{
		$this->view->title = "Logando...";
		$this->render();
	}
}