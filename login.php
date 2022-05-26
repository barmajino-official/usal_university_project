<?php
 require('./connection.php');

if (isset($_POST)) {
  foreach ($_POST as $key => $item) {
    /* Assigning the value of the `$key` to the variable with the name of `$item`. */
    if ($key != "major") {
      eval("$" . $key . " = '" . $item . "';");
    }
    else {
      eval("$" . $key . " = '" . json_encode($item) . "';");
    }

  }
}
$get_result = new stdClass;
$get_result->num_rows = null;


if (isset($_POST['submit'])) {
  $sql = "SELECT `id`, `email`, `password`, `type` FROM usal_university.authentication WHERE `email` = '$email' and `password` = '$password' ;"; 
  $get_result = $conn->query($sql);

  if ($get_result->num_rows > 0) {
    while ($result = $get_result->fetch_assoc()) {
      //echo json_encode($result);
      $auth_id = $result['id'];
      $type = $result['type'];
      //echo $type;
      if($type == 'D'){
        $get_user_info = "SELECT `id`, `fname`, `lname` FROM usal_university.doctors WHERE `authentication_id` = $auth_id;";
        $get_user_info_result = $conn->query($get_user_info);
        if ($get_user_info_result->num_rows > 0) {
          while ($result_info = $get_user_info_result->fetch_assoc()) {
            //echo json_encode($result_info);
            $_SESSION["user_type"] = $type;
            $_SESSION["user_id"] = $result_info['id'];
            $_SESSION["user_full_name"] = $result_info['fname'] . " " . $result_info['lname'];
            header("Location: ./Reservation.php");
          }
        }
      }
      if ($type == 'S') {
        //echo $type;
          $get_user_info = "SELECT `id`, `fname`, `lname` FROM usal_university.students  WHERE `authentication_id` = $auth_id;";
          echo $get_user_info;
          $get_user_info_result = $conn->query($get_user_info);
          if ($get_user_info_result->num_rows > 0) {
            while ($result = $get_user_info_result->fetch_assoc()) {
              $_SESSION["user_type"] = $type;
              $_SESSION["user_id"] = $result['id'];
              header("Location: ./student_view.php");
            }
          }
      }
      
      
      


    }
  }
  else {
    if ($conn->error) {
      echo 'Errorrr: '.$sql .'<br>'. $conn->error;
    }
  }
}
if($_SESSION){
  if($_SESSION["user_type"] == 'D'){
  header("Location: ./Reservation.php");
}
if ($_SESSION["user_type"] == 'S') {
  header("Location: ./student_view.php");
}
}


 ?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./src/css/login.css" />

    <title>Document</title>
</head>

<body class="flex flex-col min-h-screen">
    <header>
        <div class='navbar border-b-2 border-gray-200 '>

            <div class="flex-1">
                <a class='text-xl m-2' href='./'>
                    <img src='./src/pictures/usal2 (2).ico' class='object-cover object-center h-14' alt='Shoes' />
                </a>
                <ul class="menu menu-horizontal p-0 ml-6">
                    <li><a href="./" class="font-bold text-md">Back</a></li>
                </ul>
            </div>
            <div class="flex-none">
                <h1 class="font-bold text-xl text-gray-500 p-4">Login Form</h1>
            </div>
        </div>
    </header>

    <main class="flex-grow  p-4">
        <!-- [[[[[[[[[[[[[[[[ main ]]]]]]]]]]]]]]]] -->
        <div class="hero min-h-min">
            <div class="hero-content flex-col lg:flex-row-reverse p-4 w-full max-w-3xl rounded-lg bg-base-300">
                <div class="text-center lg:text-left">
                    <h1 class="text-5xl font-bold">Login now!</h1>
                    <?php if ($get_result->num_rows <= 0) {
  echo "<h4 class=\"py-6 text-red-600\"> incorect email or password</h4>";
}?>
                </div>
                <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">
                    <div class="card-body">
                        <form name="doctor_login" action="<?php $_SERVER['PHP_SELF']?>" method='post'>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input type="email" name="email" placeholder="email" class="input input-bordered"
                                    required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Password</span>
                                </label>
                                <input name="password" type="password" placeholder="password"
                                    class="input input-bordered" required />
                                <label class="label">
                                    <a href="#" class="label-text-alt link link-hover">Forgot password?</a>
                                </label>
                            </div>
                            <div class="form-control mt-6">
                                <button type="submit" name="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer items-center p-4 border-t-2 border-gray-200 mt-2">
        <div class="items-center grid-flow-col place-self-center md:justify-self-start">
            <svg width="36" height="36" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                clip-rule="evenodd" class="fill-current hover:text-primary">
                <path
                    d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z">
                </path>
            </svg>
            <p>Copyright © 2022 - Barmajino</p>
        </div>

        <div class="grid-flow-col gap-4 place-self-center md:justify-self-end">
            <div class="tooltip" data-tip="+961 1 453 003">
                <a href="tel:+961 1 453 003"><svg class="w-6 h-6 hover:text-primary" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </a>
            </div>
            <div class="tooltip" data-tip="Map">
                <a
                    href="https://www.google.com/maps/place/USAL+-+University+Of+Sciences+And+Arts+In+Lebanon/@33.856327,35.5027542,15z/data=!4m5!3m4!1s0x0:0xf7c29c0898692ff9!8m2!3d33.856327!4d35.5027542"><svg
                        class="w-6 h-6 hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
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
                    <svg class="w-6 h-6 hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>
    <!-- the themes modal -->
    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="themes_modal" class="modal-toggle" />
    <label for="themes_modal" class="modal cursor-pointer">
        <label class="modal-box relative w-11/12 max-w-5xl p-1" for="">
            <!-- the heder of modal -->
            <div class="navbar bg-base-100 ">
                <div class="flex-1">
                    <span class="text-lg font-bold">Choose your favorite theme.</apan>
                </div>
                <div class="flex-none">
                    <label for="themes_modal"
                        class="btn btn-sm btn-circle btn-primary  right-2 top-2 font-bold text-xl flex items-center ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </label>
                </div>
            </div>
            <!-- the body of modal -->
            <div class="py-4 grid grid-cols-12 grid-flow-row overflow-y-scroll max-h-60" id="themes_modal_body">
                <!-- her we place the conntent use js like :  -->
                <!-- 
          <div class="col-span-6 sm:col-span-4 md:col-span-3 w-22 border-2 border-solid border-secondary rounded-md m-1 p-1 bg-base-300">
            <label class="label cursor-pointer flex flex-row space-x-4">
              <span class="label-text font-bold"> the theme name </span>
              <input
                onchange="set_theme(this.value)"
                name="themes_radio"
                value=" the theme value "
                type="radio"
                class="toggle toggle-secondary toggle-sm"
                
              />
            </label>
          </div> -->
            </div>
        </label>
    </label>
    <script src="./src/js/themes.js"></script>
</body>

</html>
