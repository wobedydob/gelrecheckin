<?php
/** @var $serviceDesks array */
?>

<form action=""
      method="post"
>
    <label for="desk_id">Balienummer</label>
    <select id="desk_id"
            name="desk_id"
    >
        <?php foreach ($serviceDesks as $deskNumbers): ?>
            <?php $deskNumber = $deskNumbers['balienummer']; ?>
            <option value="<?php echo $deskNumber; ?>"><?php echo $deskNumber; ?></option>
        <?php endforeach; ?>
    </select>
    <br>
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