<?php

namespace App\Libraries;

use App\Models\UserModel;

/**
 * Session-based authentication.
 *
 * The session only stores the user's ID. The user row is loaded from the
 * database when needed, so an admin whose rights were revoked does not keep
 * admin access until their next login.
 */
class Auth
{
    private ?array $user = null;
    private bool $loaded = false;

    /**
     * Is somebody logged in?
     */
    public function check(): bool
    {
        return $this->user() !== null;
    }

    /**
     * The logged-in user, or null.
     *
     * @return array<string, mixed>|null
     */
    public function user(): ?array
    {
        if ($this->loaded) {
            return $this->user;
        }

        $this->loaded = true;

        $id = session('user_id');

        if (! is_string($id) || $id === '') {
            return null;
        }

        $user = (new UserModel())
            ->select('id, name, email, is_admin, created_at, updated_at')
            ->find($id);

        if ($user === null) {
            // The account was deleted while the session was still alive.
            session()->remove(['user_id', 'user_name']);

            return null;
        }

        $user['is_admin'] = $this->toBool($user['is_admin']);

        $this->user = $user;

        return $this->user;
    }

    /**
     * Is the logged-in user an administrator?
     */
    public function isAdmin(): bool
    {
        $user = $this->user();

        return $user !== null && $user['is_admin'] === true;
    }

    /**
     * Check an email/password pair and log the user in on success.
     */
    public function attempt(string $email, string $password): bool
    {
        $user = (new UserModel())
            ->where('email', strtolower(trim($email)))
            ->first();

        if ($user === null || ! password_verify($password, $user['password'])) {
            return false;
        }

        $this->login($user);

        return true;
    }

    /**
     * Start a logged-in session for a user row.
     *
     * @param array<string, mixed> $user
     */
    public function login(array $user): void
    {
        // Prevent session fixation: give the client a fresh session ID.
        session()->regenerate();

        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
        ]);

        $user['is_admin'] = $this->toBool($user['is_admin']);

        $this->user   = $user;
        $this->loaded = true;
    }

    /**
     * Log out by forgetting the authentication keys and rotating the ID.
     */
    public function logout(): void
    {
        session()->remove(['user_id', 'user_name']);
        session()->regenerate();

        $this->user   = null;
        $this->loaded = true;
    }

    /**
     * PostgreSQL returns booleans as true/false or "t"/"f".
     */
    private function toBool(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 't', 'true'], true);
    }
}
