<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

if ($employee === null)
{
    header ('Location: /users/login.php');
    exit;
}

include('create.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - New Ticket</title>
        
        <meta name="description" content="Create a new ticket.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .details-panel
            {
                width: 300px;
                position: sticky;
                top: 15px;
            }
            
            .ticket-panel
            {
                width: calc(100% - 300px);
            }
            
            .summary-input
            {
                width: 100%;
            }
            
            .description-input
            {
                width: 100%;
                min-height: 150px;
                resize: vertical;
            }
            
            .section-details
            {
                display: none;
            }
            
            .section-details.visible
            {
                display: block;
            }
            
            .employees-select-container
            {
                display: table;
            }
            
            .employees-select
            {
                width: 100%;
                height: 150px;
            }
            
            .employee-list-container
            {
                height: 300px;
                margin: 15px 0;
                position: relative;
                border: 1px solid #ccc;
                overflow-y: scroll;
            }
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script src="/resources/scripts/employee-search.js" defer></script>
        
        <script>
            const onSpecialistToggle = (e) =>
            {
                $('.specialist-info-details').toggleClass('visible', e.target.checked);
            };
            
            const onResolutionToggle = (e) =>
            {
                $('.close-ticket-details').toggleClass('visible', e.target.checked);
            };
            
            window.addEventListener('load', () =>
            {
                new EmployeeSearch('.caller-name-input', '.caller-employees');
                new EmployeeSearch('.specialist-name-input', '.specialist-employees', true);
                
                $('.specialist-info-enabled').change(onSpecialistToggle);
                $('.close-ticket-enabled').change(onResolutionToggle);
            });
        </script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/view-tickets/">&larr; Return to Tickets Overview</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width column-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="column l4 m12 padding-small">
                    <div class="bg-white shadow">
                        <div class="content-section padding-small">
                            <h2>Ticket Details</h2>
                            <h4><abbr title="A short description to help distinguish between tickets.">Short Summary:</abbr></h4>
                            <input class="summary-input" type="text" name="summary" value="<?php if (isset($_POST['summary'])) echo htmlspecialchars($_POST['summary']); ?>" autofocus>
                            <?= $messages->summary ?>
                            <h4><abbr title="How should this ticket be treated relative to others.">Priority:</abbr></h4>
                            <p>
                                <input class="urgent-input" type="radio" name="priority-group" value="3">
                                <span>Urgent</span>
                            </p>
                            <p>
                                <input class="high-input" type="radio" name="priority-group" value="2">
                                <span>High</span>
                            </p>
                            <p>
                                <input class="medium-input" type="radio" name="priority-group" value="1" checked>
                                <span>Medium</span>
                            </p>
                            <p>
                                <input class="low-input" type="radio" name="priority-group" value="0">
                                <span>Low</span>
                            </p>
                            <?= $messages->priority ?>
                        </div>
                        <div class="content-section padding-small">
                            <p><button type="submit" name="submitted" value="1">Create Ticket</button></p>
                            <?= $messages->error ?>
                        </div>
                    </div>
                </div>
                <div class="column l8 m12 padding-small">
                    <div class="bg-white shadow padding-small section">
                        <h1>Create a New Ticket</h1>
                    </div>
                    <div class="bg-white shadow padding-small section">
                        <div class="content-section padding-small">
                            <h2>Caller Details</h2>
                            <div class="column-container">
                                <div class="column l6 s12 v-content-section">
                                    <h4>Employee ID</h4>
                                    <input class="caller-id-input" type="text" name="caller-id" value="<?php if (isset($_POST['caller-id'])) echo htmlspecialchars($_POST['caller-id']); ?>">
                                    <?= $messages->callerID ?>
                                    <h4>Telephone Number</h4>
                                    <input class="telephone-input" type="text" name="telephone-number" value="<?php if (isset($_POST['telephone-number'])) echo htmlspecialchars($_POST['telephone-number']); ?>">
                                    <?= $messages->telephoneNumber ?>
                                </div>
                                <div class="column l6 s12 v-content-section">
                                    <h4>Search Employee Name</h4>
                                    <input class="caller-name-input" type="text">
                                    <div class="employee-list-container caller-employees"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow padding-small section">
                        <div class="content-section padding-small">
                            <h2><abbr title="A more detailed explanation about the problem.">Full Ticket Details</abbr></h2>
                            <textarea class="description-input" name="description" placeholder="A more detailed explanation about the problem."><?php if (isset($_POST['description'])) echo htmlspecialchars($_POST['description']); ?></textarea>
                            <?= $messages->description ?>
                        </div>
                    </div>
                    <div class="bg-white shadow padding-small section">
                        <div class="content-section padding-small">
                            <h2>Problem Information</h2>
                            <h4><abbr title="The type of problem this ticket is categorised as.">Problem Type</abbr></h4>
                            <!--<input class="problem-type-input" type="text" name="problem-type" value="<?php if (isset($_POST['problem-type'])) echo htmlspecialchars($_POST['problem-type']); ?>">-->
                            <select class="problem-type-input" name="problem-type">
                                <option>Network</option>
                                <option>Hardware</option>
                                <option>Software</option>
                            </select>
                            <?= $messages->problemType ?>
                            <h4><abbr title="Hardware serial ID related to the problem.">Hardware Serial ID</abbr></h4>
                            <input class="hardware-serial-id-input" type="text" name="hardware-serial-id" placeholder="7EIQ-72IU-2YNV-3L4Y" value="<?php if (isset($_POST['hardware-serial-id'])) echo htmlspecialchars($_POST['hardware-serial-id']); ?>">
                            <?= $messages->hardwareSerialID ?>
                            <div class="column-container">
                                <div class="column l6 s12">
                                    <h4><abbr title="The name of the operating system being used.">Device Operating System</abbr></h4>
                                    <input class="os-input" type="text" name="operating-system" placeholder="Windows 10" value="<?php if (isset($_POST['operating-system'])) echo htmlspecialchars($_POST['operating-system']); ?>">
                                    <?= $messages->operatingSystem ?>
                                </div>
                                <div class="column l6 s12">
                                    <h4><abbr title="The version number of the operating system.">Operating System Version</abbr></h4>
                                    <input class="os-version-input" type="text" name="os-version" placeholder="5.7.1.0" value="<?php if (isset($_POST['os-version'])) echo htmlspecialchars($_POST['os-version']); ?>">
                                </div>
                            </div>
                            <div class="column-container">
                                <div class="column l6 s12">
                                    <h4><abbr title="The name of the software related to the problem.">Software Name</abbr></h4>
                                    <input class="software-input" type="text" name="software" placeholder="Google Chrome" value="<?php if (isset($_POST['software'])) echo htmlspecialchars($_POST['software']); ?>">
                                    <?= $messages->software ?>
                                </div>
                                <div class="column l6 s12">
                                    <h4><abbr title="The version number of the software.">Software Version</abbr></h4>
                                    <input class="software-version-input" type="text" name="software-version" placeholder="12.3.6.1" value="<?php if (isset($_POST['software-version'])) echo htmlspecialchars($_POST['software-version']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow padding-small section">
                        <div class="content-section padding-small">
                            <div class="cell-row">
                                <h2 class="cell l6 cell-middle">Specialist Information</h2>
                                <div class="cell l6 cell-middle text-right">
                                    <input class="specialist-info-enabled" type="checkbox" name="assign-specialist" value="checked" checked>
                                    <span>Enable</span>
                                </div>
                            </div>
                            <div class="specialist-info-details section-details <?php echo isset($_POST['assign-specialist']) ? '' : 'visible' ?>">
                                <div class="column-container">
                                    <div class="column l6 s12 v-content-section">
                                        <h4>Specialist ID</h4>
                                        <input class="specialist-id-input" type="text" name="specialist-id" value="<?php if (isset($_POST['specialist-id'])) echo htmlspecialchars($_POST['specialist-id']); ?>">
                                        <?= $messages->specialist ?>
                                    </div>
                                    <div class="column l6 s12 v-content-section">
                                        <h4>Search Specialist Name</h4>
                                        <input class="specialist-name-input" type="text">
                                        <div class="employee-list-container specialist-employees"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow padding-small section">
                        <div class="content-section padding-small">
                            <div class="cell-row">
                                <h2 class="cell l6 cell-middle">Immediately Close Ticket</h2>
                                <div class="cell l6 cell-middle text-right">
                                    <input class="close-ticket-enabled" type="checkbox" name="resolution" value="checked">
                                    <span>Enable</span>
                                </div>
                            </div>
                            <div class="close-ticket-details section-details <?php echo isset($_POST['resolution']) && strlen($_POST['resolution']) > 0 ? 'visible' : '' ?>">
                                <h4><abbr title="A description of how the problem was resolved.">Resolution Description</abbr></h4>
                                <textarea class="description-input" name="resolution-description"><?php if (isset($_POST['resolution-description'])) echo htmlspecialchars($_POST['resolution-description']); ?></textarea>
                                <?= $messages->resolutionDescription ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>