<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendador</title>
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    
    <?php 
    global $auth;
    if ($auth) :?>
    <?php else: ?>
        <link rel="stylesheet" href="css/navbar.css">
    <?php 
    global $option;
    switch($option) :
        case 1:?>
        <?php break; 
        case 2:?>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/validator.css">
        <link rel="stylesheet" href="css/validator-validation.css">
        <?php break;
        case 3:?>
            <link rel="stylesheet" href="css/user.css">
            <link rel="stylesheet" href="css/validator.css">
        <?php break; 
        default:?>
        <?php break; 
        endswitch;
    endif;?>
</head>

<body>