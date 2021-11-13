<?php if (isset($_SESSION['danger'])) { ?>
    <div class="alert-danger text-center">
        <?= ($_SESSION['danger']) ?>
    </div>
    <?php unset($_SESSION['danger']); ?>
<?php } ?>

<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success">
        <?= ($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']) ?>
<?php } ?>