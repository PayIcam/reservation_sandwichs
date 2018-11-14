<?php

class Possibility {
    public static function get_all($removed_too=true) {
        global $db;
        if($removed_too) {
            $sandwiches = $db->query('SELECT * FROM purchases_possibilities');
        } else {
            $sandwiches = $db->query('SELECT * FROM purchases_possibilities WHERE is_removed=0');
        }
        return $sandwiches;
    }

    public static function get_article_id($possibility_id) {
        global $db;
        return $db->queryFirst('SELECT article_id FROM purchases_possibilities WHERE possibility_id=:possibility_id', array('possibility_id' => $possibility_id));
    }
}

?>