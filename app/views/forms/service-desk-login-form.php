<?php
/** @var $serviceDesks array */
/** @var $errors array */

$errors = $errors ?? [];
?>

<form class="login-form" action="" method="post">
    <h2>Inloggen als Medewerker</h2>

    <div class="form-group">
        <label for="desk_id">Balienummer</label>
        <select id="desk_id" name="desk_id">
            <?php foreach ($serviceDesks as $serviceDesk): /** @var $serviceDesk \Model\ServiceDesk */ ?>
                <?php $deskNumber = $serviceDesk->balienummer; ?>
                <option value="<?php echo $deskNumber; ?>"><?php echo $deskNumber; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button class="button primary" type="submit" name="submit" value="submit">Login</button>

    <div id="credentials_error"></div>

    <input type="hidden" name="action" value="login">
</form>