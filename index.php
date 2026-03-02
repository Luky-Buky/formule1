<?php
$file = "profile.json";
$message = "";
$messageType = "";

// Načtení existujících dat
if (file_exists($file)) {
    $jsonData = file_get_contents($file);
    $profile = json_decode($jsonData, true);
} else {
    $profile = ["interests" => []];
}

// Zpracování formuláře
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $newInterest = trim($_POST["new_interest"]);

    if (!empty($newInterest)) {

        // Kontrola duplicit (nezáleží na velikosti písmen)
        $lowercaseInterests = array_map("strtolower", $profile["interests"]);

        if (in_array(strtolower($newInterest), $lowercaseInterests)) {
            $message = "Tento zájem již existuje.";
            $messageType = "error";
        } else {
            $profile["interests"][] = $newInterest;

            file_put_contents(
                $file,
                json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $message = "Zájem byl úspěšně přidán.";
            $messageType = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 4.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>IT Profil 4.0</h1>

<h2>Moje zájmy</h2>
<ul>
    <?php foreach ($profile["interests"] as $interest): ?>
        <li><?php echo htmlspecialchars($interest); ?></li>
    <?php endforeach; ?>
</ul>

<h2>Přidat nový zájem</h2>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat zájem</button>
</form>

</body>
</html>