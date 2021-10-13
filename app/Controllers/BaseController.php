<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use App\Models\GlobalModel;
use App\Models\DatatableModel;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['url', 'form', 'security','my'];
	protected $template;

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->session = session();
        $this->session->start();
        // $this->validation =  \Config\Services::validation();
        // $this->encrypter = \Config\Services::encrypter();
        // $this->security = \Config\Services::security();
        $this->db = \Config\Database::connect();
        // $this->uri = new \CodeIgniter\HTTP\URI();
        $this->m_global = new GlobalModel();
        $this->uri = $this->request->uri;
	}

	function respon($data=null, $message=null, $status=true){
        $result['status'] = $status ?? false;
        $result['message'] = $message;
        $result['data'] = $data;
        echo json_encode($result,JSON_UNESCAPED_SLASHES);exit;
    }

}
