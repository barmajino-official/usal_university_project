<?php
require( './connection.php' );


function get_curent_major_selected($conn,$edit_items){
    $get_major_for_doctor = "SELECT usal_university.major.id as major_id, usal_university.major.name as major_name FROM usal_university.major JOIN usal_university.doctors_multy_major ON usal_university.major.id = usal_university.doctors_multy_major.major_id JOIN usal_university.doctors ON usal_university.doctors_multy_major.doctor_id = usal_university.doctors.id WHERE usal_university.doctors.id = $edit_items;";
    $get_major_list_for_doctor = $conn->query($get_major_for_doctor);
    // echo json_encode($get_major_list_for_doctor);
    $matching_majors = array();
    while ($rows = $get_major_list_for_doctor->fetch_assoc()) {
        array_push($matching_majors, json_encode($rows['major_id']));
    }
    return $matching_majors;
}


function generate_doctors_multy_major_query($doctor_id,$major){
    $add_doctors_multy_major = "INSERT INTO usal_university.doctors_multy_major(`doctor_id`, `major_id`) VALUES ";
    foreach ($major as $major_loop_id) {
        $add_doctors_multy_major = $add_doctors_multy_major . "(" . $doctor_id . "," . $major_loop_id . "), ";
    }
    $add_doctors_multy_major = rtrim($add_doctors_multy_major, ", ");
    $add_doctors_multy_major = $add_doctors_multy_major . ";";
    // echo $add_doctors_multy_major;
    return $add_doctors_multy_major;
}

if ( isset( $_POST ) ) {
    foreach ( $_POST as $key => $item ) {
        /* Assigning the value of the `$key` to the variable with the name of `$item`. */
        if($key != "major"){
            eval("$" . $key . " = '" . $item . "';");
        }else{
            eval("$" . $key . " = '" . json_encode($item) . "';");
        }
        
    }
}

if ( isset( $_GET ) ) {
    foreach ( $_GET as $key => $item ) {
        /* Assigning the value of the `$key` to the variable with the name of `$item`. */
        eval( "$" . $key . " = '" . $item . "';" );
    }
}


if ( isset( $_POST[ 'onclicksubmit' ] ) ) {
    //{"fname":"drerternt","lame":"Saadd","email":"alijaafar.betarmajino@gmail.com","password":"456456","major":["1","3","9"],"onclicksubmit":"submit"}

    $insert_new_user_auth = "INSERT INTO usal_university.authentication(`email`, `password`,`type`) VALUES ('$email','$password','D')";
    if ($conn->query($insert_new_user_auth) === TRUE) {
        $get_last_id = "SELECT id FROM usal_university.authentication ORDER BY id DESC LIMIT 1;";
        $get_last_id_value = $conn->query($get_last_id)->fetch_assoc();
        $auth_id = $get_last_id_value['id'];
        $insert_doctor = "INSERT INTO usal_university.doctors(`fname`, `lname`, `authentication_id`) VALUES ('$fname','$lame',$auth_id)";
        
        if ($conn->query($insert_doctor) === TRUE) {
            $get_last_id_from_doctors = "SELECT id FROM usal_university.doctors ORDER BY id DESC LIMIT 1;";
            $get_last_id_from_doctors_value = $conn->query($get_last_id_from_doctors)->fetch_assoc();
            $add_major_for_doctors = generate_doctors_multy_major_query($get_last_id_from_doctors_value['id'], $_POST['major']);
            //echo '<script> alert(\'done\');</script>';
            if (!$conn->query($add_major_for_doctors)) {
                echo 'Error: '.$add_major_for_doctors.'<br>' . $conn->error;
            }

        }
        else {
            //$result[ 'the_error' ] = 'Error: ' . $sql . '<br>' . $conn->error;
            echo 'Error: ' . $insert_doctor . '<BR>' . $conn->error;
        }

    }
    else {
        //$result[ 'the_error' ] = 'Error: ' . $sql . '<br>' . $conn->error;
        echo 'Error: '.$insert_new_user_auth.'<BR>' . $conn->error;
    }

}

if (isset($_POST['onclickdelete'])) {
    //{"delete_doctor_items":"6","delete_items":"32","onclickdelete":""}
    $delete_doctors_multy_major = "DELETE FROM usal_university.doctors_multy_major WHERE usal_university.doctors_multy_major.doctor_id = $delete_doctor_items;";
    $delete_doctor_reservation = "DELETE FROM usal_university.reservation WHERE usal_university.reservation.doctor_id = $delete_doctor_items;";
    $delete_doctors = "DELETE FROM usal_university.doctors WHERE id = $delete_doctor_items;";
    $delete_doctors_auth = "DELETE FROM usal_university.authentication WHERE id = $delete_doctor_authentication_id;";

    $the_query = array($delete_doctors_multy_major, $delete_doctor_reservation, $delete_doctors, $delete_doctors_auth);
    foreach ($the_query as $sql) {
        if (!$conn->query($sql)) {
            echo 'Error: ' . $sql . '<br>' . $conn->error;
        }

    }

    


}

