<?php
require( './connection.php' );

if ( isset( $_POST ) ) {
    foreach ( $_POST as $key => $item ) {
        /* Assigning the value of the `$key` to the variable with the name of `$item`. */
        eval( "$" . $key . " = '" . $item . "';" );
    }
}

if ( isset( $_GET ) ) {
    foreach ( $_GET as $key => $item ) {
        /* Assigning the value of the `$key` to the variable with the name of `$item`. */
        eval( "$" . $key . " = '" . $item . "';" );
    }
}

if (($_SESSION['user_id'] == '') || (!isset($_SESSION['user_id']))) {
    header("Location: ./login.php");
}
if ($_SESSION['user_type'] == 'S') {
    header("Location: ./login.php");
}

$doctors_id = $_SESSION["user_id"];

if ( isset( $_POST[ 'onclicksubmit' ] ) ) {
    // {'major':'1', 'course':'2', 'room':'2', 'date':'2022-05-04', 'time_in':'00:58', 'time_out':'00:58', 'onclicksub':'submit'}
    //echo json_encode( $_POST );
    $insert_resevation = "INSERT INTO usal_university.reservation(`doctor_id`, `room`, `course_id`, `date`, `checkin`, `checkout`) VALUES 
    ($doctors_id,$room,$course,'$date','$time_in','$time_out')";
    if ( !$conn->query( $insert_resevation )) {
        echo 'Error: ' . $insert_resevation . '<BR>' . $conn->error;
    } 

}

if (isset($_POST['onclickdelete'])) {
    $delete_reservation = "DELETE FROM usal_university.reservation WHERE id = $delete_items;";
    if ( !$conn->query( $delete_reservation )) {
        echo 'Error: ' . $delete_reservation . '<BR>' . $conn->error;
    }

}

if (isset($_POST['onclickupdate'])) {
    $update_resevation = "UPDATE usal_university.reservation SET room=$room, course_id=$course,date='$date',checkin='$time_in',checkout='$time_out' WHERE id = $update_items;";
    if (!$conn->query($update_resevation)) {
        echo 'Error: ' . $update_resevation . '<BR>' . $conn->error;
    }
    

}
if (isset($_POST['onclickedit'])) {

    $edit_reservation = $edit_items;
    $get_Reservation_to_edit = "SELECT usal_university.reservation.id , usal_university.room.id as room_id, usal_university.course.id as course_id,usal_university.major.id as faculty_id, usal_university.reservation.date, usal_university.reservation.checkin, usal_university.reservation.checkout FROM usal_university.reservation JOIN usal_university.doctors ON usal_university.reservation.doctor_id = usal_university.doctors.id JOIN usal_university.room ON usal_university.reservation.room = usal_university.room.id JOIN usal_university.floor ON usal_university.room.floor = usal_university.floor.id JOIN usal_university.course ON usal_university.reservation.course_id = usal_university.course.id JOIN usal_university.major ON usal_university.course.major_id = usal_university.major.id WHERE usal_university.doctors.id = $doctors_id AND usal_university.reservation.id = $edit_reservation ;";
    $get_Reservation_to_edit_value = $conn->query($get_Reservation_to_edit);
    if ($get_Reservation_to_edit_value->num_rows > 0) {
        $get_Reservation_to_edit_json_value = $get_Reservation_to_edit_value->fetch_assoc();
    }

}
else {
    $edit_reservation = null;

}

$get_rooms = "
    SELECT usal_university.room.id , usal_university.floor.name as floorname, usal_university.room.name as roomname, usal_university.room.seatnb FROM 
    ( usal_university.room INNER JOIN usal_university.floor ON usal_university.room.floor = usal_university.floor.id );
    ";
/* Executing the query. */
$get_rooms_result = $conn->query( $get_rooms );

