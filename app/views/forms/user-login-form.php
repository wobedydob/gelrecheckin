<?php ?>

<form action=""
      method="post"
>
    <label for="passenger_id">Passagiernummer</label>
    <input type="text"
           id="passenger_id"
           name="passenger_id"
           value=""
    ><br>
    <label for="password">Wachtwoord</label>
    <input type="password"
           id="password"
           name="password"
           value=""
    ><br>
    <input type="submit"
           name="submit"
           value="submit"
    >
    <input type="hidden"
           name="action"
           value="login"
    >
</form>