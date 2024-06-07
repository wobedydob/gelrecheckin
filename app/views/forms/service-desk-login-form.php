<?php
/** @var $serviceDesks array */
?>

<form class="login-form" action="" method="post">
    <h2>Login</h2>

    <div class="form-group">
        <label for="desk_id">Balienummer</label>
        <select id="desk_id" name="desk_id">
            <?php foreach ($serviceDesks as $deskNumbers): ?>
                <?php $deskNumber = $deskNumbers['balienummer']; ?>
                <option value="<?php echo $deskNumber; ?>"><?php echo $deskNumber; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" name="submit" value="submit">Login</button>

    <input type="hidden" name="action" value="login">

</form>