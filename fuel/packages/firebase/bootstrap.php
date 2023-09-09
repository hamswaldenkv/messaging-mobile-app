<?php


\Autoloader::add_core_namespace('Firebase');

\Autoloader::add_classes(array(
    'Firebase\\Firebase'                        => __DIR__ . '/classes/firebase.php',
    'Firebase\\Push'                            => __DIR__ . '/classes/push.php'
));
