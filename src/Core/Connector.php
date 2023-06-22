<?php

namespace ErnandesRS\EasyCrud\Core;

class Connector
{
    /**
     * PDO instance
     *
     * @var \PDO
     */
    private \PDO $pdo;

    /**
     * Connect
     *
     * @param string $dataBaseName
     * @param string $host
     * @param string $user
     * @param string $password
     * @param array $options
     * @return Connector
     */
    public function connect(string $dataBaseName, string $host, string $user, string $password, array $options = [])
    {
        try {
            $this->pdo = new \PDO("mysql:dbname={$dataBaseName};host={$host}", $user, $password, $options);
            return $this;
        } catch (\Exception $e) {
            throw new \Exception("Fail on database connect: " . $e->getMessage());
        }

    }

    /**
     * Get PDO
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Disconnect
     *
     * @return void
     */
    public function disconnect()
    {
        $this->pdo = null;
        return;
    }
}