<?php

namespace Tether;

use Tether\Facade\Str;

class Database
{
    protected static array $connections = [];
    protected string|bool $table = false;
    protected string $connection = 'default';
    protected string $primaryKey = 'id';
    protected string $fields = '*';
    protected static array $where = [];
    
    public function __construct(protected Config $config) {}

    public static function mapDatabaseConnections(): void
    {
        if ($connections = Config::get('database.connections', false)) {
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
                    throw new \PDOException('Could not establish database connection', (int)$e->getCode());
                }
            }
        }
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
    
    public static function where($key, $value)
    {
        self::$where[$key] = $value;
        
        return new (static::class)();
    }
    
    public function first()
    {
        return $this->limit(1)->get(false);
    }
    
    public function stringifyWhere()
    {
        if (count(self::$where) === 0) return '';
        
        $string = 'WHERE';
        
        foreach (self::$where as $key => $value) {
            $string .= ' `' . $key . '` = \'' . $value . '\'';
        }
        
        return $string;
    }
    
    public function limit($limit)
    {
        return $this;
    }
    
    public function get($collect = true)
    {
        $query = "SELECT " . $this->fields . " FROM " . $this->table . ' ' . $this->stringifyWhere();
        
        $results = self::$connections[$this->connection]->prepare($query);
        
        $results->execute();
        
        $results = $results->fetchAll();
        
        if (! $collect) {
            return $results[0];
        }
        
        return new Collection(array_map(function ($result) {
            return (new (static::class))->fill($result);
        }, $results));
    }
}