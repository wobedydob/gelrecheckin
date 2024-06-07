<?php ?>

<form class="login-form" action="" method="post">
    <h2>Login</h2>
    <div class="form-group">
        <label for="passenger_id">Passagiernummer</label>
        <input type="text" id="passenger_id" name="passenger_id" required>
    </div>
    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" name="submit" value="submit">Login</button>

    <input type="hidden" name="action" value="login">

</form>