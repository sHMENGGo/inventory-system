<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
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
     * Helpers loaded for every controller: forms (CSRF + old input),
     * URL building, authentication, and display formatting.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url', 'auth', 'format'];

    /**
     * @param CLIRequest|IncomingRequest $request
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * The logged-in user, or null for guests.
     *
     * @return array<string, mixed>|null
     */
    protected function currentUser(): ?array
    {
        return service('auth')->user();
    }

    protected function isAdmin(): bool
    {
        return service('auth')->isAdmin();
    }

    /**
     * Guard against malformed IDs before they reach PostgreSQL, which would
     * otherwise throw an "invalid input syntax for type uuid" error.
     */
    protected function isUuid(mixed $id): bool
    {
        return is_string($id)
            && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id) === 1;
    }

    /**
     * Generate a UUID (version 4).
     */
    protected function uuid(): string
    {
        $bytes = random_bytes(16);

        // Set the version to 4 and the variant to RFC 4122.
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
