<?php

// See if the database is already disconnected
if((isset($dbc)) AND ($dbc!='')){

  // Disconnect from the database
   mysqli_close($dbc);
   $dbc='';

   }

?>