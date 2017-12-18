<?php

class EntityManager {

    private $config;
    private $connection;

    public function __construct($config) {
        $this->config = $config;

        if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['dbname'], $this->config['user'], $this->config['password']
            );
            $this->connection->setAttribute(
                    PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
            );
        }
    }

    public function findAll($entity, $order = null, $limit = null, $offset = null) {
        $stmt = $this->connection->prepare('SELECT * FROM ' . $entity
                . ($order ? ' ORDER BY ' . $order : '')
                . ($limit ? ' LIMIT ' . ($offset ? $offset . ',' : '') . $limit : ''));

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, $entity);
        return $stmt->fetchAll();
    }

    public function count($entity) {
        $stmt = $this->connection->query('SELECT COUNT(*) FROM ' . $entity);
        return $stmt->fetchColumn();
    }

    public function findBy($entity, $criteria) {

        $where = [];
        foreach ($criteria as $key => $value) {
            $where[] = '`' . $key . '`' . ' = :' . $key;
        }

        $stmt = $this->connection->prepare('SELECT * FROM ' . $entity
                . ' WHERE ' . implode(' AND ', $where));

        foreach ($criteria as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, $entity);
        return $stmt->fetch();
    }

    public function save($entity) {

        if (!empty($entity->id)) {
            return $this->update($entity);
        }

        $vars = get_object_vars($entity);

        if (array_key_exists('id', $vars)) {
            unset($vars['id']);
        }

        foreach ($vars as $key => $value) {
            if ($value === null) {
                unset($vars[$key]);
            }
        }

        $stmt = $this->connection->prepare('
            INSERT INTO ' . get_class($entity) . ' 
                (' . implode(',', array_keys($vars)) . ') 
            VALUES 
                (:' . implode(', :', array_keys($vars)) . ')
        ');

        foreach ($vars as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $result = $stmt->execute();

        $entity->id = $this->connection->lastInsertId();

        return $entity;
    }

    public function update($entity) {
        if (!isset($entity->id)) {
            // We can't update a record unless it exists...
            throw new LogicException('Cannot update entity that does not yet exist in the database.');
        }

        $vars = get_object_vars($entity);

        $id = $vars['id'];
        unset($vars['id']);

        foreach ($vars as $key => $value) {
            if ($value === null) {
                unset($vars[$key]);
            }
        }

        $set = [];
        foreach ($vars as $key => $value) {
            $set[] = $key . ' = :' . $key;
        }

        $stmt = $this->connection->prepare('
            UPDATE ' . get_class($entity) . '
            SET ' . implode(',', $set) . '
            WHERE id = :id
        ');

        foreach ($vars as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

}
