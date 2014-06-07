<?php
class IndexController extends Zend_Controller_Action
{
	
	function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
        Zend_Loader::loadClass('Usuario');
		Zend_Loader::loadClass('Acesso');
	}
	
	function indexAction()
	{
		$this->view->title = "Controle de Finanças";
		
		$session = new Zend_Session_Namespace('login');
		
		if(isset($session->user)){
			header('Location: /logado/');
			return;
		}
		
		$this->render();
	}
	function addAction()
	{
		$this->view->title = "Cadastrar novo Usuário";
		
		$session = new Zend_Session_Namespace('login');
		
		if(isset($session->user)){
			header('Location: /logado/');
			return;
		}
		
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
				echo '<center><div id=\'alerta\' class="alert alert-danger">
						<p>Os campos Senha, Username e E-mail não podem ser nulos</p>
					  </div></center>';
				return;
			}

			if($confirmSenha !== $senha){
				echo '<center><div id=\'alerta\' class="alert alert-danger">
						<p>As senhas não conferem</p>
					  </div></center>';
				return;
			}

			$usuario = new Usuario();
			
			$rowset = $usuario->fetchAll($usuario->select()->where('Username = ?',$username));
			
			if( count($rowset) > 0){
				echo '<center><div id=\'alerta\' class="alert alert-danger">
						<p>Username já existe!</p>
					  </div></center>';
				return;
			}
			
			$rowset = $usuario->fetchAll($usuario->select()->where('Email = ?',$email));
			
			if( count($rowset) > 0){
				echo '<center><div id=\'alerta\' class="alert alert-danger">
						<p>E-mail já existe!</p>
					  </div></center>';
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
			
			echo ' <center><div id=\'alerta\' class="alert alert-danger">
						<p>Cadastrado com sucesso!</p>
					  </div></center>';
			
		}
		$this->render();
	}
	function loginAction()
	{
		$this->view->title = "Logando...";
		
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			Zend_Loader::loadClass('Zend_Filter_StripTags');
			$filter = new Zend_Filter_StripTags();
			$login = $filter->filter($this->_request->getPost('login'));
			$senha = $filter->filter($this->_request->getPost('senha'));
			
			$usuario = new Usuario();
			
			$rowset = $usuario->fetchAll($usuario->select()->where('Senha = ?',$senha)->where('Username = ? OR Email = ?',$login));	
			
			
			if( count($rowset) == 0){
				$this->view->title = "Usuário não encontrado";
				return;
			}
			
			$acesso = new Acesso();
			$date = new Zend_Date();
			
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			
			$data = array(
				'ID_Usuario' => $rowset->current()->ID,
				'Data_Acesso' => $date->get(Zend_Date::DATE_MEDIUM),
				'IP' => $ip
			);
			
			$acesso->insert($data);
			
			
			unset($session);
			$session = new Zend_Session_Namespace('login');
			
			$row = $rowset->current();
			$session->user = $row->ID;
			
			header('Location: /logado/');
			
		}else{
			header('Location: /index/');
		}
		
		$this->render();
	}
}