if (isset($_POST['onclickupdate'])) {

    // echo json_encode($_POST);
    //{"fname":"Mohammad","lname":"Saad","email":"email1@gmail.com","password":"password","update_items":"1","onclickupdate":"submit"}


    $update_doctor_info = "UPDATE usal_university.doctors SET fname='$fname',lname='$lname' WHERE usal_university.doctors.id = $update_items;";
    $update_doctor_authentication = "UPDATE usal_university.authentication SET email='$email',password='$password' WHERE usal_university.authentication.id = $update_items_auth;";
    if (!$conn->query($update_doctor_info)) {
        echo 'Error: ' . $update_doctor_info . '<br>' . $conn->error;
    }


    if (!$conn->query($update_doctor_authentication)) {
        echo 'Error: ' . $update_doctor_authentication . '<br>' . $conn->error;
    }

}
if (isset($_POST['onclickedit'])) {

    $edit_doctor = $edit_items;
    $get_doctors_to_edit = "SELECT usal_university.doctors.id as doctor_id, usal_university.doctors.fname as doctor_fname, usal_university.doctors.lname as doctor_lname, usal_university.authentication.email as doctor_email, usal_university.authentication.password as doctor_password, usal_university.authentication.id as doctor_authentication_id FROM usal_university.doctors JOIN usal_university.authentication ON usal_university.doctors.authentication_id = usal_university.authentication.id WHERE usal_university.doctors.id = $edit_items";
    $get_doctor_data_to_edit = $conn->query( $get_doctors_to_edit )->fetch_assoc();
    //echo json_encode($get_doctor_data_to_edit);
}
else {
    $edit_doctor = null;

}

$get_majors = "SELECT id as major_id , name as major_name FROM usal_university.major WHERE 1
";
/* Executing the query. */
$get_majors_result = $conn->query( $get_majors );

/* Checking if the query is successful or not. If it is successful, it will push the data to
the array. */
$get_doctors = "SELECT usal_university.doctors.id as doctor_id, usal_university.doctors.fname as doctor_fname, usal_university.doctors.lname as doctor_lname, usal_university.authentication.email, usal_university.authentication.password, usal_university.authentication.id as doctor_authentication_id FROM usal_university.doctors JOIN usal_university.authentication ON usal_university.doctors.authentication_id = usal_university.authentication.id;";
$get_doctors_table_rows = $conn->query( $get_doctors );


?>

<!DOCTYPE html>
<html lang='en' data-theme='light'>

<head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <link rel='stylesheet' href='./src/css/doctors.css' />
    <title>Document</title>
</head>

