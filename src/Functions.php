<?php

class Functions {
    public static function display_percentage_style($pourcentage) {
        switch ($pourcentage) {
            case ('undefined'):
                echo 'table-danger';
                break;
            case ($pourcentage<25):
                echo 'table-danger';
                break;
            case ($pourcentage<50):
                echo 'table-warning';
                break;
            case ($pourcentage<75):
                echo 'table-info';
                break;
            case ($pourcentage<100):
                echo 'table-primary';
                break;
            case ($pourcentage=100):
                echo 'table-success';
                break;
        }
    }

    public static function pourcentage_extended_zero_division($a, $b) {
        if($b==0)
            return 0;
        return round(100*$a/$b, 2);
    }

    public static function flash($message, $type, $location=false) {
        $_SESSION['alerts'][] = ['message' => $message, 'type' => $type];
        if($location) {
            header('Location: ' . $location);
            die();
        }
    }
    public static function display_alerts() {
        foreach($_SESSION['alerts'] as $alert) {
            echo '<div class="alert alert-' . $alert['type'] . ' alert-dismissible">' . '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $alert['message'] . '</div>';
        }
    }
}