<?php
class LogadoController extends Zend_Controller_Action{
	
	function init(){
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
        Zend_Loader::loadClass('Usuario');
		Zend_Loader::loadClass('Acesso');
		Zend_Loader::loadClass('ContaPagar');
		Zend_Loader::loadClass('ContaReceber');
	}
	
	function indexAction(){
	
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Home";
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function deleteAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Deletar Conta";
			
			if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
				
				$pagar = new ContaPagar();
				$where = $pagar->getAdapter()->quoteInto('ID_Usuario = ?',$session->user);
				$pagar->delete($where);
				
				$receber = new ContaReceber();
				$where = $receber->getAdapter()->quoteInto('ID_Usuario = ?',$session->user);
				$receber->delete($where);
				
				$ac = new Acesso();
				$where = $ac->getAdapter()->quoteInto('ID_Usuario = ?',$session->user);
				$ac->delete($where);
				
				$usuario = new Usuario();
				$where = $usuario->getAdapter()->quoteInto('ID = ?',$session->user);
				$usuario->delete($where);
				
				header('Location: /logado/logout');
			}
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function editAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Editar Conta";
			
			if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
				Zend_Loader::loadClass('Zend_Filter_StripTags');
				$filter = new Zend_Filter_StripTags();
				$nome = $filter->filter($this->_request->getPost('nome'));
				$sobrenome = $filter->filter($this->_request->getPost('sobrenome'));
				
				$usuario = new Usuario();
				
				$data = array(
					'Nome' => $nome,
					'Sobrenome' => $sobrenome
				);
				
				$where = $usuario->getAdapter()->quoteInto('ID = ?', $session->user);
				$usuario->update($data, $where);
				
				echo '<div id="alerta">Alterado com sucesso</div>';
			}
			
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function addcontaAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Adiciona Conta";
			
			
			if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
				Zend_Loader::loadClass('Zend_Filter_StripTags');
				$filter = new Zend_Filter_StripTags();
				$valor = $filter->filter($this->_request->getPost('valor'));
				$obs = $filter->filter($this->_request->getPost('obs'));
				$T = $filter->filter($this->_request->getPost('T'));
				
				$date = new Zend_Date();
				
				$data = array(
					'Valor' => $valor,
					'OBS' => $obs,
					'ID_Usuario' => $session->user,
					'Status' => 'Pendente',
					'Data_Criacao' => $date->get(Zend_Date::DATE_MEDIUM)
				);
				
				if($T == 1){
					$pagar = new ContaPagar();
					$pagar->insert($data);
				}elseif($T == 2){
					$receber = new ContaReceber();
					$receber->insert($data);
				}
				
			}
			
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function editcontaAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Editar Conta";
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function delcontaAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Deletar Conta";
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function dashboardAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Dashboard";
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
	function logoutAction(){
		$session = new Zend_Session_Namespace('login');

		if(isset($session->user)){
			$this->view->title = "Logout...";
			unset($session->user);
			unset($session);
			header('Location: /index/');
			$this->render();
		}else{
			header('Location: /index/');
		}
	}
	
}
