<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Nyalakan session dan helper secara global
        $this->session = \Config\Services::session();

        // Tambahkan 'custom' di dalam array helper ini
        helper(['url', 'form', 'text', 'custom', 'fungsi']);
    }

    /**
     * Pengecekan sesi login secara global
     */
    protected function checkAuth()
    {
        // Mengecek session menggunakan key yang valid di aplikasi Anda
        // sekaligus memastikan hanya level_id 1 (Admin) yang bisa mengakses
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            header('Location: ' . base_url('auth'));
            exit;
        }
    }
}
