<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "todo_lists";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Error: ". mysqli_connect_error($conn));
}


// $sql = "INSERT INTO todo_items (title, dsc) VALUES ('This is title', 'This is description')";
// $result = mysqli_query($conn, $sql);

// $sql = "SELECT * FROM todo_items";
// $result = mysqli_fetch_assoc($conn, $sql);


// if (!$result) {
//     die("Get all query failed". mysqli_error($result));
// }
// echo "OK";




// if (!$conn) {
//     echo '
// <div class="container">
//     <div class="alert alert-primary" role="alert">
//         <p>
//             We are facing some troubles! This will be fix soon. We are working on it. <br>
//             Thank you for your support.<br>
//             Failed to connect to database' . mysqli_connect_error() . '<br>
//         </p>
//     </div>
// </div>
// ';
// }

class SQL_DB
{
    public function __construct($hostname, $username, $password, $database)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->conn = new mysqli($hostname, $username, $password, $database);

        if ($this->conn->connect_error) {
        echo '
        <div class="container">
            <div class="alert alert-primary" role="alert">
                <p>
                    We are facing some troubles! This will be fix soon. We are working on it. <br>
                    Thank you for your support.<br>
                    Failed to connect to database' . $this->conn->connect_error . '<br>
                </p>
            </div>
        </div>
        ';
        }
    }

    public function close() : void
    {
        $this->conn->close();
    }

    public function create($title, $description)
    {
        $sql = "INSERT INTO todo_items (title, dsc) VALUES ('$title', '$description')";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Create query failed". $this->conn->error);
        }
        return true;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM todo_items WHERE id='$id'";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Delete query failed". $this->conn->error);
        }
        return true;
    }

    public function update($title, $description, $id)
    {
        $sql = "UPDATE todo_items SET title='$title', dsc='$description' WHERE id=$id";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Update query failed". $this->conn->error);
        }
        return true;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM todo_items";
        $result = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        if (!$result) {
            die("Get all query failed". $this->conn->error);
        }
        return $result;
    }

}

function get_database() {
    return new SQL_DB("localhost", "root", "", "todo_lists");
}