/* Checking if the query is successful or not. If it is successful, it will push the data to
the array. */
$get_Reservations = "SELECT usal_university.reservation.id, usal_university.doctors.fname as doctor_fname, usal_university.doctors.lname as doctor_lname, usal_university.room.name as room_name, usal_university.room.seatnb as seat_number, usal_university.floor.name as floor_name, usal_university.course.name as course_name,usal_university.major.name as faculty, usal_university.reservation.date, usal_university.reservation.checkin, usal_university.reservation.checkout FROM usal_university.reservation JOIN usal_university.doctors ON usal_university.reservation.doctor_id = usal_university.doctors.id JOIN usal_university.room ON usal_university.reservation.room = usal_university.room.id JOIN usal_university.floor ON usal_university.room.floor = usal_university.floor.id JOIN usal_university.course ON usal_university.reservation.course_id = usal_university.course.id JOIN usal_university.major ON usal_university.course.major_id = usal_university.major.id WHERE usal_university.doctors.id = $doctors_id;";
$get_Reservation_table_rows = $conn->query( $get_Reservations );

$get_majors = "SELECT usal_university.major.id as major_id, usal_university.major.name as major_name FROM usal_university.major JOIN usal_university.doctors_multy_major ON usal_university.major.id = usal_university.doctors_multy_major.major_id JOIN usal_university.doctors ON usal_university.doctors_multy_major.doctor_id = usal_university.doctors.id WHERE usal_university.doctors.id = $doctors_id;";
$get_majors_result = $conn->query( $get_majors );

$get_courses = "SELECT usal_university.course.id as course_id ,usal_university.major.id as major_id, usal_university.course.name as course_name , usal_university.major.name as major_name FROM usal_university.course JOIN usal_university.major ON usal_university.course.major_id = usal_university.major.id JOIN usal_university.doctors_multy_major ON usal_university.major.id = usal_university.doctors_multy_major.major_id WHERE usal_university.doctors_multy_major.doctor_id = $doctors_id;";
$get_courses_result = $conn->query( $get_courses );

?>

<!DOCTYPE html>
<html lang='en' data-theme='light'>

<head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <link rel='stylesheet' href='./src/css/Reservation.css' />
    <title>Document</title>
</head>

