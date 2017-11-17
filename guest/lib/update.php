<?php
session_start();
define('_CONTROL', 1);

include "db.php";

class Update extends Db
{
    private $name;
    private $text;
    private $note;
    private $idmax;
    private $filename;

    public function __construct()
    {
        if (!empty($_POST['text']) && isset($_SESSION["name"]) && isset($_SESSION["idchange"])) {
            $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
            $this->name = trim(addslashes(html_entity_decode(strip_tags($_SESSION["name"]))));
            $this->text = trim(addslashes(html_entity_decode(strip_tags($_POST['text']))));
            $note = trim(addslashes(html_entity_decode(strip_tags(preg_replace("/[^1-5]/", "", $_POST['note'])))));
            $this->note = (empty($note[0])) ? 5 : $note[0];
            $this->idmax = $_SESSION["idchange"];
            $res = $db->query("SELECT `filename` FROM `msgs` WHERE `id`='$this->idmax' limit 1");
            $get_total = $res->fetch_all(MYSQLI_ASSOC);
            $this->filename = $get_total[0]['filename'];

            if ($_POST['delit2'] == 0) {
                if ($_POST['delit'] == 1 && !empty($this->filename)) {
                    unlink('../uploads/' . $this->filename);
                    $this->filename = '';
                }
                $this->getFile();
                $res = $db->query("UPDATE `msgs` SET `msg`='$this->text',`note`='$this->note',`filename`='$this->filename' WHERE `id`='$this->idmax'");
            } else {
                $res = $db->query("DELETE FROM `msgs` WHERE `id`='$this->idmax'");
                if (!empty($this->filename)) {
                    unlink('../uploads/' . $this->filename);
                    $this->filename = '';
                }
            }
            if ($res == true) {
                echo "сообщение отредактировано!!!";
                echo '<script language="JavaScript">
                            function func() {
                                window.location.href = "/";
                                            }
                             setTimeout(func, 1500);
                      </script>';
            } else {
                echo "попробуйте снова отправить!!!";
            }
        }
    }

    public function getFile()
    {
        if (isset($_FILES["tax_file"])) {
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
}

$sub = new Update();