<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class ConsistentHashingController extends Controller
{
    private $ring = [];
    private $nodes = [];
    private $replicas = 3; // Virtual nodes for better distribution

    public function __construct($replicas = 3) {
        $this->replicas = $replicas;
    }


    function index()  {
        

            $ch = new ConsistentHashingController();
            $ch->addNode("Server1");
            $ch->addNode("Server2");
            $ch->addNode("Server3");

            // echo "Key 'User123' is assigned to: " . $ch->getNode("User123") . "\n <br>";
            // echo "Key 'User456' is assigned to: " . $ch->getNode("User456") . "\n <br>";

            // $ch->removeNode("Server2");

            // echo "After removing Server2, Key 'User123' is assigned to: " . $ch->getNode("User123") . "\n";


            echo "<br>=======================</br>";
            echo "<pre>";            
            //  print_r($ch->ring);
            // print_r($ch->nodes);
            // print_r($ch->replicas);

            print_r($ch->getNode("User123"));
            // print_r($ch->getNode("User456"));
            // print_r($ch->getNode("Server1"));       
    }

    // Add a node to the hash ring
    public function addNode($node) {
        for ($i = 0; $i < $this->replicas; $i++) {
            $hash = $this->hash($node . $i);
            $this->ring[$hash] = $node;
            $this->nodes[] = $hash;
        }
        sort($this->nodes); // Keep the ring sorted
    }

    // Remove a node from the hash ring
    public function removeNode($node) {
        for ($i = 0; $i < $this->replicas; $i++) {
            $hash = $this->hash($node . $i);
            unset($this->ring[$hash]);
            $index = array_search($hash, $this->nodes);
            if ($index !== false) {
                unset($this->nodes[$index]);
            }
        }
        $this->nodes = array_values($this->nodes); // Reindex array
    }

    // Get the node for a given key
    public function getNode($key) {
        if (empty($this->ring)) {
            return null; // No nodes available
        }


        print_r($this->ring);


        $hash = $this->hash($key);
        
        // Find the closest node in the ring
        foreach ($this->nodes as $nodeHash) {
            if ($hash <= $nodeHash) {
                return $this->ring[$nodeHash];
            }
        }

        // If no node found, return the first node (circular behavior)
        return $this->ring[$this->nodes[0]];
    }

    // Hash function (simple MD5-based integer hash)
    private function hash($str) {
        return crc32($str);
    }
}



