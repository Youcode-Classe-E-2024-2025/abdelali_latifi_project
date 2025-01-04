<?php
require_once '../backoffice/authentication.php'; 
session_start();

$login = new Login();
$error = '';

if (isset($_POST['submit'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            $result = $login->login($username, $password);
            // La redirection est maintenant gérée dans la méthode login
            // Si nous arrivons ici, c'est qu'il y a eu une erreur
            if ($result === 10) {
                $error = "Incorrect password.";
            } elseif ($result === 100) {
                $error = "User not found.";
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "A server error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Improved Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-fixed bg-gray-100 min-h-screen">
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow">
        <h1 class="text-2xl font-extrabold text-gray-800">TASKFLOW</h1>
        <div>
            <a href="../frontoffice/registre_page.php" id="sign_up" class="px-4 py-2 text-xl font-bold text-white bg-violet-600 rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-400">
                Sign Up
            </a>
        </div>
    </header>
    <section id="FormSignIn" class="flex flex-col items-center w-full max-w-lg mx-auto mt-10 p-6 bg-white shadow rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Log In</h2>
        <form action="" method="post" class="w-full space-y-4">
            <div>
                <label for="username" class="block text-xl font-bold text-gray-700">Name</label>
                <input id="username" name="username" type="text" placeholder="Enter your name"
                    class="w-full p-2 mt-1 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label for="password" class="block text-xl font-bold text-gray-700">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password"
                    class="w-full p-2 mt-1 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <input type="submit" value="Log In" name="submit"
                class="w-full p-3 mt-4 font-bold text-white bg-violet-600 rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-400">
        </form>
        <?php if (isset($error)): ?>
            <div class="text-red-600 mt-4"><?php echo $error; ?></div>
        <?php endif; ?>
    </section>
</body>

</html>
