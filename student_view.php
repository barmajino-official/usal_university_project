<?php
require( './connection.php' );

if (($_SESSION['user_id'] == '') || (!isset($_SESSION['user_id']))) {
  header("Location: ./login.php");
}
if ($_SESSION['user_type'] == 'D') {
  header("Location: ./login.php");
}
$student_id = $_SESSION['user_id'];
$get_all_resevation = "SELECT usal_university.reservation.id as reservation_id , usal_university.doctors.fname as doctor_fname, usal_university.doctors.lname as doctor_lname, usal_university.room.name as room_name, usal_university.room.seatnb as room_seat_number, usal_university.floor.name as floor_name, usal_university.course.name as course_name, usal_university.major.name as major_name, usal_university.reservation.date as reservation_date, usal_university.reservation.checkin as reservation_in, usal_university.reservation.checkout as reservation_out FROM usal_university.reservation JOIN usal_university.doctors on usal_university.reservation.doctor_id = usal_university.doctors.id JOIN usal_university.room on usal_university.reservation.room = usal_university.room.id JOIN usal_university.floor on usal_university.room.floor = usal_university.floor.id JOIN usal_university.course on usal_university.reservation.course_id = usal_university.course.id JOIN usal_university.major on usal_university.course.major_id = usal_university.major.id JOIN usal_university.students on usal_university.students.major = usal_university.major.id where usal_university.students.id = $student_id";

$get_all_resevation_data = $conn->query($get_all_resevation);

$get_student_info = "SELECT fname, lname FROM usal_university.students WHERE id = $student_id";
$get_student_info_data = $conn->query($get_student_info)->fetch_assoc();


?>


<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./src/css/student_view.css" />
    <title>Document</title>
</head>

<body class="flex flex-col min-h-screen ">
    <div class="navbar border-b-2 border-gray-200">
        <div class="flex-1">
            <a class="text-xl m-2" href="./">
                <img src="./src/pictures/usal2 (2).ico" class="object-cover object-center h-14" alt="Shoes" />
            </a>
            <ul class="menu menu-horizontal p-0 space-x-4">
                <li><a href="./">Home</a></li>
                <li><a href="./logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="flex-none">
            <h1 class="font-bold text-xl text-gray-500 p-4">
                Welcome <?php echo $get_student_info_data['fname']." ".$get_student_info_data['lname'] ?>
            </h1>
        </div>
    </div>
    <main class="flex-grow">
        <div class="flex flex-wrap   justify-center xl:justify-start  h-full">

            <?php 

    if ($get_all_resevation_data->num_rows > 0) {
  while ($resevation = $get_all_resevation_data->fetch_assoc()) {
echo '<div class="card card-compact w-72 m-2 shadow-xl">
            <figure class="relative">
                <img src="./src/pictures/student_view_bg.webp" alt="Shoes" />
                <h2 class="card-title absolute bottom-1 left-1">'.$resevation['course_name'].'</h2>
            </figure>
            <div class="card-body">
                <p class="text-xs">Doctor name : '.$resevation['doctor_fname'].' '.$resevation['doctor_lname'].'</p>
                <div class="flex flex-wrap">
                    <p class="text-xs">floor : '.$resevation['floor_name'].'</p>
                    <p class="text-xs">room : '.$resevation['room_name'].'</p>
                </div>
                <div class="flex flex-wrap">
                    <p class="text-xs">check-in : '.$resevation['reservation_in'].'</p>
                    <p class="text-xs">check-out : '.$resevation['reservation_out'].'</p>
                </div>
                <p class="text-xs">Date : '.$resevation['reservation_date'].'</p>
            </div>
        </div>';
  }
}

?>

        </div>
    </main>
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
</body>

</html>
