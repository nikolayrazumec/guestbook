<?php
session_start();
define('_CONTROL', 1);

include "db.php";

class Submit extends Db
{
    private $name;
    private $text;
    private $note;
    private $idmax;
    private $filename;

    public function __construct()
    {
        if (!empty($_POST['text']) && isset($_SESSION["name"])) {
            $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
            $this->name = trim(addslashes(html_entity_decode(strip_tags($_SESSION["name"]))));
            $this->text = trim(addslashes(html_entity_decode(strip_tags($_POST['text']))));
            $note = trim(addslashes(html_entity_decode(strip_tags(preg_replace("/[^1-5]/", "", $_POST['note'])))));
            $this->note = (empty($note[0])) ? 5 : $note[0];
            $res = $db->query("SELECT MAX(`id`) as `MAX` FROM `msgs` ");
            $row = $res->fetch_array(MYSQLI_NUM);
            $this->idmax = $row[0] + 1;
            $this->getFile();
            $res = $db->query("INSERT INTO `msgs`(`name`, `msg`, `note`, `datetime`,`filename`) VALUES ('$this->name','$this->text','$this->note',NOW(),'$this->filename')");
            if ($res == true) {
                echo "сообщение отправлено!!!";
            } else {
                echo "попробуйте снова отправить!!!";
            }

        }
    }

    public function getFile()
    {
        $uploaddir = '../uploads/';
        if (!is_dir($uploaddir)) mkdir($uploaddir, 0777);
        foreach ($_FILES as $file) {
            if ($file["type"] == "image/png"
                || $file["type"] == "image/jpg"
                || $file["type"] == "image/gif"
                || $file["type"] == "image/jpeg"
                || $file["type"] == "image/jpeg"
                && ($file["size"] < 20000)
            ) {
                $path_parts = pathinfo($file['name']);
                $path_parts['extension'];
                $this->filename = "{$this->idmax}.{$path_parts['extension']}";
                if (move_uploaded_file($file['tmp_name'], $uploaddir . $this->filename)) {
                    $files[] = realpath($uploaddir . $file['name']);
                } else {
                    die("проверьте формат файла, разрер не более 20мб!!!");
                }
            } else {
                die("проверьте формат файла, разрер не более 20мб!!!");
            }
        }
    }


}

$sub = new Submit();