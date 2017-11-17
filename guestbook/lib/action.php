<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
define('_CONTROL', 1);

include "db.php";

class Login extends Db
{
    protected $name = '';
    protected $pass = '';
    protected $email = '';

    function __construct()
    {
        if (!empty($_POST["name"] && !empty($_POST["pass"]))) {
            session_start();
            $this->pass = addslashes(html_entity_decode(strip_tags(preg_replace('/[^\w]*/Uuis', '', $_POST["pass"]))));
            $this->name = addslashes(html_entity_decode(strip_tags(preg_replace('/[^\w]*/Uuis', '', $_POST["name"]))));
            if (empty($this->pass) || empty($this->name)) {
                die("проверьте правильность формы");
            }
            $this->getPage();
        }

    }

    public function getPage()
    {
        $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        if (!empty($_POST["email"])) {
            $this->email = addslashes(html_entity_decode(strip_tags(preg_replace('/[^\w\@\.]*/Uuis', '', $_POST["email"]))));
            preg_match('/^\w+\@\w+\.\w+$/Uuis', $this->email, $aem);
            $this->email = $aem[0];
            if (empty($this->email)) {
                die("<h4>Email должен быть вида:email@email.com</h4>");
            }
            $res = $db->query("SELECT `id`, `name`, `pass`, `email`, `admin` FROM `guest` WHERE `name`='$this->name'");
            $query = $res->num_rows;
            if ($query) {
                die("<h4>данное имя уже занято!!!</h4>");
            } else {
                $res = $db->query("INSERT INTO `guest`( `name`, `pass`, `email`) VALUES ('$this->name','$this->pass','$this->email')");
                if (!$res) {
                    die("<h4>что-то пошло не так, попробуйте снова!!!</h4>");
                } else {
                    $_SESSION["name"] = $this->name;
                }
            }
        } else {
            $res = $db->query("SELECT `id`, `name`, `pass`, `email`, `admin` FROM `guest` WHERE `name`='$this->name' AND `pass`='$this->pass'");
            $query = $res->num_rows;
            if (!$query) {
                die("<h4>проверьте правильность формы!!!</h4>");
            } else {
                $query = $res->fetch_all(MYSQLI_ASSOC);
                echo $_SESSION["name"] = $this->name;
                if (!empty($query[0]["admin"])) {
                    $_SESSION["admin"] = $this->name;
                }
            }
        }
        //exit();
        echo '
            <script language="JavaScript">
                window.location.href = "/"
            </script>';
    }
}

$x = new Login();
