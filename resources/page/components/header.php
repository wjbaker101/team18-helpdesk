<header role="banner" class="padding-small shadow">
    <div class="content cell-row">
        <div class="title cell cell-middle">
            <h1>Team 18 Helpdesk</h1>
        </div>
        <?php if ($employee !== null) { ?>
        <div class="login cell cell-middle">
            <strong>Logged in as:</strong>
            <span><?php if ($employee !== null) echo $employee['FirstName'] . ' ' . $employee['Surname'] ?> <a href="/users/log-out.php"><strong>(Switch)</strong></a></span>
        </div>
        <?php } ?>
    </div>
</header>