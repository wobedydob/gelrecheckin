<form class="login-form" action="" method="post">
    <h2>Inloggen als Passagier</h2>

    <div class="form-group">
        <label for="passenger_id">Passagiernummer</label>
        <input type="text" id="passenger_id" name="passenger_id" required>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button class="button secondary" type="submit" name="submit" value="submit">Inloggen</button>

    <div id="credentials_error"></div>

    <input type="hidden" name="action" value="login">
</form>