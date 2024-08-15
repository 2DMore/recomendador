        <!-- <input type="checkbox" name="sidebar" id="sidebar"> -->
<?php
require_once app_path() . '/Helpers/NavBarHelper.php';
?>
<!DOCTYPE html>
        <div class="sideContainer">
    <nav class="sidebar active">
        <a class="logo" href="">
            <img height="100px" src="images/UADY_logo.svg" alt="logo">
        </a>
        <!-- <label for="sidebar">press</label> -->
        <button class="actionSidebar"><img src="images/right-arrow.png" alt=""></button>
        <!-- <img style="float:right;" src="../../Desktop/STK-20200827-WA0046.webp" alt="" srcset="" height="100%"> -->

        <div class="options-nav">


        <!--Opciones de navegacion borradas agregarlas despues -->
        <?php foreach (get_nav_elements(session('user_type')) as $element): ?>
                <a class="option" href="<?php echo $element['link']; ?>">
                    <img class="optionIcon" src="<?php echo $element['img_source']; ?>" alt="">
                    <label><?php echo $element['text']; ?></label>
                </a>
        <?php endforeach; ?>
       <br>
        <a style="border-top: 2px solid" class="option" href="./logout">
                    <img class="optionIcon" src="images/graph.svg" alt="">
                    <label>Cerrar sesión</label>
                </a>

        </div>
    </nav>
</div>
{{-- Importa la función de NavBarHelper.php de la carpeta Helpers --}}


