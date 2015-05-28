<?php

include "simple_html_dom.php";





    $dir = '/var/www/threads/';
//echo $dir;
    $files1 = scandir($dir);

//boolean flag to determine when to start parsing
    $flag = 0;


    $string = "";
//Iterate through all messages
    for ($i = 2; $i < sizeof($files1); ++$i) {
        try {
            $html = file_get_html($dir . $files1[$i]);
            $flag = false;

            $threadID = ""; //ID for URL of thread
            $month = ""; //month post was made
            $day = ""; // day post was made
            $year = ""; // year of post
            $time_hour = ""; //hour post was made
            $time_minute = ""; //minute post was made
            $morning_night = ""; //either AM or PM
            $post_count_in_thread = "";
            $username = "";
            $join_date_month = "";
            $join_date_year = "";
            $message = ""; //users entire post input area

            $junk_var = ""; //for string manipulation for parsing

//            echo $files1[$i];

            foreach ($html->find('.tborder') as $es) {
                if (preg_match("/Bookmarks/", $es))
                    $flag = false;
//

//            if($flag == true)
//            echo '<br/>'. $es->plaintext . '<br/>';
                $string = $es->plaintext;
//            echo $string.'<br/>';
                if ($flag == true) {
                    list($threadID) = (explode('.', $files1[$i]));
                    list($month, $day, $year) = (explode('-', $string));
                    list($year, $junk_var) = (explode(',', $year));
//                echo $month. " " . $day . " " .$year;
                    list($time_hour, $junk_var) = (explode(':', $junk_var));
                    list($time_minute, $morning_night) = (explode(' ', $junk_var));
//                echo $morning_night. '<br/>';
                    list(, $junk_var) = (explode('#', $string)); //make junkvar just after #

                    list($post_count_in_thread) = (explode(' ', $junk_var)); //make junkvar just after #
                    list($post_count_in_thread) = (explode(' ', $junk_var)); //make junkvar just after #
//                echo $junk_var;

                    list(, $username) = (preg_split("/[\s]+/", $junk_var)); //make junkvar just after #
                    list(, $join_date_month) = (explode('Join Date: ', $junk_var)); //make junkvar just after #
                    list($join_date_month, $join_date_year) = (preg_split("/[\s]+/", $join_date_month)); //make junkvar just after #

//echo $join_date_month." ".$join_date_year.'<br/>';

                    list(, $junk_var) = (explode('Posts: ', $junk_var)); //make junkvar just after #
                    list($message) = (explode('___', $junk_var)); //make junkvar just after #


                    //test
                                  echo "This is thread:".$threadID . "\n";
//                                   echo "Day of creation:".$month." ".$day." ".$year . '<br/>';
//                                   echo "Time of Day created:".$time_hour." ".$time_minute." ".$morning_night." " . '<br/>';
//                                 echo "Post count in this thread:".$post_count_in_thread ." by user:". $username.'<br/>';
//                                 echo "Joined:".$join_date_month." ".$join_date_year . '<br/>';
//                                  echo "Says: ". $message . '<br/><br/><br/>';

//                    echo $threadID;

                    $link = mysql_connect('localhost', 'root', 'BMLAAAAMA');
                    if (!$link) {
                        die('Could not connect: ' . mysql_error());
                        echo "\n is not connecting";
                    }
                    //           echo '<br/>'."seems to be working";
                    mysql_select_db('GFY', $link) or die(mysql_error());

//                mysql_query("INSERT INTO post_information (threadID, month, year) values ('$join_date_year','$join_date_month','$post_count_in_thread')");
                    mysql_query("INSERT INTO thread (threadID, month, day, year, time_hour, time_minute, morning_night, post_count_in_thread, username, join_date_month, join_date_year, message) values ('$threadID','$month','$day','$year','$time_hour','$time_minute','$morning_night','$post_count_in_thread','$username','$join_date_month','$join_date_year','$message')");
// 12 values into mysql
//                printf("Last inserted record has id %d\n", mysql_insert_id());

                    mysql_close($link);


//echo "this works";


                    usleep(5000);


                }

                if (preg_match("/Display Modes/", $es))
                    $flag = true;


            }


        } catch (Exception $e) {
            //       echo "on to next iteration".'<br/>';
        }

    }
    //end of $i for





//echo $string;

?>
