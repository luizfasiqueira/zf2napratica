<?php

namespace Admin\Service;

use Core\Service\Service;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\Sql\Select;

/**
 * ServiÃ§o responsÃ¡vel pela autenticaÃ§Ã£o da aplicaÃ§Ã£o
 * 
 * @category Admin
 * @package Service
 * @author  Elton Minetto<eminetto@coderockr.com>
 */
class Auth extends Service {

    /**
     * Adapter usado para a autenticaÃ§Ã£o
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct($dbAdapter = null) {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * Faz a autenticaÃ§Ã£o dos usuÃ¡rios
     * 
     * @param array $params
     * @return array
     */
    public function authenticate($params) {
        if (!isset($params['username']) || !isset($params['password'])) {
            echo 'entrou aqui';
            exit;
            throw new \Exception("Parâmetros inválidos");
        }

        $password = md5($params['password']);
        $auth = new AuthenticationService();
        $authAdapter = new AuthAdapter($this->dbAdapter);
        $authAdapter
                ->setTableName('users')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setIdentity($params['username'])
                ->setCredential($password);
        $result = $auth->authenticate($authAdapter);

        if (!$result->isValid()) {
            throw new \Exception("Login ou senha inválidos");
        }

        //salva o user na sessÃ£o
        $session = $this->getServiceManager()->get('Session');
        $session->offsetSet('user', $authAdapter->getResultRowObject());

        return true;
    }

    /**
     * Faz o logout do sistema
     *
     * @return void
     */
    public function logout() {
        $auth = new AuthenticationService();
        $session = $this->getServiceManager()->get('Session');
        $session->offsetUnset('user');
        $auth->clearIdentity();
        return true;
    }

    /**
     * Faz a autorizaÃ§Ã£o do usuÃ¡rio para acessar o recurso
     * @return boolean
     */
    public function authorize() {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            return true;
        }
        return false;
    }

}
