<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Portal implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        // Do something here
        $session = \Config\Services::session();
        if(!$session->has('mysession'))
        {
        	return redirect()->to('/portal');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}