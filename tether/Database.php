<?php

namespace Tether;

class Database
{
    protected Config $config;
    protected static array $connections = [];
    protected string|bool $table = false;
    protected string $connection = 'default';
    protected string $primaryKey = 'id';
    protected string $fields = '*';
    
    public function __construct()
    {
        $this->config = new Config();
        
        $this->mapConnections();
    }

    public static function mapConnections(): void
    {
        if (Config::get('database.connections', false)) {
            $connections = Config::get('database.connections', false);

            foreach ($connections as $connection_name => $connection_values) {
                $dsn = "mysql:host={$connection_values['host']};dbname={$connection_values['database']};charset={$connection_values['charset']}";
                $options = [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                try {
                    self::$connections[$connection_name] = new \PDO($dsn, $connection_values['username'], $connection_values['password'], $options);
                } catch (\PDOException $e) {
                    throw new \PDOException($e->getMessage(), (int)$e->getCode());
                }
            }
        }
    }

    public static function pluralize($quantity, $singular, $plural=null) {
        if($quantity==1 || !strlen($singular)) return $singular;
        if($plural!==null) return $plural;

        $last_letter = strtolower($singular[strlen($singular)-1]);
        
        return match ($last_letter) {
            'y' => substr($singular, 0, -1) . 'ies',
            's' => $singular . 'es',
            default => $singular . 's',
        };
    }
    
    public function select($fields = '*'): static
    {
        if (! $fields) return $this;
        
        if (is_array($fields)) {
            $fields = join(',', $fields);
        }
        
        $this->fields = $fields;
        
        return $this;
    }
    
    public function get()
    {
        $query = "SELECT " . $this->fields . " FROM " . $this->table;
        
        $results = self::$connections[$this->connection]->prepare($query);
        
        $results->execute();
        
        return $results->fetchAll();
    }
}