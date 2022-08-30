<?php
    function num_pages($query,$results_per_page){

        include('database/conn.php');

        $result = mysqli_query($conn,$query);
        $number_of_results = mysqli_num_rows($result);

        return $number_of_pages = ceil($number_of_results/$results_per_page);
    }

    function first_page($results_per_page){

        if(!isset($_GET['page'])){
            $page = 1;
        }
        else{
            $page = $_GET['page'];
        }

        $this_page_first_result = ($page-1) * $results_per_page;

        return $this_page_first_result;
        
    }
?>