<body>
    <div class='navbar border-b-2 border-gray-200 '>

        <div class="flex-1">
            <a class='text-xl m-2' href='./'>
                <img src='./src/pictures/usal2 (2).ico' class='object-cover object-center h-14' alt='Shoes' />
            </a>
            <ul class="menu menu-horizontal p-0 ml-6">
                <li><a href="./admin.html" class="font-bold text-md">Back</a></li>
            </ul>
        </div>
        <div class="flex-none">
            <h1 class="font-bold text-xl text-gray-500 p-4">Doctors Table</h1>
        </div>
    </div>

    <div class='card card-compact bg-white w-auto m-2 mb-4 shadow-md'>
        <figure class='relative'>
            <img src='./src/pictures/doctorsAdmin.jpg' class='object-cover object-center w-full h-52' alt='Shoes' />
            <div class=' bg-white/30 absolute w-full h-full'></div>
        </figure>
        <div class='card-body'>
            <h2 class='card-title'>Reservation</h2>
            <div class='flex items-center justify-center'>
                <!-- open if condition for test if the type in edit or not  -->
                <?php if (isset($edit_doctor)) { ?>
                <!-- //////////////////////////////////////////////////// -->
                <form id='Reservation_form_edit' class='grid grid-row grid-cols-12 w-full'
                    action="<?php $_SERVER['PHP_SELF']?>" method='post'>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>First name

                            </span>
                        </label>
                        <input type="text" value="<?php echo $get_doctor_data_to_edit['doctor_fname'] ?>" name="fname"
                            class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Last name

                            </span>
                        </label>
                        <input type="text" name="lname" value="<?php echo $get_doctor_data_to_edit['doctor_lname'] ?>"
                            class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Email

                            </span>
                        </label>
                        <input type="email" name="email" value="<?php echo $get_doctor_data_to_edit['doctor_email'] ?>"
                            class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Password

                            </span>
                        </label>
                        <input type="password" name="password"
                            value="<?php echo $get_doctor_data_to_edit['doctor_password'] ?>"
                            class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Faculty
                            </span>
                        </label>
                        <ul class="list-disc input-bordered w-full">
                            <?php
                                if ($get_majors_result->num_rows > 0) {
                                    while ($majors = $get_majors_result->fetch_assoc()) {
                                        if (in_array(json_encode($majors['major_id']), get_curent_major_selected($conn, $edit_items))) {
                                            echo '<li>' . $majors['major_name'] . '</li>';
                                        }

                                    }
                                }
                                else {
                                    echo 'Error: '  . $conn->error;
                                }
                            ?>
                        </ul>

                    </div>


                    <input class='sr-only' name="update_items" value="<?php echo $edit_doctor ?>" />
                    <input class='sr-only' name="update_items_auth"
                        value="<?php echo $get_doctor_data_to_edit['doctor_authentication_id'] ?>" />
                    <input name='onclickupdate' value='submit' type='submit' class='sr-only' />
                </form>
                <!-- close if condition and open else condition of isset( $edit_doctor ) -->
                <?php } else { ?>
                <!-- //////////////////////////////////////////////////////////// -->
                <form id='Reservation_form' class='grid grid-row grid-cols-12 w-full'
                    action="<?php $_SERVER['PHP_SELF']?>" method='post'>
                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>First name

                            </span>
                        </label>
                        <input type="text" name="fname" class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Last name

                            </span>
                        </label>
                        <input type="text" name="lame" class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Email

                            </span>
                        </label>
                        <input type="email" name="email" class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Password

                            </span>
                        </label>
                        <input type="password" name="password" class='input input-bordered w-full' required />
                    </div>

                    <div class='form-control w-full p-2 col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3'>
                        <label class='label'>
                            <span class='label-text'>Faculty

                            </span>
                        </label>
                        <select name='major[]' id='faculty_select'
                            onchange="filter(this,document.querySelector('#Reservation_form').querySelector('#course_select'))"
                            class='select input-bordered w-full h-40' name='floor' required multiple>
                            <?php
                                if ($get_majors_result->num_rows > 0) {
                                    while ($majors = $get_majors_result->fetch_assoc()) {
                                        echo '<option value=' . $majors['major_id'] . '>' . $majors['major_name'] .  ' </option>';
                                    }
                                }
                                else {
                                    echo 'Error: ' . $sql . '<br>' . $conn->error;
                                }
                            ?>
                        </select>
                    </div>
                    <input name='onclicksubmit' value='submit' type='submit' class='sr-only' />
                    <!-- close else of isset( $edit_doctor ) -->
                    <?php } ?>
                    <!-- //////////////////////////////////////// -->

                </form>
            </div>
            <div class='card-actions justify-end'>
                <?php if ( isset( $edit_doctor ) ) { ?>
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

                <?php if ( isset( $edit_doctor ) ) { ?>
                <form action="<?php $_SERVER['PHP_SELF']?>" method='post'>
                    <input class='sr-only' name="delete_doctor_items"
                        value="<?php echo $get_doctor_data_to_edit['doctor_id'] ?>" />
                    <input class='sr-only' name="delete_doctor_authentication_id"
                        value="<?php echo $get_doctor_data_to_edit['doctor_authentication_id'] ?>" />
                    <button type='submit' name="onclickdelete" class='btn bg-red-600 hover:bg-red-400'>
                        Delete
                    </button>
                </form>
                <form action="<?php $_SERVER['PHP_SELF']?>" method='post'>

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
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th class='sr-only'>Action</th>

                </tr>
            </thead>
            <tbody>
                <!-- row 1 -->

                <?php
            if ( $get_doctors_table_rows->num_rows > 0 ) {
                $count_table_row = 1;
                while( $rows = $get_doctors_table_rows->fetch_assoc() ) {
        echo "<tr>
                                    <th>" . $count_table_row . "</th>
                                    <td>" . $rows['doctor_fname'] . "</td>
                                    <td>" . $rows['doctor_lname'] . "</td>
                                    <td>" . $rows['email'] . "</td>
                                    <td>" . $rows['password'] . "</td><td >
                                    <form action=".$_SERVER['PHP_SELF']." method='post'>
                    <input class='sr-only' name='edit_items' value=".$rows['doctor_id']." />
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
}?>

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

    <script>
    function filter(elem, select) {
        if (elem.value != '') {
            select.disabled = false;
        } else {
            select.disabled = true;
        }
        for (var i = 0; i < select.length; i++) {
            // console.log( '=======================================' );
            // console.log( select.options[ i ].getAttribute( 'match' ) );
            // console.log( '--------------------------------------' );
            // console.log( elem.value );
            // console.log( '--------------------------------------' );
            // console.log( select.options[ i ].getAttribute( 'match' ) == elem.value );
            // console.log( '=======================================' );
            if (select.options[i].getAttribute('match') == elem.value) {
                select.options[i].style.display = 'block';
            } else {
                select.options[i].style.display = 'none';
            }
        }
    }

    <?php if ( isset( $edit_doctor ) ) { ?>
    setTimeout(function() {
        filter(document.querySelector('#Reservation_form_edit').querySelector('#faculty_select'), document
            .querySelector(
                '#Reservation_form_edit').querySelector('#course_select'));
    }, 20);
    <?php } ?>
    </script>
</body>

</html>
