<?php

namespace App\Helpers;

class DatabaseSharding {
    private $ring = [];
    private $nodes = [];
    private $databases = ["mysql1", "mysql2", "mysql3"];
    private $replicas = 100;

    public function __construct() {
        foreach ($this->databases as $db) {
            for ($i = 0; $i < $this->replicas; $i++) {
                $hash = $this->hash($db . $i);
                $this->ring[$hash] = $db;
                $this->nodes[] = $hash;
            }
        }
        sort($this->nodes);
    }

    public function getDatabase($key) {
        $hash = $this->hash($key);
        foreach ($this->nodes as $nodeHash) {
            if ($hash <= $nodeHash) {
                return $this->ring[$nodeHash];
            }
        }
        return $this->ring[$this->nodes[0]];
    }

    private function hash($str) {
        return crc32($str);
    }
}
