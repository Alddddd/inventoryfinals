<?php

// Ensure $errors is defined
if (!isset($errors)) {
    $errors = [];
}

// Check if there are any errors to display
if (count($errors) > 0): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
