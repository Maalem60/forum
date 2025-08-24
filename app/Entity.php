<?php
namespace App;

abstract class Entity {

    protected function hydrate($data) {
        if (!is_array($data)) {
            return; // sécurise contre null ou non-tableau
        }

        foreach ($data as $field => $value) {
            // Gestion des relations *_id
            $fieldArray = explode("_", $field);

            if (isset($fieldArray[1]) && $fieldArray[1] === "id") {
                $manName = ucfirst($fieldArray[0]) . "Manager";
                $FQCName = "Model\\Managers\\" . $manName;

                if (class_exists($FQCName)) {
                    $man = new $FQCName();
                    $value = $man->findOneById($value);
                }
            }

            // Fabrication du setter
            $method = "set" . ucfirst($fieldArray[0]);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function __construct($data = []) {
        if (is_array($data) && !empty($data)) {
            $this->hydrate($data);
        }
    }

    public function getClass() {
        return get_class($this);
    }
}
