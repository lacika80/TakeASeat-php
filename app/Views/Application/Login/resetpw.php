<?php
if (!isset($_GET["token"])){?>
<form action="/login/pwresetpost" method="post" class="mb-3">
    <label for="email" class="form-label">Email cím</label>
    <input type="email" class="form-control" id="remail" autocomplete="email" value="root@localhost.com" name="email" required>
    <input type="submit" class="btn btn-primary mt-3"  value="jelszó visszaállítás">
</form>
<?php }else{ ?>
    <form action="/<?php echo $_GET["token"] ?>" method="post" class="mb-3">



        <label for="password" class="form-label">pw </label>
        <input type="password" class="form-control" id="password" autocomplete="new-password"  name="password" required>
        <label for="password2" class="form-label">pw újra </label>
        <input type="password" class="form-control" id="password2" autocomplete="new-password"  name="password2" required>


        <input type="submit" class="btn btn-primary mt-3"  value="jelszó visszaállítás">
    </form>
    megvan a kód
<?php } ?>