<?php

require_once 'db_connect.php';

$output = array('data' => array());

$sql = "SELECT * FROM members";
$query = $connect->query($sql);

$x = 1;
while ($row = $query->fetch_assoc()) {
    $active = '';
    if ($row['active'] == 1) {
        $active = '<label class="label label-success">Active</label>';
    } else if ($row['active'] == 2) {
        $active = '<label class="label label-primary">Pending</label>';
    } else {
        $active = '<label class="label label-danger">Closed</label>';
    }

    $actionButton = '
	<div class="btn-group">
	  <button type="button" class="btn btn-gray dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Action <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	    <li><a style="cursor:pointer;" type="button" data-toggle="modal" data-target="#editMemberModal" onclick="editMember(' . $row['id'] . ')"> <span class="fa fa-pencil"></span> Edit</a></li>
	    <li><a style="cursor:pointer;" type="button" data-toggle="modal" data-target="#removeMemberModal" onclick="removeMember(' . $row['id'] . ')"> <span class="fa fa-trash"></span> Remove</a></li>	    
	  </ul>
	</div>
		';
    $image = '<img src=' . $row['image'] . ' style="width:40px;">';


    $url = $row['messanger_link'];
    $chat = "<a href=\"javascript:window.open('$url','Social Login','width=800,height=600,top=150,left=200')\"><img src='assests/image/messanger.png' style='width:30px;'></a>";


    $output['data'][] = array(
        $x,
        $image,
        $chat,
        $row['name'],
        $row['fb_link'],
        $row['address'],
        $row['contact'],
        $active,
        $actionButton
    );
    $x++;
}
// database connection close
$connect->close();

echo json_encode($output);
