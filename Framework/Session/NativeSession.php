<?php

declare(strict_types=1);

namespace Framework\Session;

use Framework\Session\Interface\SessionInterface;

/**
 * Implémentation native de la gestion de session PHP.
 *
 * Cette classe encapsule les fonctions natives de session,
 * en offrant une interface orientée objet pour gérer le cycle de vie,
 * l'accès, la modification, et la sécurité de la session.
 *
 * Elle gère également les métadonnées internes pour le contrôle de l'expiration
 * et la régénération automatique de l'identifiant de session.
 *
 * @package Framework\Session
 */
final class NativeSession implements SessionInterface
{
    /**
     * Indique si la session a été démarrée.
     *
     * @var bool
     */
    private bool $started = false;

    /**
     * Initialise une session avec les options fournies.
     *
     * Les options supportées incluent :
     * - name : nom du cookie de session
     * - lifetime : durée de vie en secondes
     * - path : chemin du cookie
     * - domain : domaine du cookie
     * - secure : flag Secure du cookie
     * - httponly : flag HttpOnly du cookie
     * - samesite : attribut SameSite du cookie (ex. Lax, Strict)
     *
     * @param mixed[] $options Tableau associatif des options de session.
     */
    public function __construct(private array $options = [])
    {
        $this->options = array_merge([
            'name'     => 'APP_SESSION',
            'lifetime' => 3600,
            'path'     => '/',
            'domain'   => '',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ], $options);
    }

    /**
     * Démarre la session PHP si elle n'est pas déjà démarrée.
     *
     * Initialise aussi les métadonnées internes pour la gestion du cycle de vie.
     *
     * @return void
     */
    public function start(): void
    {
        if ($this->started || session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => $this->options['lifetime'],
            'path'     => $this->options['path'],
            'domain'   => $this->options['domain'],
            'secure'   => $this->options['secure'],
            'httponly' => $this->options['httponly'],
            'samesite' => $this->options['samesite'],
        ]);

        session_name($this->options['name']);
        session_start();

        $this->started = true;

        if (!isset($_SESSION['_metadata'])) {
            $_SESSION['_metadata'] = [
                'created_at'    => time(),
                'last_activity' => time(),
            ];
        }

        $this->checkExpiration();
    }

    /**
     * Indique si la session a bien été démarrée et est active.
     *
     * @return bool True si la session est active, false sinon.
     */
    public function isStarted(): bool
    {
        return $this->started && session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Récupère une valeur en session par sa clé.
     *
     * @param string $key Clé de la donnée.
     * @param mixed|null $default Valeur retournée si la clé n'existe pas.
     * @return mixed Valeur associée à la clé, ou $default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Définit (ou remplace) une valeur dans la session.
     *
     * Met à jour la date de dernière activité.
     *
     * @param string $key Clé de la donnée.
     * @param mixed $value Valeur à stocker.
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
        $this->updateLastActivity();
    }

    /**
     * Indique si une clé existe en session.
     *
     * @param string $key Clé à vérifier.
     * @return bool True si la clé existe, false sinon.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Supprime une clé et sa valeur de la session.
     *
     * @param string $key Clé à supprimer.
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Retourne la valeur d'une clé en session et la supprime (flash).
     *
     * @param string $key Clé de la donnée flash.
     * @param mixed|null $default Valeur par défaut si absente.
     * @return mixed Valeur flash ou $default.
     */
    public function flash(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->remove($key);
        return $value;
    }

    /**
     * Retourne toutes les données stockées dans la session.
     *
     * @return mixed[] Tableau associatif des données de session.
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * Efface toutes les données de session sans détruire la session active.
     *
     * @return void
     */
    public function clear(): void
    {
        $_SESSION = [];
    }

    /**
     * Régénère l'identifiant de session.
     *
     * @param bool $deleteOldSession Indique si l'ancien ID doit être détruit.
     * @return void
     */
    public function regenerate(bool $deleteOldSession = true): void
    {
        session_regenerate_id($deleteOldSession);
        $this->updateLastActivity();
    }

    /**
     * Détruit complètement la session et les cookies de session associés.
     *
     * @return void
     */
    public function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->started = false;
    }

    /**
     * Retourne l'identifiant actuel de la session.
     *
     * @return string ID de session.
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * Sauvegarde la session en fermant l'accès en écriture.
     *
     * @return void
     */
    public function save(): void
    {
        if ($this->isStarted()) {
            session_write_close();
        }
    }

    /**
     * Met à jour la date de dernière activité dans les métadonnées.
     *
     * @return void
     */
    private function updateLastActivity(): void
    {
        if (isset($_SESSION['_metadata'])) {
            $_SESSION['_metadata']['last_activity'] = time();
        }
    }

    /**
     * Vérifie l'expiration de la session.
     *
     * Régénère l'ID si inactivité supérieure à 30 minutes,
     * détruit la session si elle dépasse la durée de vie configurée.
     *
     * @return void
     */
    private function checkExpiration(): void
    {
        if (!isset($_SESSION['_metadata'])) {
            return;
        }

        $now = time();
        $metadata = $_SESSION['_metadata'];

        // Régénérer l'ID toutes les 30 minutes
        if (($now - $metadata['last_activity']) > 1800) {
            $this->regenerate();
        }

        // Expirer après la durée de vie
        if (($now - $metadata['created_at']) > $this->options['lifetime']) {
            $this->destroy();
        }
    }
}
