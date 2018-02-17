<footer>
    <div class="content">
        <div class="dynamic-font-container">
            <form action="/resources/page/utils/font-size.php" method="post">
                <input name="url" value="<?= $_SERVER["REQUEST_URI"] ?>" style="display:none">
                <p>
                    <strong>Select a font size:</strong>
                    <button class="font-small-button" name="font-small" value="1">A</button>
                    <button class="font-large-button" name="font-large" value="1">A</button>
                </p>
            </form>
        </div>
        <p>Team 18 Helpdesk created by Team 18</p>
        <p>Copyright &copy; Team 18, 2017-<?php echo (new DateTime())->format('Y'); ?></p>
        <p><strong>Team 18:</strong> William Baker, Simeon Dimitrov, William Masters, Eddie Page, Ross Massie, Daniel Christian-Lau</p>
    </div>
</footer>