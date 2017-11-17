<?php
session_start();
define('_CONTROL', 1);
include "db.php";


class Pages extends Db
{
    public $page_number;
    public $item_per_page = 3;
    public $total_pages;
    public $get_total_rows;

    function __construct()
    {
        if (isset($_POST["page"])) {
            $this->page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
            if (!is_numeric($this->page_number)) {
                die('Invalid page number!');
            }
        } else {
            $this->page_number = 1;
        }
        $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        $res = $db->query("SELECT COUNT(`id`) AS `cnt` FROM `msgs`");
        $this->get_total_rows = $res->fetch_array(MYSQLI_ASSOC);
        $this->total_pages = ceil($this->get_total_rows["cnt"] / $this->item_per_page);
        if (!$this->total_pages > 0) {
            die;
        }
        $this->getPage();
        $db->close();
    }

    public function getPage()
    {
        $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        $page_position = (($this->page_number - 1) * $this->item_per_page);
        $res1 = $db->query("SELECT `id`, `name`, `msg`, `note`, `datetime`, `filename` FROM `msgs` ORDER BY `id` DESC LIMIT $page_position, $this->item_per_page");
        $get_total = $res1->fetch_all(MYSQLI_ASSOC);
        $db->close();

        echo '
    <div class="container">
            <div class="row">';

        foreach ($get_total as $value) {

            echo '
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">';
            if (!empty($value['filename'])) {
                //<img id="myImg" src="uploads/' . $value['filename'] . '" class="img-rounded" alt="' . $value['name'] . '" height="150"  >
                echo '<img id="myImg" src="uploads/' . $value['filename'] . '" class="img-rounded" alt="' . $value['name'] . '">';
            }
            echo '
                    <div class="caption">
                        <h4>' . $value['name'] . '</h4>
                        <p>' . $value['datetime'] . '</p>
                        <p>' . $value['msg'] . '</p>';
            if ($value['name'] == $_SESSION['name']) {
                echo ' <p> <a href="./change.php?id=' . $value['id'] . '"class="btn btn-primary" role="button">Редактировать</a></p>';
            }
            echo '     <p> <ul class="webwidget_rating_sex">';
            $i=0;
            do{
                echo '<li style="background-image: url(&quot;assets/images//web_widget_star.gif&quot;); background-position: 0px -28px;"></li>';
                $i++;
            }while($i<$value['note']);
                         echo '</ul></p>
                    </div>
                </div>
            </div>
            ';
        }
        echo "</div>
</div>
    <div align=\"center\">
        {$this->outputPag($this->page_number, $this->total_pages)};
    </div>";
    }

    public function outputPag($current_page, $total_pages)
    {
        $pagination = '';
        if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) {
            $pagination .= '<ul class="pagination">';

            $right_links = $current_page + 3;
            if ($current_page > 4) {
                $previous = $current_page - 3;
            } else {
                $previous = $current_page - 1;
            }
            $first_link = true;

            if ($current_page > 1) {
                $previous_link = ($previous == 0) ? 1 : $previous;
                $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>';
                $pagination .= '<li><a href="#" data-page="' . $previous_link . '" title="Previous">&lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) {
                    if ($i > 0) {
                        $pagination .= '<li><a href="#" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
                    }
                }
                $first_link = false;
            }

            if ($first_link) {
                $pagination .= '<li class="first active"><a href="#" >' . $current_page . '</a></li>';
            } elseif ($current_page == $total_pages) { //if it's the last active link
                $pagination .= '<li class="last active"><a href="#" >' . $current_page . '</a></li>';
            } else {
                $pagination .= '<li class="active"><a href="#" >' . $current_page . '</a></li>';
            }

            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li><a href="#" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
                }
            }
            if ($current_page < $total_pages) {
                $next_link = ($i > $total_pages) ? $total_pages : $i;
                $pagination .= '<li><a href="#" data-page="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
                $pagination .= '<li class="last"><a href="#" data-page="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
            }

            $pagination .= '</ul>';
        }
        return $pagination;
    }
}

$x = new Pages();