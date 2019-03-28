<?php
class Statistic 
{
    private
    $_count = '';

    public function built($name,$listedItem = array(), $identifier = array(), $condition = '',$limit = '',$page = '',$tool = ''){
        echo "<h3 class='m-2'>" . $name . ": " . $this->getCount($this->ArrayProcessor($listedItem),$identifier,$condition) . "</h3>" ;
        echo "<hr>";
        if (!($this->getCount($this->ArrayProcessor($listedItem),$identifier,$condition) > 0)) {
            echo "<h3 class='m-5 text-center text-muted'>No Records</h3>";
        }
        else {
            echo $this->header($listedItem,$tool);
            echo $this->body($this->ArrayProcessor($listedItem),$identifier,$condition,$tool,$page,$limit);
        }
    }

    public function getCount($listedItem,$identifier,$condition) {
        if ($this->_count == '') {
            $db = DB::run();
            $db->jointTable($listedItem, $identifier, $condition);
            $this->_count = $db->getCount();
        }
        return $this->_count;
    }

    public function getPageCount($limit){
        return ceil($this->_count / $limit);
    }

    public function header($listedItem,$tool){
        $output = "<table class='table'>
        <thead class='table-striped'>
        <tr>";
        foreach ($listedItem as $table => $properties) {
            foreach ($properties as $property => $headerName) {
                $output .= "<th scope>$headerName</th>";
            }
        }
        if (strlen($tool) > 0) {
            $output .= "<th scope>Tools</th>";
        }
        $output .= "</tr>";
        return $output;
    }

    public function ArrayProcessor($listedItem){
        $output = array();
        foreach ($listedItem as $firstRow => $SecondRow) {
            $output[$firstRow] = array();
            $val = array();
            foreach ($SecondRow as $row => $value) {
                array_push($val,$row);
            }
            $output[$firstRow] = $val;
            // $output[$firstRow] = $SecondRow;
        }
        return $output;

    }

    public function body($listedItem,$identifier,$condition,$tool = '',$page = '',$limit = ''){
        $output = '';
        $db = DB::run();
        $additionalCondition = '';
        if ($page != '' && $limit != '') {
            $offset    = (--$page * $limit);
            $additionalCondition = " LIMIT $limit OFFSET $offset";
        }
        $db->jointTable($listedItem, $identifier, $condition . $additionalCondition);
        // var_dump($db->getResult());
        $leaker = '';
        foreach ($db->getResult() as $index => $items) {
        $output .= "<tr>";
        foreach ($items as $value) {
            $value = (strlen($value) > 50) ? Message::StringOverFlow($value, 30) : $value;
            $value = ($value == '')? "Nothing" : $value;
            $output .= "<td scope='col'>$value</td>";
            $leaker = $items;
        }
        // var_dump($leaker);

        if (strlen($tool) > 0) {
            switch ($tool) {
                case 'apply':
                $output .= "<td scope='col'><a class='btn btn-primary' href='apply.php?apply=$leaker->apply_id'>View</a></td>";
                    break;
                case 'user':
                $output .= "<td scope='col'><a class='btn btn-primary' href='reportViewUser.php?report=$leaker->report_id'>View</a></td>";
                    break;
                case 'post':
                $output .= "<td scope='col'><a class='btn btn-primary' href='reportViewPost.php?report=$leaker->report_id'>View</a></td>";
                    break;
                case 'ban':
                $usr_id = new User($leaker->usrnm);
                $usr_id = $usr_id->getData()->usr_id;
                $output .= "<td scope='col'><a class='btn btn-primary' href='viewBan.php?ban=$leaker->ban_id&user=$usr_id&choice=Unban'>Unban</a>
                <a class='btn btn-primary' href='viewBan.php?ban=$leaker->ban_id'>View</a></td>";
                    break;
                default:
                    # code...
                    break;
            }
        }
        
        $output .= "</tr>";
        
    }
    $output .= "</table>";
    return $output;
}
}