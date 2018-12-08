<?php

class Possibility {
    public static function get_all($removed_too=true) {
        global $db;
        if($removed_too) {
            $classics = $db->query('SELECT * FROM purchases_possibilities WHERE is_special=0');
            $specials = $db->query('SELECT * FROM purchases_possibilities WHERE is_special=1');
        } else {
            $classics = $db->query('SELECT * FROM purchases_possibilities WHERE is_special=0 and is_removed=0');
            $specials = $db->query('SELECT * FROM purchases_possibilities WHERE is_special=1 and is_removed=0');
        }
        return ['classics' => $classics, 'specials' => $specials];
    }

    public static function get_article_id($possibility_id) {
        global $db;
        return $db->queryFirst('SELECT article_id FROM purchases_possibilities WHERE possibility_id=:possibility_id', array('possibility_id' => $possibility_id))['article_id'];
    }

    public static function can_book_possibility($closure_type, $day) {
        if($closure_type==0) {
            return strtotime($day['reservation_second_closure_date']) - time() >0;
        } else {
            return strtotime($day['reservation_first_closure_date']) - time() >0;
        }
    }

}

?>