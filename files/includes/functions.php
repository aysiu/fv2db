<?php

// Checks that the supplied email address belongs in the array of approved domains
function email_domain_check($test_email, $domain_array){

   // Make sure the array is an array and that the array isn't empty
   if((!is_array($domain_array)) OR (empty($domain_array))){

      // Might as well return true, because we can't really evaluate with an empty array or non-array
      return true;

   } else {

      // Set a test variable
      $domain_okay=0;

      foreach($domain_array AS $approved_domain){

         // Add in an @ sign
         $domain_to_check='@' . $approved_domain;
   
         if((strpos($test_email, $domain_to_check)) AND ($domain_okay==0)){
            $domain_okay=1;
            break;
         // End checking the domain is okay
         }
      // End looping through approved domains
      }

      // Return whether the domain checks out or not
      if($domain_okay==1){
         return true;
      } else {
         return false;
      } 

   // End checking not empty array
   }

// End function
}
?>