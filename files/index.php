<?php

require('includes/header.php');

require('includes/database_connect.php');

# This is an admin logged in here, so go ahead and list out all of the serial numbers and recovery keys

$query="SELECT recovery_key, serial, comp_hostname, fv_users, timestamp, recovery_id
FROM recovery_keys
ORDER BY timestamp DESC";
$result=mysqli_query($dbc, $query);
$count=mysqli_num_rows($result);
if($count>0){

   echo '<table id="inventory">
   <thead>
   <tr>
   <th>Date Added</th>
   <th>Recovery Key</th>
   <th>Serial Number</th>
   <th>Comp. Hostname</th>
   <th>FileVault Users</th>
   </tr>
   </thead>
   <tbody>';

      while($row=mysqli_fetch_array($result)){
         echo '<tr><td>' . date("m/d/y H:i:s", $row['timestamp']) . '</td><td>' . $row['recovery_key'] . '</td><td>' . $row['serial'] . '</td><td>' . $row['comp_hostname'] . '</td><td>' . $row['fv_users'] . '</td></tr>';
      }

   echo '</tbody></table>' . "
   <script>
   $(document).ready( function () {
       $('#inventory').DataTable( {
           dom: 'Bfrtip',
           buttons: [
               {
                   extend: 'copyHtml5',
                   exportOptions: {
                    columns: ':contains(\"Office\")'
                   }
               },
               'excelHtml5',
               'csvHtml5',
               'pdfHtml5'
           ]
       } );
       $('div.dataTables_filter input').focus();
   } );
   </script>";


} else {

   echo '<div class="error">There are no recovery keys in the database yet.</div>';

}


include('includes/database_disconnect.php');

include('includes/footer.php');

?>