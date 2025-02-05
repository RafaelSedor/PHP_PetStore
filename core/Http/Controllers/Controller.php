<?php

namespace Core\Http\Controllers;

use App\Models\User;
use Core\Constants\Constants;
use Lib\Authentication\Auth;

class Controller
{
    protected string $layout = 'application';

    // protected ?User $current_user = null;

    // public function __construct()
    // {
    //     $this->current_user = Auth::user();
    // }

    // public function currentUser(): ?User
    // {
    //     if ($this->current_user === null) {
    //         $this->current_user = Auth::user();
    //     }

    //     return $this->current_user;
    // }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $view = Constants::rootPath()->join('app/views/' . $view . '.phtml');
        require Constants::rootPath()->join('app/views/layouts/' . $this->layout . '.phtml');
    }


    /**
     * @param array<string, mixed> $data
     */
    protected function renderJson(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json', true, $statusCode);
        echo json_encode($data);
        exit;
    }

    protected function redirectTo(string $location): void
    {
        if (defined('IS_TEST_ENV')) {
            throw new \Exception("Redirecting to: $location");
        }

        header('Location: ' . $location);
        exit;
    }

    protected function redirectBack(): void
    {
        if (defined('IS_TEST_ENV')) {
            throw new \Exception("Redirecting back");
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirectTo($referer);
    }
}
