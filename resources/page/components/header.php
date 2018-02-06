<header role="banner" class="padding-small shadow">
    <div class="content cell-row">
        <div class="title cell cell-middle">
            <h1>Team 18 Helpdesk</h1>
        </div>
        <div class="login cell cell-middle">
            <strong>Logged in as:</strong>
            <span><?php if ($employee !== null) echo $employee['FirstName'] . ' ' . $employee['Surname'] ?></span>
        </div>
    </div>
</header>