<body>
    <div class='navbar border-b-2 border-gray-200 '>

        <div class="flex-1">
            <a class='text-xl m-2' href='./'>
                <img src='./src/pictures/usal2 (2).ico' class='object-cover object-center h-14' alt='Shoes' />
            </a>
            <ul class="menu menu-horizontal p-0 space-x-4">
                <li><a href="./">Home</a></li>
                <li><a href="./logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="flex-none">
            <h1 class="font-bold text-xl text-gray-500 p-4">Welcome Dr. <?php echo $_SESSION["user_full_name"] ?></h1>
        </div>
    </div>

    <div class='card card-compact bg-white w-auto m-2 mb-4 shadow-md'>
        <figure class='relative'>
            <img src='./src/pictures/students.jpg' class='object-cover object-top w-full h-52' alt='Shoes' />
            <div class='sm:backdrop-blur-sm bg-white/30 absolute w-full h-full'></div>
        </figure>
        <div class='card-body'>
            <h2 class='card-title'>Reservation</h2>
            <div class='flex items-center justify-center'>
                <!-- open if condition for test if the type in edit or not  -->
                <?php if (isset($edit_reservation)) { ?>
                <!-- //////////////////////////////////////////////////// -->
                <form id='Reservation_form_edit' class='grid grid-row grid-cols-12 w-full'
                    action="<?php $_SERVER['PHP_SELF']?>" method='post'>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Faculty

                            </span>
                        </label>
                        <select name='major' id='faculty_select'
                            onchange="filter(this,document.querySelector('#Reservation_form').querySelector('#course_select'))"
                            class='select input-bordered w-full' name='floor' required>
                            <option value=''>Please select</option>
                            <?php
                                if ( $get_majors_result->num_rows > 0 ) {
                                    while ( $faculty = $get_majors_result->fetch_assoc() ) {
                                        if ( $get_Reservation_to_edit_json_value[ 'faculty_id' ] == $faculty[ 'major_id' ] ) {
                                            echo '<option value=' . $faculty[ 'major_id' ] . ' selected>' . $faculty[ 'major_name' ] . '</option>';
                                        } else {
                                            echo '<option value=' . $faculty[ 'major_id' ] . '>' . $faculty[ 'major_name' ] . '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option >error</option>';
                                    echo '<script>console.log(\'" . $conn->error . "\');</script>';
                                }

                            ?>
                        </select>
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Course</span>
                        </label>
                        <select name='course' value='' id='course_select' class='select input-bordered w-full'
                            name='floor' required>
                            <option value=''>Please select</option>
                            <?php
                                if ( $get_courses_result->num_rows > 0 ) {
                                    while ( $course = $get_courses_result->fetch_assoc() ) {
                                        if ( $get_Reservation_to_edit_json_value[ 'course_id' ] == $course[ 'course_id' ] ) {
                                            echo '<option   value=' . $course[ 'course_id' ] . ' match=' . $course[ 'major_id' ] . ' selected>' . $course[ 'course_name' ] . '</option>';
                                        } else {
                                            echo '<option   value=' . $course[ 'course_id' ] . ' match=' . $course[ 'major_id' ] . '>' . $course[ 'course_name' ] . '</option>';

                                        }
                                    }
                                } else {
                                    echo '<option >error</option>';
                                    echo '<script>console.log(\'" . $conn->error . "\');</script>';
                                }

                            ?>
                        </select>
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Room</span>
                        </label>
                        <select name='room' class='select input-bordered w-full' name='floor' required>
                            <option value=''>Please select</option>
                            <?php
                                if ( $get_rooms_result->num_rows > 0 ) {
                                    while ( $room = $get_rooms_result->fetch_assoc() ) {
                                        if ( $get_Reservation_to_edit_json_value[ 'room_id' ] == $room[ 'id' ] ) {
                                            echo '<option value=' . $room[ 'id' ] . ' selected>' . $room[ 'roomname' ] . ' ( floor : ' .
                                            $room[ 'floorname' ] . ', seat : ' . $room[ 'seatnb' ] . ' )</option>';
                                        } else {
                                            echo '<option value=' . $room[ 'id' ] . '>' . $room[ 'roomname' ] . ' ( floor : ' .
                                            $room[ 'floorname' ] . ', seat : ' . $room[ 'seatnb' ] . ' )</option>';
                                        }

                                    }
                                } else {
                                    echo 'Error: ' . $sql . '<br>' . $conn->error;
                                }
                            ?>
                        </select>
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Date</span>
                        </label>
                        <input name='date' value="<?php echo $get_Reservation_to_edit_json_value['date'] ?>" type='date'
                            class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Check-in</span>
                        </label>
                        <input name='time_in' value="<?php echo $get_Reservation_to_edit_json_value['checkin'] ?>"
                            type='time' class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Check-out</span>
                        </label>
                        <input name='time_out' value="<?php echo $get_Reservation_to_edit_json_value['checkout'] ?>"
                            type='time' class='input input-bordered w-full' required />
                    </div>
                    <input class='sr-only' name="update_items"
                        value="<?php echo $get_Reservation_to_edit_json_value['id'] ?>" />
                    <input name='onclickupdate' value='submit' type='submit' class='sr-only' />
                </form>
                <!-- close if condition and open else condition of isset( $edit_reservation ) -->
                <?php } else { ?>
                <!-- //////////////////////////////////////////////////////////// -->
                <form id='Reservation_form' class='grid grid-row grid-cols-12 w-full'
                    action="<?php $_SERVER['PHP_SELF']?>" method='post'>
                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Faculty</span>
                        </label>
                        <select name='major' id='faculty_select'
                            onchange="filter(this,document.querySelector('#Reservation_form').querySelector('#course_select'))"
                            class='select input-bordered w-full' name='floor' required>
                            <option value=''>Please select</option>
                            <?php
                                if ( $get_majors_result->num_rows > 0 ) {
                                    while ( $room = $get_majors_result->fetch_assoc() ) {
                                        echo '<option value=' . $room[ 'major_id' ] . '>' . $room[ 'major_name' ] . '</option>';
                                    }
                                } else {
                                    echo '<option >error</option>';
                                    echo '<script>console.log(\'" . $conn->error . "\');</script>';
                                }

                            ?>
                        </select>
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Course</span>
                        </label>
                        <select name='course' disabled id='course_select' class='select input-bordered w-full'
                            name='floor' required>
                            <option value=''>Please select</option>
                            <?php
                                if ( $get_courses_result->num_rows > 0 ) {
                                    while ( $course = $get_courses_result->fetch_assoc() ) {
                                        echo '<option   value=' . $course[ 'course_id' ] . ' match=' . $course[ 'major_id' ] . '>' . $course[ 'course_name' ] . '</option>';
                                    }
                                } else {
                                    echo '<option >error</option>';
                                    echo '<script>console.log(\'" . $conn->error . "\');</script>';
                                }

                            ?>
                        </select>
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Room</span>
                        </label>
                        <select name='room' class='select input-bordered w-full' name='floor' required>
                            <option value=''>Please select</option>
                            <?php
                                if ( $get_rooms_result->num_rows > 0 ) {
                                    while ( $room = $get_rooms_result->fetch_assoc() ) {
                                        echo '<option value=' . $room['id'] . '>' . $room['roomname'] . ' ( floor : ' .
                                        $room['floorname'] . ', seat : ' . $room['seatnb'] . ' )</option>';
                                    }
                                } else {
                                    echo 'Error: ' . $sql . '<br>' . $conn->error;
                                }
                            ?>
                        </select>
                    </div>


                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Date</span>
                        </label>
                        <input name='date' type='date' class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Check-in</span>
                        </label>
                        <input name='time_in' type='time' class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Check-out</span>
                        </label>
                        <input name='time_out' type='time' class='input input-bordered w-full' required />
                    </div>
                    <input name='onclicksubmit' value='submit' type='submit' class='sr-only' />
                    <!-- close else of isset( $edit_reservation ) -->
                    <?php } ?>
                    <!-- //////////////////////////////////////// -->

                </form>
            </div>
            <div class='card-actions justify-end'>
                <?php if ( isset( $edit_reservation ) ) { ?>
                <button type='submit'
                    onclick="document.querySelector('#Reservation_form_edit').querySelector('input[type=\'submit\']').click()"
                    class='btn bg-[#35927E] hover:bg-[#6DA45E]'>
                    Update
                </button>
                <?php } else { ?>
                <button type='submit'
                    onclick="document.querySelector('#Reservation_form').querySelector('input[type=\'submit\']').click()"
                    class='btn bg-[#35927E] hover:bg-[#6DA45E]'>
                    Submit
                </button>
                <?php } ?>

                <?php if ( isset( $edit_reservation ) ) { ?>
                <form action="<?php $_SERVER['PHP_SELF']?>?id=<?php echo $doctors_id ?>" method='post'>
                    <input class='sr-only' name="delete_items"
                        value="<?php echo $get_Reservation_to_edit_json_value['id'] ?>" />
                    <button type='submit' name="onclickdelete" class='btn bg-red-600 hover:bg-red-400'>
                        Delete
                    </button>
                </form>
                <form action="<?php $_SERVER['PHP_SELF']?>?id=<?php echo $doctors_id ?>" method='post'>
                    <!-- <input class='sr-only' name="cancel_edit_items"
                        value="<?php echo $get_Reservation_to_edit_json_value['id'] ?>" /> -->
                    <button type='submit' name="onclickcancel" class='btn bg-gray-600 hover:bg-gray-400'>
                        Cancel
                    </button>
                </form>
                <?php } ?>

            </div>
        </div>
    </div>

    <hr />

    <div class='overflow-x-auto shadow-md rounded-lg m-2 mt-4 border-2 border-gray-200'>
        <table class='table w-full'>
            <!-- head -->
            <thead class='bg-[#F2F2F2]'>
                <tr>
                    <th class='sr-only'> </th>
                    <th>Faculty</th>
                    <th>Course</th>
                    <th>Floor</th>
                    <th>Room</th>
                    <th>Seat</th>
                    <th>Date</th>
                    <th>Check in</th>
                    <th>Check out</th>
                    <th class='sr-only'>Action</th>

                </tr>
            </thead>
            <tbody>
                <!-- row 1 -->

                <?php
            if ( $get_Reservation_table_rows->num_rows > 0 ) {
                $count_table_row = 1;
                while( $rows = $get_Reservation_table_rows->fetch_assoc() ) {
                    echo "<tr>
                                    <th>".$count_table_row."</th>
                                    <td>".$rows[ 'faculty' ]."</td>
                                    <td>".$rows[ 'course_name' ]."</td>
                                    <td>".$rows[ 'floor_name' ]."</td>
                                    <td>".$rows[ 'room_name' ]."</td>
                                    <td>".$rows[ 'seat_number' ]."</td>
                                    <td>".$rows[ 'date' ]."</td>
                                    <td>".$rows[ 'checkin' ]."</td>
                                    <td>".$rows[ 'checkout' ]."</td>
                                    <td >
                                    <form action=".$_SERVER['PHP_SELF']."?id=".$doctors_id." method='post'>
                    <input class='sr-only' name='edit_items' value=".$rows['id']." />
                    <button type='submit' name='onclickedit'>
                        <svg class='w-6 h-6 text-blue-600 cursor-pointer' fill='none' stroke='currentColor'
                            viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'>
                            </path>
                        </svg>
                    </button>
                </form></td>
                                </tr>";
                    $count_table_row++;
                }
            } else {
                echo "</tr><td colspan='8' class='text-center'> Sorry Doctor You Dont have any resevation fill the form to add !</td></tr>";
            } ?>

            </tbody>
        </table>
    </div>

    <footer class="footer items-center p-4 border-t-2 border-gray-200 mt-2 text-neutral-content">
        <div class="items-center text-black grid-flow-col">
            <svg width="36" height="36" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                clip-rule="evenodd" class="fill-current">
                <path
                    d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z">
                </path>
            </svg>
            <p>Copyright © 2022 - All right reserved</p>
        </div>
        <div class="grid-flow-col text-black gap-4 md:place-self-center md:justify-self-end">
            <div class="tooltip" data-tip="+961 1 453 003">
                <a href="tel:+961 1 453 003"><svg class="w-6 h-6 text-black hover:text-green-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </a>
            </div>
            <div class="tooltip" data-tip="Map">
                <a
                    href="https://www.google.com/maps/place/USAL+-+University+Of+Sciences+And+Arts+In+Lebanon/@33.856327,35.5027542,15z/data=!4m5!3m4!1s0x0:0xf7c29c0898692ff9!8m2!3d33.856327!4d35.5027542"><svg
                        class="w-6 h-6 text-black hover:text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </a>
            </div>
            <div class="tooltip tooltip-left" data-tip="info@usal.edu.lb">
                <a href="mailto:info@usal.edu.lb">
                    <svg class="w-6 h-6 text-black hover:text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <script src="./src/js/reservation_filter_course.js"></script>
    <script>
    <?php if (isset($edit_reservation)) { ?>
    filter_on_edit();
    <?php } ?>
    </script>
</body>

</html>
