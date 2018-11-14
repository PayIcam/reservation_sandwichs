<?php

class Sandwich {
    public $sandwich_id;
    public $name;
    public $default_quota;
    public $description;
    public $is_removed;

    public function __construct($sandwich_id) {
        global $db;
        $this->bind($db->queryFirst('SELECT * FROM sandwiches WHERE sandwich_id = :sandwich_id', array('sandwich_id' => $sandwich_id)));
    }

    public static function get_all($removed_too=true) {
        global $db;
        if($removed_too) {
            $sandwiches = $db->query('SELECT * FROM sandwiches');
        } else {
            $sandwiches = $db->query('SELECT * FROM sandwiches WHERE is_removed=0');
        }
        return $sandwiches;
    }

    public static function insert($sandwich) {
        global $db;
        $db->query('INSERT INTO sandwiches(name, default_quota, description) VALUES (:name, :default_quota, :description)', array("name" => $sandwich['name'], "default_quota" => $sandwich['default_quota'], "description" => $sandwich['description']));
    }
    public static function update($sandwich) {
        global $db;
        $db->query('UPDATE sandwiches SET name=:name, default_quota=:default_quota, description=:description WHERE sandwich_id=:sandwich_id', array("sandwich_id" => $sandwich['sandwich_id'], "name" => $sandwich['name'], "default_quota" => $sandwich['default_quota'], "description" => $sandwich['description']));
    }

    public function toggle() {
        if($this->is_removed==1) {
            return $this->restore();
        } else {
            return $this->delete();
        }
    }

    private function delete() {
        global $db;
        //check whether sandwich was sold already at least once
        if($db->queryFirst('SELECT COUNT(*) FROM day_has_sandwiches r WHERE sandwich_id =:sandwich_id', array('sandwich_id' => $this->sandwich_id))['COUNT(*)']==0) {
            $db->query('DELETE FROM sandwiches WHERE sandwich_id=:sandwich_id', array('sandwich_id' => $this->sandwich_id));
            return json_encode(array('message' => 'Le sandwich a été totalement supprimé', 'sandwich_id' => $this->sandwich_id));

        } else {
            $db->query('UPDATE sandwiches SET is_removed=1 WHERE sandwich_id=:sandwich_id', array('sandwich_id' => $this->sandwich_id));
            return json_encode(array('message' => 'Le sandwich a bien été supprimé'));

        }
    }
    private function restore() {
        global $db;
        $db->query('UPDATE sandwiches SET is_removed=0 WHERE sandwich_id=:sandwich_id', array('sandwich_id' => $this->sandwich_id));
        return json_encode(array('message' => 'Le sandwich a bien été restauré'));
    }

    protected function bind($sandwich) {
        $this->sandwich_id = $sandwich['sandwich_id'];
        $this->name = $sandwich['name'];
        $this->default_quota = $sandwich['default_quota'];
        $this->description = $sandwich['description'];
        $this->is_removed = $sandwich['is_removed'];
    }

    public static function display_reservation_table_row($sandwich, $possibilities) {
        if($sandwich['current_quota'] < $sandwich['quota']) {
            ?>
            <tr class="text-center" data-sandwich_id="<?=$sandwich['sandwich_id']?>">
                <th scope="row">
                    <?=$sandwich['name']?>
                    <?php if(!empty($sandwich['description']))
                        echo '<button type="button" class="btn btn-sm" data-toggle="popover" data-content="' . $sandwich['description'] . '"><span class="oi oi-question-mark"></span></button>'; ?>
                </th>
                <?php foreach($possibilities as $possibility) { ?>
                <td class="text-center">
                    <button data-possibility_id="<?=$possibility['possibility_id']?>" class="reservation btn btn-primary"> Réserver </button>
                </td>
                <?php } ?>
            </tr>
        <?php } else { ?>
            <tr class="text-center">
                <th scope="row">
                    <?=$sandwich['name']?>
                    <?php if(!empty($sandwich['description']))
                        echo '<button type="button" class="btn btn-sm" data-toggle="popover" data-content="' . $sandwich['description'] . '"><span class="oi oi-question-mark"></span></button>'; ?>
                </th>
                <?php foreach($possibilities as $possibility) { ?>
                <td class="text-center">
                    <button class="reservation btn btn-primary button_disabled" disabled title="Le quota est déjà complet :("> Réserver </button>
                </td>
                <?php } ?>
            </tr> <?php
        }
    }
}

?>