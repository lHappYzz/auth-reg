<?php


class exceldoc
{
    private $filename;

    public function __construct() {
        $this->filename = 'Registered users on ' . date('Y-m-d H:i:s', time());
    }

    function exportUsers($usersList) {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='. $this->filename .'.xls');
        $formatedUsersList = '';
        foreach ($usersList as $user){
            $formatedUsersList .= "<tr>" .
                            "<td>" .
                                $user['id']
                          . "</td>".
                            "<td>" .
                                $user['email']
                          . "</td>".
                            "<td>" .
                                $user['password']
                          . "</td>"
                    . "</tr>";
        }
        echo "
            <table>
                <tr>
                    <th>Id</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
                ". $formatedUsersList ."
            </table>
            ";
        exit;
    }
}