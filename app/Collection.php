<?php
namespace App;

// Classe Collection qui implémente les interfaces IteratorAggregate et Countable
// Permet de créer des collections d'objets ou de données avec des fonctionnalités standards de parcours et de comptage.
class Collection implements \IteratorAggregate, \Countable {

    // Tableau contenant les éléments de la collection
    private array $items;

    // Constructeur : initialise la collection avec un tableau d'éléments (vide par défaut)
    public function __construct(array $items = []) {
        $this->items = $items;
    }

    // Méthode de l'interface IteratorAggregate
    // Retourne un objet ArrayIterator permettant de parcourir les éléments avec foreach
    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->items);
    }

    // Méthode de l'interface Countable
    // Retourne le nombre d'éléments dans la collection
    public function count(): int {
        return count($this->items);
    }
}
