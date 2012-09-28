<?php
require_once('./twilio-php/Services/Twilio.php');
$AccountSid = "ACb4ad35d8c538f65ceabcb8253c704909";
$AuthToken = "a763bf40d023bfed087d86a8ca3fb212";
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);
/* Your Twilio Number or Outgoing Caller ID */

                  $people = array(
                          "7347470243" => "Vasu",
                                              );



              
$from = '7342742961';
//$to ='8476444551';
//$to ='7347470243';
              
        	                                                                    $body ="You've got a new photo!\nwww.photosharingnetwork.com/d.php?f=410\nClick on the link above to view.";
  //                                   $client->account->sms_messages->create($from, $to, $body);
            /*
            foreach ($people as $to => $name) {
                     // Send a new outgoing SMS 
                             $body = "Bad news $name, the server is down and it needs your help";
                                     $client->account->sms_messages->create($from, $to, $body);
                                             echo "Sent message to $name";
                                                 }*/
