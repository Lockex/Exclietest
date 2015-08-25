<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\SessionManager;
use CsnUser\Entity\User;

class LoginController extends AbstractActionController
{

	/**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_entityManager;

    public function indexAction()
    {
        	if ($usuario = $this->identity()) {
                return $this->redirect()->toRoute('home');
            }

            $mensaje = null;
            if ($this->request->isPost()) {
            	$em = $this->getEntityManager();
            	$usuario = new User;
            	$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            	$adapter = $authService->getAdapter();
            	$usuarioOEmail = $this->params()->fromPost('usuario');

            	try {
            		$query = "SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usuarioOEmail' OR u.username = '$usuarioOEmail'";
            		$usuario = $em->createQuery($query)->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
            		$usuario = $usuario[0];

            		if(!isset($usuario)) {
            			$mensaje = 'Usuario o email no válidos.';
            			return new ViewModel(array(
            			    'error' => "Credenciales no válidas",
            			    'mensaje' => $mensaje,
            			));
            		}

            		if($usuario->getState()->getId() < 2) {
            		    $mensaje = 'El usuario está desactivado, contacte a un administrador.';
            		    return new ViewModel(array(
            		        'error' => 'Usuario no válido',        	        
            		        'mensaje' => $mensaje,        	     
            		    ));
            		}
            		$adapter->setIdentityValue($usuario->getUsername());
            		$adapter->setCredentialValue($this->params()->fromPost('pass'));

            		$authResult = $authService->authenticate();
            		if ($authResult->isValid()) {
            		    $identity = $authResult->getIdentity();
            		    $authService->getStorage()->write($identity);
            		    return $this->redirect()->toRoute('home');
            		}

            	} catch (Exception $e) {
            		return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
            		    $this->getTranslatorHelper()->translate('Something went wrong during login! Please, try again later.'),
            		    $e,
            		    $this->getOptions()->getDisplayExceptions(),
            		    $this->getOptions()->getNavMenu()
            		);
            	}
            }
            
            return new ViewModel(array(
                'error' => 'Usuario o contraseña incorrectos.',          
                'mensaje' => $mensaje,            
            ));
    }

    /**
     * get entityManager
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {
        if (null === $this->_entityManager) {
            $this->_entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->_entityManager;
    }

    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
            $sessionManager = new SessionManager();
            $sessionManager->forgetMe();
        }

        return $this->redirect()->toRoute('login');
    }
}
