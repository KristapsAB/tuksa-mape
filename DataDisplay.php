<?php
class DataDisplay {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "viesnica");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getData($selectedData) {
        switch ($selectedData) {
            case 'bookings':
                $sql = "SELECT * FROM booking WHERE ires_sakums < '2023-09-09'";
                break;
            case 'bookings_future':
                $sql = "SELECT * FROM booking WHERE ires_beigas > '2023-09-09'";
                break;
            case 'bookings_today':
                $sql = "SELECT * FROM booking WHERE DATE(ires_sakums) = CURDATE()";
                break;
            case 'hotels':
                $sql = "SELECT nosaukums, apraksts FROM hotel";
                break;
            case 'workers':
                $sql = "SELECT Workers.*, hotel.nosaukums as hotel_nosaukums
                        FROM Workers
                        JOIN hotel ON Workers.hotel_id = hotel.hotel_id
                        WHERE hotel.nosaukums = 'Viesnīca NR1'";
                break;
            case 'user':
                $sql = "SELECT vards, uzvards FROM user";
                break;
            case 'rooms':
                $sql = "SELECT * FROM rooms WHERE pieejamas_istabas > 5";
                break;
            case 'payment':
                $sql = "SELECT summa, datums FROM payment";
                break;
            case 'rooms_hotels':
                $sql = "SELECT Rooms.tips, hotel.nosaukums as hotel_nosaukums
                        FROM Rooms
                        JOIN hotel ON Rooms.hotel_id = hotel.hotel_id";
                break;
            case 'workers_birthdays':
                $sql = "SELECT vards, uzvards, dzimsanas_datums FROM workers WHERE dzimsanas_datums IS NOT NULL";
                break;
            case 'booking_rooms_users_payments':
                $sql = "SELECT Booking.booking_id, Rooms.tips as numura_tips, user.vards, user.uzvards
                        FROM Booking
                        JOIN rooms ON Booking.room_id = rooms.room_id
                        JOIN user ON Booking.lietotaja_id = user.lietotaja_id
                        JOIN payment ON Booking.booking_id = payment.booking_id";
                break;
            case 'bookings_between_dates':
                $sql = "SELECT * FROM booking WHERE ires_sakums BETWEEN '2023-09-09' AND '2023-10-09'";
                break;
            case 'rooms_available':
                $sql = "SELECT tips, pieejamas_istabas FROM rooms WHERE pieejamas_istabas > 2";
                break;
            case 'hotels_within_price_range':
                $sql = "SELECT nosaukums FROM hotel WHERE ires_maksa BETWEEN 200.00 AND 400.00";
                break;
            case 'payments_above_100':
                $sql = "SELECT summa FROM payment WHERE summa > 100.00";
                break;
            case 'hotel_tips_viesnica_nr1':
                $sql = "SELECT tips FROM hotel WHERE nosaukums = 'Viesnīca NR1'";
                break;
            default:
                echo "Invalid selection.";
                return;
        }

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $this->displayData($result, $selectedData);
        } else {
            echo "No results found.";
        }
    }

    private function displayData($result, $selectedData) {
        echo '<div class="data-section">';
        echo "<h3>{$this->getTitle($selectedData)}</h3>";
        echo "<table>";
        $row = $result->fetch_assoc();

        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<th>{$key}</th>";
        }
        echo "</tr>";

        while ($row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
            $row = $result->fetch_assoc();
        }

        echo "</table>";
        echo '</div>';
    }

    private function getTitle($selectedData) {
        switch ($selectedData) {
            case 'bookings':
                return 'Booking Data';
            case 'hotels':
                return 'Hotel Information';
            case 'workers':
                return "Workers at 'Viesnīca NR1'";
            case 'user':
                return 'User Data';
            case 'rooms':
                return 'Rooms with More than 5 Available';
            case 'payment':
                return 'Payment Data';
            default:
                return '';
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}
?>
